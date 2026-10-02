<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Audits\HttpPageSpeedClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HttpPageSpeedClientTest extends TestCase
{
    public function test_missing_key_marks_metrics_unavailable(): void
    {
        config(['services.pagespeed.key' => '']);

        $report = (new HttpPageSpeedClient)->analyze('https://example.com/');

        $this->assertFalse($report->available);
    }

    public function test_it_reads_lighthouse_numeric_values(): void
    {
        config(['services.pagespeed.key' => 'test-key']);
        Http::fake([
            '*' => Http::response([
                'lighthouseResult' => [
                    'categories' => ['performance' => ['score' => 0.4]],
                    'audits' => [
                        'largest-contentful-paint' => ['numericValue' => 5000],
                        'interaction-to-next-paint' => ['numericValue' => 180],
                        'cumulative-layout-shift' => ['numericValue' => 0.05],
                        'first-contentful-paint' => ['numericValue' => 1000],
                        'server-response-time' => ['numericValue' => 200],
                        'speed-index' => ['numericValue' => 2000],
                        'total-blocking-time' => ['numericValue' => 100],
                        'font-size' => ['score' => 1],
                        'tap-targets' => ['score' => 0.5],
                    ],
                ],
            ]),
        ]);

        $report = (new HttpPageSpeedClient)->analyze('https://example.com/');

        $this->assertTrue($report->available);
        $this->assertNotNull($report->mobile);
        $this->assertSame(40.0, $report->mobile->performanceScore);
        $this->assertSame(5000.0, $report->mobile->lcp);
        $this->assertSame(0.5, $report->mobile->tapTargetsScore);
    }
}
