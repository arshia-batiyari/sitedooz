<?php

declare(strict_types=1);

namespace App\Services\Audits\Analyzers;

use App\Services\Audits\Data\PageSpeedReport;
use App\Services\Audits\Data\Signal;

final class PerformanceAnalyzer
{
    /**
     * @return list<Signal>
     */
    public function analyze(PageSpeedReport $report): array
    {
        $keys = [
            'performance_score',
            'lcp',
            'inp',
            'cls',
            'fcp',
            'ttfb',
            'speed_index',
            'total_blocking_time',
        ];

        if (! $report->available || $report->mobile === null) {
            $signals = [Signal::review(
                'performance_needs_review',
                null,
                'داده آزمایشگاهی سرعت در دسترس نیست و این بخش نیاز به بررسی انسانی دارد.',
            )];
            foreach ($keys as $key) {
                $signals[] = Signal::notApplicable($key);
            }

            return $signals;
        }

        $mobile = $report->mobile;

        return [
            Signal::notApplicable('performance_needs_review'),
            $this->metric('performance_score', $mobile->performanceScore, function (?float $value): ?string {
                if ($value === null) {
                    return null;
                }
                if ($value >= 90) {
                    return null;
                }

                return $value >= 50 ? 'medium' : 'high';
            }),
            $this->metric('lcp', $mobile->lcp, fn (?float $value): ?string => $this->band($value, 2500, 4000)),
            $this->metric('inp', $mobile->inp, fn (?float $value): ?string => $this->band($value, 200, 500)),
            $this->metric('cls', $mobile->cls, fn (?float $value): ?string => $this->band($value, 0.1, 0.25)),
            $this->metric('fcp', $mobile->fcp, fn (?float $value): ?string => $this->band($value, 1800, 3000)),
            $this->metric('ttfb', $mobile->ttfb, fn (?float $value): ?string => $this->band($value, 800, 1800)),
            $this->metric('speed_index', $mobile->speedIndex, fn (?float $value): ?string => $value === null || $value <= 3400 ? null : 'medium'),
            $this->metric('total_blocking_time', $mobile->tbt, fn (?float $value): ?string => $this->band($value, 200, 600)),
        ];
    }

    private function band(?float $value, float $good, float $poor): ?string
    {
        if ($value === null || $value <= $good) {
            return null;
        }

        return $value <= $poor ? 'medium' : 'high';
    }

    /**
     * @param  callable(?float): ?string  $severity
     */
    private function metric(string $key, ?float $value, callable $severity): Signal
    {
        if ($value === null) {
            return Signal::notApplicable($key);
        }

        $level = $severity($value);
        if ($level === null) {
            return Signal::pass($key, metadata: ['value' => $value]);
        }

        return Signal::fail($key, $level, metadata: ['value' => $value]);
    }
}
