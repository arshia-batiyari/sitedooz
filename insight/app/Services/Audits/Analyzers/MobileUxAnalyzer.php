<?php

declare(strict_types=1);

namespace App\Services\Audits\Analyzers;

use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\PageSpeedReport;
use App\Services\Audits\Data\Signal;

final class MobileUxAnalyzer
{
    /**
     * @return list<Signal>
     */
    public function analyze(CrawlResult $crawl, PageSpeedReport $report): array
    {
        $signals = [];
        $missingViewport = [];
        foreach ($crawl->htmlPages() as $page) {
            if (! $page->hasViewport) {
                $missingViewport[] = Signal::fail('viewport', 'high', $page->finalUrl);
            }
        }
        if ($crawl->htmlPages() === []) {
            $signals[] = Signal::notApplicable('viewport');
        } else {
            $signals[] = $missingViewport === [] ? Signal::pass('viewport') : $missingViewport[0];
            if (count($missingViewport) > 1) {
                $signals = array_merge($signals, array_slice($missingViewport, 1));
            }
        }

        $signals[] = Signal::review('horizontal_overflow', null, 'سرریز افقی فقط با مشاهده صفحه قابل تشخیص است.');

        $mobile = $report->mobile;
        $signals[] = $this->auditScore('text_readability', $mobile?->fontSizeScore);
        $signals[] = $this->auditScore('tap_targets', $mobile?->tapTargetsScore);
        $signals[] = $this->mobilePerformance($mobile?->performanceScore);

        return $signals;
    }

    private function auditScore(string $key, ?float $score): Signal
    {
        if ($score === null) {
            return Signal::review($key, null, 'این مورد در داده سرعت وجود نداشت و نیاز به بررسی انسانی دارد.');
        }

        return $score >= 1
            ? Signal::pass($key, metadata: ['score' => $score])
            : Signal::fail($key, 'medium', metadata: ['score' => $score]);
    }

    private function mobilePerformance(?float $score): Signal
    {
        if ($score === null) {
            return Signal::notApplicable('mobile_performance');
        }
        if ($score >= 90) {
            return Signal::pass('mobile_performance', metadata: ['value' => $score]);
        }

        return Signal::fail(
            'mobile_performance',
            $score >= 50 ? 'medium' : 'high',
            metadata: ['value' => $score],
        );
    }
}
