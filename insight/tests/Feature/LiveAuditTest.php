<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contracts\Audits\DnsResolver;
use App\Enums\AuditStatus;
use App\Events\Audits\AnalyzerCompleted;
use App\Events\Audits\AnalyzerStarted;
use App\Events\Audits\AuditCompleted;
use App\Events\Audits\AuditFailed;
use App\Events\Audits\AuditStarted;
use App\Events\Audits\CrawlerStarted;
use App\Events\Audits\FindingDetected;
use App\Events\Audits\MetricCalculated;
use App\Events\Audits\PageCrawled;
use App\Events\Audits\ScoreUpdated;
use App\Models\Audit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LiveAuditTest extends TestCase
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
        config([
            'audit.crawl.delay_ms' => 0,
            'audit.crawl.max_pages' => 5,
            'audit.performance.good_ms' => 60000,
        ]);
    }

    public function test_live_page_starts_after_create_and_streams_real_events(): void
    {
        Http::preventStrayRequests();
        Http::fake(function ($request) {
            $url = $request->url();
            if (str_contains($url, '/robots.txt')) {
                return Http::response("User-agent: *\nAllow: /\n", 200, ['Content-Type' => 'text/plain']);
            }
            if (str_contains($url, '/sitemap.xml')) {
                return Http::response('missing', 404, ['Content-Type' => 'text/plain']);
            }
            if (str_contains($url, '/gone')) {
                return Http::response('missing', 404, ['Content-Type' => 'text/html']);
            }

            return Http::response('<html><head><title>خانه</title></head><body><a href="/gone">gone</a></body></html>', 200, ['Content-Type' => 'text/html']);
        });

        $created = $this->post('/audits', ['url' => 'https://example.com']);
        $created->assertRedirect();
        $uuid = Audit::query()->first()->uuid;
        $this->assertSame(AuditStatus::Queued, Audit::query()->first()->status);

        $waiting = $this->get('/audits/'.$uuid);
        $waiting->assertOk()->assertSee('امتیاز سایت‌دوز')->assertSee('panel failure is-hidden', false);

        Event::fake();
        $this->postJson('/audits/'.$uuid.'/start')->assertOk();

        $audit = Audit::query()->where('uuid', $uuid)->first();
        $this->assertSame(AuditStatus::Completed, $audit->status);
        $this->assertNotNull($audit->overall_score);
        $this->assertArrayHasKey('technical', $audit->category_scores);
        $this->assertArrayHasKey('seo', $audit->category_scores);

        Event::assertDispatched(AuditStarted::class);
        Event::assertDispatched(CrawlerStarted::class);
        Event::assertDispatched(PageCrawled::class, function (PageCrawled $event) use ($uuid): bool {
            $payload = $event->broadcastWith();

            return $payload['page_number'] >= 1
                && isset($payload['url'], $payload['pages_found'])
                && $event->broadcastOn()[0]->name === 'audit.'.$uuid;
        });
        Event::assertDispatched(MetricCalculated::class, fn (MetricCalculated $event): bool => $event->broadcastWith()['name'] === 'robots_txt');
        Event::assertDispatched(FindingDetected::class, function (FindingDetected $event): bool {
            $payload = $event->broadcastWith();

            return $payload['title'] === 'توضیح متا وجود ندارد'
                && $payload['severity'] === 'high'
                && $payload['category'] === 'seo'
                && ! array_key_exists('trace', $payload);
        });
        Event::assertDispatched(FindingDetected::class, fn (FindingDetected $event): bool => str_contains($event->broadcastWith()['title'], 'لینک شکسته'));
        Event::assertNotDispatched(FindingDetected::class, fn (FindingDetected $event): bool => str_contains($event->broadcastWith()['title'], 'کند'));
        Event::assertDispatched(AnalyzerStarted::class);
        Event::assertDispatched(AnalyzerCompleted::class);
        Event::assertDispatched(ScoreUpdated::class, function (ScoreUpdated $event): bool {
            $payload = $event->broadcastWith();

            return array_key_exists('technical', $payload['categories'])
                && array_key_exists('seo', $payload['categories']);
        });
        Event::assertDispatched(AuditCompleted::class, function (AuditCompleted $event): bool {
            $payload = $event->broadcastWith();
            $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE);

            return $payload['progress'] === 100
                && $payload['top_issues'] !== []
                && ! str_contains((string) $encoded, 'ConnectionException');
        });
        Event::assertNotDispatched(AuditFailed::class);

        $this->get('/audits/'.$uuid)
            ->assertOk()
            ->assertSee('گزارش نهایی')
            ->assertSee('توضیح متا وجود ندارد')
            ->assertSee('panel failure is-hidden', false);
    }

    public function test_failure_is_visible_without_internal_exception_text(): void
    {
        Http::fake(function (): void {
            throw new ConnectionException('down');
        });

        $this->post('/audits', ['url' => 'https://example.com/offline'])->assertRedirect();
        $uuid = Audit::query()->first()->uuid;

        Event::fake();
        $this->postJson('/audits/'.$uuid.'/start')->assertOk();
        Event::assertDispatched(AuditFailed::class, function (AuditFailed $event) use ($uuid): bool {
            $payload = $event->broadcastWith();
            $encoded = json_encode($payload, JSON_UNESCAPED_UNICODE);

            return $event->broadcastOn()[0]->name === 'audit.'.$uuid
                && $payload['reason'] === 'ارتباط با سایت برقرار نشد.'
                && ! str_contains((string) $encoded, 'ConnectionException')
                && ! str_contains((string) $encoded, 'down');
        });
        Event::assertNotDispatched(AuditCompleted::class);

        $page = $this->get('/audits/'.$uuid);
        $page->assertOk()
            ->assertSee('تحلیل سایت متوقف شد')
            ->assertSee('ارتباط با سایت برقرار نشد.')
            ->assertSee('تلاش مجدد')
            ->assertDontSee('ConnectionException')
            ->assertDontSee('down');
        $this->assertStringNotContainsString('failure is-hidden', $page->getContent());

        $this->post('/audits/'.$uuid.'/retry')->assertRedirect('/audits/'.$uuid);
        $this->assertSame(AuditStatus::Queued, Audit::query()->where('uuid', $uuid)->first()->status);
        $this->assertSame(0, Audit::query()->where('uuid', $uuid)->first()->pages()->count());
    }
}
