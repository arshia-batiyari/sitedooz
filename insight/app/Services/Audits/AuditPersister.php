<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Enums\AuditStatus;
use App\Models\Audit;
use App\Models\AuditPage;
use App\Services\Audits\Analysis\FindingDraft;
use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\PageSnapshot;

final class AuditPersister
{
    public function rememberPage(Audit $audit, PageSnapshot $page, ?string $discoveredFrom = null): AuditPage
    {
        $existing = $audit->pages()->where('url', $page->url)->first();
        if ($existing !== null) {
            return $existing;
        }

        $robots = strtolower($page->metaRobots ?? '');
        $headings = array_filter($page->h1, fn (string $heading): bool => trim($heading) !== '');

        return $audit->pages()->create([
            'url' => $page->url,
            'final_url' => $page->finalUrl,
            'status_code' => $page->statusCode,
            'redirect_chain' => $page->redirectChain,
            'depth' => $page->depth,
            'indexable' => $page->isHtmlSuccess()
                && ! str_contains($robots, 'noindex')
                && ! str_contains($robots, 'none'),
            'title' => $page->title,
            'content_hash' => hash('sha256', ($page->title ?? '').'|'.($page->metaDescription ?? '')),
            'word_count' => $page->wordCount,
            'metadata' => [
                'canonical' => $page->canonical,
                'error' => $page->error,
                'blocked_by_robots' => $page->blockedByRobots,
                'content_type' => $page->contentType,
                'discovered_from' => $discoveredFrom,
                'internal_link_count' => count($page->internalLinks),
                'internal_links' => array_slice($page->internalLinks, 0, 24),
                'has_meta_description' => is_string($page->metaDescription) && trim($page->metaDescription) !== '',
                'has_h1' => $headings !== [],
            ],
        ]);
    }

    public function rememberCrawlProgress(Audit $audit, int $pageNumber, int $pagesFound): void
    {
        $stats = $audit->crawl_stats ?? [];
        $stats['page_number'] = $pageNumber;
        $stats['pages_found'] = $pagesFound;
        $audit->update(['crawl_stats' => $stats]);
    }

    public function rememberMetric(Audit $audit, string $source, string $name, float|int $value, ?string $unit): void
    {
        $audit->metrics()->updateOrCreate(
            ['source' => $source, 'name' => $name],
            ['value' => $value, 'unit' => $unit, 'payload' => null],
        );
    }

    public function rememberFinding(Audit $audit, FindingDraft $finding): void
    {
        $url = $finding->url ?? $audit->normalized_url;
        $pageId = $audit->pages()->where('url', $url)->value('id');

        $audit->findings()->updateOrCreate(
            ['rule_key' => $finding->ruleKey, 'page_url' => $url],
            [
                'audit_page_id' => $pageId,
                'category' => $finding->category,
                'severity' => $finding->severity,
                'title' => $finding->title,
                'description' => $finding->description,
                'business_impact' => $finding->businessImpact,
                'recommendation' => $finding->recommendation,
                'metadata' => null,
                'score_impact' => 0,
                'needs_human_review' => false,
            ],
        );
    }

    public function rememberScore(Audit $audit, ?int $overall, array $categories): void
    {
        $audit->update([
            'overall_score' => $overall,
            'category_scores' => $categories,
        ]);
    }

    public function rememberAnalyzerProgress(Audit $audit, int $completed, int $total): void
    {
        $stats = $audit->crawl_stats ?? [];
        $stats['analyzers_completed'] = $completed;
        $stats['analyzers_total'] = $total;
        $audit->update(['crawl_stats' => $stats]);
    }

    public function finishCrawl(Audit $audit, CrawlResult $crawl): void
    {
        $audit->update([
            'crawl_stats' => array_merge($audit->crawl_stats ?? [], $crawl->stats, [
                'sitemap_status' => $crawl->sitemapStatus,
                'robots_found' => $crawl->robotsFound,
            ]),
        ]);
    }

    /**
     * @param  array<string, int>  $categories
     */
    public function complete(Audit $audit, ?int $overall, array $categories): void
    {
        $audit->update([
            'status' => AuditStatus::Completed,
            'overall_score' => $overall,
            'category_scores' => $categories,
            'error_message' => null,
            'finished_at' => now(),
        ]);
    }

    public function retry(Audit $audit): void
    {
        $audit->metrics()->delete();
        $audit->findings()->delete();
        $audit->pages()->delete();
        $audit->update([
            'status' => AuditStatus::Queued,
            'overall_score' => null,
            'category_scores' => null,
            'error_message' => null,
            'crawl_stats' => null,
            'started_at' => null,
            'finished_at' => null,
        ]);
    }
}
