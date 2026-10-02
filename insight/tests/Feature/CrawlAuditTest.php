<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contracts\Audits\DnsResolver;
use App\Enums\AuditStatus;
use App\Models\Audit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CrawlAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->instance(DnsResolver::class, new class implements DnsResolver
        {
            public function resolve(string $host): array
            {
                return ['93.184.216.34'];
            }
        });
        config(['audit.crawl.delay_ms' => 0]);
    }

    public function test_duplicate_urls_are_fetched_once(): void
    {
        config(['audit.crawl.max_pages' => 10, 'audit.crawl.max_depth' => 2]);
        Http::preventStrayRequests();
        Http::fake(function ($request) {
            $url = $request->url();
            if (str_contains($url, '/robots.txt')) {
                return Http::response("User-agent: *\nAllow: /\nSitemap: https://example.com/sitemap.xml\n", 200, ['Content-Type' => 'text/plain']);
            }
            if (str_contains($url, '/sitemap.xml')) {
                return Http::response('<urlset><url><loc>https://example.com/</loc></url></urlset>', 200, ['Content-Type' => 'application/xml']);
            }
            if (str_contains($url, '/contact')) {
                return Http::response('<html><head><title>تماس</title></head><body><a href="/about">about</a></body></html>', 200, ['Content-Type' => 'text/html']);
            }
            if (str_contains($url, '/about')) {
                return Http::response('<html><head><title>درباره</title></head><body><a href="/contact">contact</a><a href="/">home</a></body></html>', 200, ['Content-Type' => 'text/html']);
            }

            return Http::response('<html><head><title>خانه</title></head><body><a href="/about">a</a><a href="/about">b</a><a href="/contact">c</a></body></html>', 200, ['Content-Type' => 'text/html']);
        });

        $uuid = $this->postJson('/api/audits', ['url' => 'https://example.com'])->json('data.uuid');

        $this->assertSame(AuditStatus::Completed, Audit::query()->where('uuid', $uuid)->first()->status);
        $this->assertSame(3, Audit::query()->where('uuid', $uuid)->first()->pages()->count());
        $this->assertSame(1, $this->requestCount('/contact'));
        $this->assertSame(1, $this->requestCount('/about'));
        $this->assertSame('ok', Audit::query()->where('uuid', $uuid)->first()->crawl_stats['sitemap_status']);
    }

    public function test_crawl_stops_at_the_page_limit(): void
    {
        config(['audit.crawl.max_pages' => 2, 'audit.crawl.max_depth' => 2]);
        Http::preventStrayRequests();
        Http::fake(function ($request) {
            $url = $request->url();
            if (str_contains($url, '/robots.txt') || str_contains($url, '/sitemap.xml')) {
                return Http::response('missing', 404, ['Content-Type' => 'text/plain']);
            }
            if (str_contains($url, '/a')) {
                return Http::response('<html><body><a href="/b">b</a></body></html>', 200, ['Content-Type' => 'text/html']);
            }

            return Http::response('<html><body><a href="/a">a</a><a href="/b">b</a><a href="/c">c</a></body></html>', 200, ['Content-Type' => 'text/html']);
        });

        $uuid = $this->postJson('/api/audits', ['url' => 'https://example.com'])->json('data.uuid');
        $audit = Audit::query()->where('uuid', $uuid)->first();
        $urls = $audit->pages()->pluck('url')->all();

        $this->assertCount(2, $urls);
        $this->assertContains('https://example.com/', $urls);
        $this->assertContains('https://example.com/a', $urls);
        $this->assertNotContains('https://example.com/c', $urls);
        $this->assertTrue($audit->crawl_stats['limited']);
    }

    public function test_status_moves_from_queued_to_completed_or_failed(): void
    {
        config(['audit.crawl.max_pages' => 1]);
        Http::fake(function ($request) {
            if (str_contains($request->url(), '/robots.txt') || str_contains($request->url(), '/sitemap.xml')) {
                return Http::response('', 404);
            }

            return Http::response('<html><body>ok</body></html>', 200, ['Content-Type' => 'text/html']);
        });

        $seen = [];
        Audit::updated(function (Audit $audit) use (&$seen): void {
            $seen[] = $audit->status->value;
        });

        $created = $this->postJson('/api/audits', ['url' => 'https://example.com']);
        $created->assertCreated()->assertJsonPath('data.status', 'queued');
        $this->assertSame(['crawling', 'completed'], $seen);
        $this->assertSame(AuditStatus::Completed, Audit::query()->where('uuid', $created->json('data.uuid'))->first()->status);

        $seen = [];
        Http::fake(function (): void {
            throw new ConnectionException('down');
        });

        $failed = $this->postJson('/api/audits', ['url' => 'https://example.com/down']);
        $failed->assertCreated();
        $this->assertSame(['crawling', 'failed'], $seen);
        $this->assertSame(AuditStatus::Failed, Audit::query()->where('uuid', $failed->json('data.uuid'))->first()->status);
    }

    private function requestCount(string $fragment): int
    {
        return Http::recorded()->filter(function (array $pair) use ($fragment): bool {
            return str_contains($pair[0]->url(), $fragment);
        })->count();
    }
}
