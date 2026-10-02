<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Contracts\Audits\PageSpeedClient;
use App\Services\Audits\Data\PageSpeedReport;
use App\Services\Audits\Data\StrategyMetrics;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

final class HttpPageSpeedClient implements PageSpeedClient
{
    public function analyze(string $url): PageSpeedReport
    {
        $key = (string) config('services.pagespeed.key');
        if ($key === '') {
            return PageSpeedReport::unavailable('کلید PageSpeed تنظیم نشده است.');
        }

        $mobile = $this->strategy($url, 'mobile', $key);
        $desktop = $this->strategy($url, 'desktop', $key);
        if ($mobile === null && $desktop === null) {
            return PageSpeedReport::unavailable('پاسخ PageSpeed قابل استفاده نبود.');
        }

        return new PageSpeedReport(true, $mobile, $desktop);
    }

    private function strategy(string $url, string $strategy, string $key): ?StrategyMetrics
    {
        try {
            $response = Http::timeout((int) config('audit.pagespeed.timeout_seconds'))
                ->get((string) config('audit.pagespeed.endpoint'), [
                    'url' => $url,
                    'strategy' => $strategy,
                    'category' => 'performance',
                    'key' => $key,
                ]);
        } catch (Throwable $exception) {
            Log::warning('pagespeed.unavailable', ['strategy' => $strategy, 'message' => $exception->getMessage()]);

            return null;
        }

        if (! $response->successful()) {
            Log::warning('pagespeed.unavailable', ['strategy' => $strategy, 'status' => $response->status()]);

            return null;
        }

        $audits = $response->json('lighthouseResult.audits');
        if (! is_array($audits)) {
            return null;
        }

        $score = $response->json('lighthouseResult.categories.performance.score');

        return new StrategyMetrics(
            performanceScore: is_numeric($score) ? round(((float) $score) * 100, 2) : null,
            lcp: $this->number($audits, 'largest-contentful-paint'),
            inp: $this->number($audits, 'interaction-to-next-paint')
                ?? $this->number($audits, 'experimental-interaction-to-next-paint'),
            cls: $this->number($audits, 'cumulative-layout-shift'),
            fcp: $this->number($audits, 'first-contentful-paint'),
            ttfb: $this->number($audits, 'server-response-time'),
            speedIndex: $this->number($audits, 'speed-index'),
            tbt: $this->number($audits, 'total-blocking-time'),
            fontSizeScore: $this->score($audits, 'font-size'),
            tapTargetsScore: $this->score($audits, 'tap-targets'),
        );
    }

    /**
     * @param  array<string, mixed>  $audits
     */
    private function number(array $audits, string $key): ?float
    {
        $value = $audits[$key]['numericValue'] ?? null;

        return is_numeric($value) ? (float) $value : null;
    }

    /**
     * @param  array<string, mixed>  $audits
     */
    private function score(array $audits, string $key): ?float
    {
        $value = $audits[$key]['score'] ?? null;

        return is_numeric($value) ? (float) $value : null;
    }
}
