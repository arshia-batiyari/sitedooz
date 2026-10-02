<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Contracts\Audits\DnsResolver;
use App\Services\Audits\Analyzers\OnPageSeoAnalyzer;
use App\Services\Audits\Analyzers\TechnicalSeoAnalyzer;
use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\PageSnapshot;
use App\Services\Audits\Data\Signal;
use App\Services\Audits\HtmlSnapshotParser;
use App\Services\Audits\UrlSafety;
use PHPUnit\Framework\TestCase;

class OnPageAndHttpsAnalyzerTest extends TestCase
{
    public function test_missing_meta_description_is_a_medium_finding_signal(): void
    {
        $safety = new UrlSafety(new class implements DnsResolver
        {
            public function resolve(string $host): array
            {
                return ['93.184.216.34'];
            }
        });
        $page = (new HtmlSnapshotParser($safety))->parse(
            '<html><head><title>عنوان نمونه برای صفحه اصلی سایت</title></head><body><h1>سلام</h1></body></html>',
            'https://example.com/',
            'https://example.com/',
            200,
            [],
            [],
            0,
        );

        $signal = $this->signal(
            (new OnPageSeoAnalyzer)->analyze(new CrawlResult([$page], true, 'ok', [], false, [])),
            'missing_meta_description',
        );

        $this->assertNotNull($signal);
        $this->assertFalse($signal->passed);
        $this->assertSame('medium', $signal->severity);
        $this->assertSame('https://example.com/', $signal->pageUrl);
    }

    public function test_http_homepage_fails_https_rule(): void
    {
        $page = PageSnapshot::make([
            'url' => 'http://example.com/',
            'finalUrl' => 'http://example.com/',
        ]);
        $signal = $this->signal(
            (new TechnicalSeoAnalyzer)->analyze(new CrawlResult([$page], true, 'ok', [], false, [])),
            'https_enabled',
        );

        $this->assertNotNull($signal);
        $this->assertFalse($signal->passed);
        $this->assertSame('critical', $signal->severity);
    }

    public function test_https_homepage_passes(): void
    {
        $signal = $this->signal(
            (new TechnicalSeoAnalyzer)->analyze(new CrawlResult([PageSnapshot::make()], true, 'ok', [], false, [])),
            'https_enabled',
        );

        $this->assertNotNull($signal);
        $this->assertTrue($signal->passed);
    }

    /**
     * @param  list<Signal>  $signals
     */
    private function signal(array $signals, string $key): ?Signal
    {
        foreach ($signals as $signal) {
            if ($signal->ruleKey === $key && ! $signal->passed) {
                return $signal;
            }
        }

        foreach ($signals as $signal) {
            if ($signal->ruleKey === $key) {
                return $signal;
            }
        }

        return null;
    }
}
