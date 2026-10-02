<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Enums\AuditStatus;
use App\Enums\FindingSeverity;
use App\Models\Audit;
use App\Models\AuditFinding;
use App\Models\AuditMetric;

final class AuditSnapshot
{
    public function __construct(private readonly ReportSummary $summary) {}

    /**
     * @return array<string, mixed>
     */
    public function for(Audit $audit): array
    {
        $audit->load(['pages', 'findings', 'metrics']);
        $findings = $audit->findings
            ->map(fn (AuditFinding $finding): array => [
                'rule_key' => $finding->rule_key,
                'category' => $finding->category,
                'severity' => $finding->severity->value,
                'title' => $finding->title,
                'url' => $finding->page_url,
                'recommendation' => $finding->recommendation,
            ])
            ->values()
            ->all();
        $report = $this->summary->summarize($findings);

        return [
            'uuid' => $audit->uuid,
            'url' => $audit->normalized_url,
            'host' => $audit->host,
            'status' => $audit->status->value,
            'status_label' => $audit->status->label(),
            'overall_score' => $audit->overall_score === null ? null : (int) round((float) $audit->overall_score),
            'categories' => $this->categories($audit),
            'labels' => config('audit.labels'),
            'progress' => $this->progress($audit),
            'pages' => $audit->pages->map(fn ($page): array => [
                'url' => $page->url,
                'path' => $this->pathOf($page->url),
                'status_code' => $page->status_code,
            ])->values()->all(),
            'findings_count' => count($findings),
            'live_findings' => $this->liveFindings($findings),
            'findings' => $findings,
            'top_issues' => $report['top_issues'],
            'top_recommendations' => $report['top_recommendations'],
            'activity' => $this->activity($audit, $findings),
            'error' => $audit->status === AuditStatus::Failed ? $audit->error_message : null,
            'broadcast' => [
                'key' => (string) config('broadcasting.connections.reverb.key'),
                'host' => (string) (config('broadcasting.connections.reverb.options.host') ?: '127.0.0.1'),
                'port' => (int) (config('broadcasting.connections.reverb.options.port') ?: 8081),
                'scheme' => (string) (config('broadcasting.connections.reverb.options.scheme') ?: 'http'),
            ],
        ];
    }

    /**
     * @return array<string, int>
     */
    private function categories(Audit $audit): array
    {
        $scores = [];
        foreach ($audit->category_scores ?? [] as $key => $score) {
            if (is_numeric($score)) {
                $scores[(string) $key] = (int) round((float) $score);
            }
        }

        return $scores;
    }

    private function progress(Audit $audit): int
    {
        if ($audit->status === AuditStatus::Completed) {
            return 100;
        }
        if ($audit->status === AuditStatus::Queued) {
            return 0;
        }

        $stats = $audit->crawl_stats ?? [];
        if (in_array($audit->status, [AuditStatus::Analyzing, AuditStatus::GeneratingReport], true)) {
            return AuditProgress::fromAnalyzers(
                (int) ($stats['analyzers_completed'] ?? 0),
                (int) ($stats['analyzers_total'] ?? 0),
            );
        }

        return AuditProgress::fromCrawl(
            (int) ($stats['page_number'] ?? $audit->pages->count()),
            (int) ($stats['pages_found'] ?? max(1, $audit->pages->count())),
        );
    }

    /**
     * @param  list<array{rule_key: string, category: string, severity: string, title: string, url: ?string, recommendation: string}>  $findings
     * @return list<array{rule_key: string, category: string, severity: string, title: string, url: ?string, recommendation: string}>
     */
    private function liveFindings(array $findings): array
    {
        $important = array_values(array_filter(
            $findings,
            fn (array $finding): bool => in_array($finding['severity'], ['critical', 'high', 'medium'], true),
        ));
        usort($important, fn (array $left, array $right): int => $this->rank($left['severity']) <=> $this->rank($right['severity']));

        return array_slice($important, 0, 4);
    }

    /**
     * @param  list<array{rule_key: string, category: string, severity: string, title: string, url: ?string, recommendation: string}>  $findings
     * @return list<array{key: string, tone: string, text: string}>
     */
    private function activity(Audit $audit, array $findings): array
    {
        $lines = [];
        foreach (['robots_txt', 'sitemap', 'https', 'response_time'] as $name) {
            $metric = $audit->metrics->firstWhere('name', $name);
            if (! $metric instanceof AuditMetric) {
                continue;
            }
            $text = $this->metricText($metric);
            if ($text !== null) {
                $lines[] = ['key' => 'feed:metric:'.$name, 'tone' => 'check', 'text' => $text];
            }
        }

        foreach ($audit->pages as $page) {
            $lines[] = [
                'key' => 'feed:page:'.$page->url,
                'tone' => 'check',
                'text' => 'صفحه '.$this->pathOf($page->url).' بررسی شد',
            ];
        }

        foreach ($findings as $finding) {
            if (! in_array($finding['severity'], ['critical', 'high', 'medium'], true)) {
                continue;
            }
            $lines[] = [
                'key' => 'feed:finding:'.$finding['rule_key'].':'.($finding['url'] ?? ''),
                'tone' => 'warn',
                'text' => $finding['title'],
            ];
        }

        $labels = config('audit.labels');
        foreach ($this->categories($audit) as $key => $score) {
            $label = is_array($labels) ? (string) ($labels[$key] ?? $key) : $key;
            $lines[] = [
                'key' => 'feed:analyzer:'.$key,
                'tone' => 'check',
                'text' => 'تحلیل '.$label.' تکمیل شد',
            ];
        }

        return $lines;
    }

    private function metricText(AuditMetric $metric): ?string
    {
        $value = (float) $metric->value;

        return match ($metric->name) {
            'robots_txt' => $value >= 1 ? 'robots.txt بررسی شد' : 'robots.txt پیدا نشد',
            'sitemap' => $value > 0 ? 'نقشه سایت پیدا شد' : 'نقشه سایت پیدا نشد',
            'https' => $value >= 1 ? 'اتصال HTTPS بررسی شد' : 'اتصال صفحه اصلی روی HTTP بود',
            'response_time' => 'زمان پاسخ صفحه اصلی: '.(int) round($value).' میلی‌ثانیه',
            default => null,
        };
    }

    private function pathOf(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (! is_string($path) || $path === '' || $path === '/') {
            return '/';
        }

        return $path;
    }

    private function rank(string $severity): int
    {
        return FindingSeverity::tryFrom($severity)?->rank() ?? 4;
    }
}
