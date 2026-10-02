<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Enums\AuditStatus;
use App\Models\Audit;
use App\Services\Audits\Data\CertificateReport;
use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\FindingDraft;
use App\Services\Audits\Data\PageSpeedReport;
use App\Services\Audits\Data\StrategyMetrics;

final class AuditPersister
{
    /**
     * @param  list<FindingDraft>  $drafts
     * @param  array<string, float|null>  $categoryScores
     */
    public function persist(
        Audit $audit,
        CrawlResult $crawl,
        array $drafts,
        ?float $overall,
        array $categoryScores,
        PageSpeedReport $speed,
        CertificateReport $certificate,
    ): void {
        $audit->findings()->delete();
        $audit->metrics()->delete();
        $audit->pages()->delete();

        $pageIds = [];
        foreach ($crawl->pages as $page) {
            $robots = strtolower($page->metaRobots ?? '');
            $indexable = $page->isHtmlSuccess()
                && ! $page->blockedByRobots
                && ! str_contains($robots, 'noindex')
                && ! str_contains($robots, 'none');

            $model = $audit->pages()->create([
                'url' => $page->url,
                'final_url' => $page->finalUrl,
                'status_code' => $page->statusCode,
                'redirect_chain' => $page->redirectChain,
                'depth' => $page->depth,
                'indexable' => $indexable,
                'title' => $page->title,
                'content_hash' => hash('sha256', ($page->title ?? '').'|'.($page->metaDescription ?? '')),
                'word_count' => $page->wordCount,
                'metadata' => [
                    'canonical' => $page->canonical,
                    'h1' => $page->h1,
                    'error' => $page->error,
                    'blocked_by_robots' => $page->blockedByRobots,
                    'content_type' => $page->contentType,
                ],
            ]);
            $pageIds[$page->url] = $model->id;
            $pageIds[$page->finalUrl] = $model->id;
        }

        foreach ($drafts as $draft) {
            $audit->findings()->create([
                'audit_page_id' => $draft->pageUrl !== null ? ($pageIds[$draft->pageUrl] ?? null) : null,
                'rule_key' => $draft->ruleKey,
                'category' => $draft->category,
                'severity' => $draft->severity,
                'title' => $draft->title,
                'description' => $draft->description,
                'business_impact' => $draft->businessImpact,
                'recommendation' => $draft->recommendation,
                'page_url' => $draft->pageUrl,
                'metadata' => $draft->metadata,
                'score_impact' => $draft->scoreImpact,
                'needs_human_review' => $draft->needsHumanReview,
            ]);
        }

        $this->storeSpeed($audit, 'mobile', $speed->mobile);
        $this->storeSpeed($audit, 'desktop', $speed->desktop);
        if ($certificate->applicable) {
            $audit->metrics()->create([
                'source' => 'certificate',
                'name' => 'valid',
                'value' => $certificate->valid ? 1 : 0,
                'unit' => 'bool',
                'payload' => ['valid_to' => $certificate->validTo, 'detail' => $certificate->detail],
            ]);
        }

        $audit->update([
            'status' => AuditStatus::Completed,
            'overall_score' => $overall,
            'category_scores' => $categoryScores,
            'crawl_stats' => $crawl->stats,
            'error_message' => null,
            'finished_at' => now(),
        ]);
    }

    private function storeSpeed(Audit $audit, string $strategy, ?StrategyMetrics $metrics): void
    {
        if ($metrics === null) {
            return;
        }

        $rows = [
            'performance_score' => [$metrics->performanceScore, 'score'],
            'lcp' => [$metrics->lcp, 'ms'],
            'inp' => [$metrics->inp, 'ms'],
            'cls' => [$metrics->cls, 'score'],
            'fcp' => [$metrics->fcp, 'ms'],
            'ttfb' => [$metrics->ttfb, 'ms'],
            'speed_index' => [$metrics->speedIndex, 'ms'],
            'total_blocking_time' => [$metrics->tbt, 'ms'],
        ];

        foreach ($rows as $name => [$value, $unit]) {
            if ($value === null) {
                continue;
            }
            $audit->metrics()->create([
                'source' => 'pagespeed',
                'name' => $name.'_'.$strategy,
                'value' => $value,
                'unit' => $unit,
                'payload' => ['strategy' => $strategy],
            ]);
        }
    }
}
