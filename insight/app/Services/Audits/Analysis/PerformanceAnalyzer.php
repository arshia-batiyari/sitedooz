<?php

declare(strict_types=1);

namespace App\Services\Audits\Analysis;

use App\Enums\FindingSeverity;
use App\Services\Audits\Data\CrawlResult;

final class PerformanceAnalyzer
{
    public function analyze(CrawlResult $crawl): ?CategoryAnalysis
    {
        $duration = $crawl->stats['homepage_duration_ms'] ?? null;
        if (! is_int($duration)) {
            return null;
        }

        $good = (int) config('audit.performance.good_ms');
        $poor = (int) config('audit.performance.poor_ms');
        $home = $crawl->homepage();
        $metrics = [[
            'name' => 'response_time',
            'value' => $duration,
            'unit' => 'ms',
        ]];

        if ($duration <= $good) {
            return new CategoryAnalysis('performance', (string) config('audit.labels.performance'), [
                AuditCheck::pass('performance.response_time'),
            ], $metrics);
        }

        $severity = $duration <= $poor ? FindingSeverity::Medium : FindingSeverity::High;

        return new CategoryAnalysis('performance', (string) config('audit.labels.performance'), [
            AuditCheck::fail(new FindingDraft(
                ruleKey: 'performance.response_time',
                category: 'performance',
                severity: $severity,
                title: 'پاسخ صفحه اصلی کند است',
                description: 'زمان دریافت صفحه اصلی '.$duration.' میلی‌ثانیه اندازه‌گیری شد.',
                businessImpact: 'انتظار طولانی در اولین درخواست، خروج بازدیدکننده را بیشتر می‌کند.',
                recommendation: 'زمان پاسخ سرور و حجم صفحه اصلی را کم کنید.',
                url: $home?->url,
            )),
        ], $metrics);
    }
}
