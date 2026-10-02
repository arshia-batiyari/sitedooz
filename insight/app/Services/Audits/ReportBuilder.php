<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Enums\FindingSeverity;
use App\Models\Audit;
use App\Models\AuditFinding;

final class ReportBuilder
{
    /**
     * @return array<string, mixed>
     */
    public function build(Audit $audit): array
    {
        $audit->loadMissing(['findings', 'pages', 'metrics']);

        $findings = $audit->findings->sort(function (AuditFinding $left, AuditFinding $right): int {
            $rank = $left->severity->rank() <=> $right->severity->rank();
            if ($rank !== 0) {
                return $rank;
            }

            return (float) $right->score_impact <=> (float) $left->score_impact;
        })->values();

        $critical = $findings->filter(
            fn (AuditFinding $finding): bool => in_array($finding->severity, [FindingSeverity::Critical, FindingSeverity::High], true),
        )->values();

        $actions = $findings->filter(
            fn (AuditFinding $finding): bool => ! $finding->needs_human_review && $finding->severity !== FindingSeverity::Info,
        )->take(5)->values();

        $grouped = [];
        foreach ($findings as $finding) {
            $grouped[$finding->category][] = $this->finding($finding);
        }

        $performance = $audit->metrics
            ->where('source', 'pagespeed')
            ->map(fn ($metric): array => [
                'name' => $metric->name,
                'value' => $metric->value,
                'unit' => $metric->unit,
                'strategy' => $metric->payload['strategy'] ?? null,
            ])->values();

        return [
            'uuid' => $audit->uuid,
            'status' => $audit->status->value,
            'status_label' => $audit->status->label(),
            'url' => $audit->url,
            'normalized_url' => $audit->normalized_url,
            'overall_score' => $audit->overall_score,
            'category_scores' => $this->categories($audit),
            'critical_and_high' => $critical->map(fn (AuditFinding $finding): array => $this->finding($finding))->all(),
            'findings_by_category' => $grouped,
            'top_actions' => $actions->map(fn (AuditFinding $finding): array => $this->finding($finding))->all(),
            'technical_metrics' => $audit->metrics->map(fn ($metric): array => [
                'source' => $metric->source,
                'name' => $metric->name,
                'value' => $metric->value,
                'unit' => $metric->unit,
            ])->values()->all(),
            'page_problems' => $audit->pages->filter(
                fn ($page): bool => ($page->status_code !== null && $page->status_code >= 400)
                    || ($page->metadata['error'] ?? null) !== null
                    || ($page->metadata['blocked_by_robots'] ?? false) === true,
            )->map(fn ($page): array => $this->page($page))->values()->all(),
            'pages' => $audit->pages->map(fn ($page): array => $this->page($page))->values()->all(),
            'performance' => $performance->all(),
            'conversion_opportunities' => $findings
                ->where('category', 'conversion')
                ->map(fn (AuditFinding $finding): array => $this->finding($finding))
                ->values()
                ->all(),
            'needs_human_review' => $findings
                ->where('needs_human_review', true)
                ->map(fn (AuditFinding $finding): array => $this->finding($finding))
                ->values()
                ->all(),
            'crawl_stats' => $audit->crawl_stats,
            'error_message' => $audit->error_message,
            'lead' => [
                'name' => $audit->name,
                'mobile' => $audit->mobile,
                'email' => $audit->email,
                'business_name' => $audit->business_name,
                'lead_source' => $audit->lead_source,
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function categories(Audit $audit): array
    {
        $stored = $audit->category_scores ?? [];
        $rows = [];
        foreach (config('audit.weights') as $key => $weight) {
            $rows[] = [
                'key' => $key,
                'label' => config('audit.labels.'.$key),
                'weight' => $weight,
                'score' => $stored[$key] ?? null,
            ];
        }

        return $rows;
    }

    /**
     * @return array<string, mixed>
     */
    private function finding(AuditFinding $finding): array
    {
        return [
            'rule_key' => $finding->rule_key,
            'category' => $finding->category,
            'category_label' => config('audit.labels.'.$finding->category),
            'severity' => $finding->severity->value,
            'severity_label' => $finding->severity->label(),
            'title' => $finding->title,
            'description' => $finding->description,
            'business_impact' => $finding->business_impact,
            'recommendation' => $finding->recommendation,
            'page_url' => $finding->page_url,
            'metadata' => $finding->metadata,
            'score_impact' => $finding->score_impact,
            'needs_human_review' => $finding->needs_human_review,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function page(mixed $page): array
    {
        return [
            'url' => $page->url,
            'final_url' => $page->final_url,
            'status_code' => $page->status_code,
            'depth' => $page->depth,
            'indexable' => $page->indexable,
            'title' => $page->title,
            'word_count' => $page->word_count,
            'metadata' => $page->metadata,
        ];
    }
}
