<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contracts\Audits\CertificateInspector;
use App\Contracts\Audits\DnsResolver;
use App\Contracts\Audits\PageSpeedClient;
use App\Services\Audits\Data\CertificateReport;
use App\Services\Audits\Data\PageSpeedReport;
use App\Services\Audits\Data\StrategyMetrics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RunAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_completes_with_findings_and_scores(): void
    {
        $this->app->instance(DnsResolver::class, new class implements DnsResolver
        {
            public function resolve(string $host): array
            {
                return ['93.184.216.34'];
            }
        });
        $this->app->instance(CertificateInspector::class, new class implements CertificateInspector
        {
            public function inspect(string $url): CertificateReport
            {
                return CertificateReport::valid(time() + 86_400);
            }
        });
        $this->app->instance(PageSpeedClient::class, new class implements PageSpeedClient
        {
            public function analyze(string $url): PageSpeedReport
            {
                $mobile = new StrategyMetrics(40, 5000, 100, 0.05, 1000, 200, 2000, 100, 1, 1);
                $desktop = new StrategyMetrics(80, 2000, 100, 0.05, 1000, 200, 2000, 100, 1, 1);

                return new PageSpeedReport(true, $mobile, $desktop);
            }
        });

        Http::preventStrayRequests();
        Http::fake(function ($request) {
            $url = $request->url();
            if (str_contains($url, '/robots.txt')) {
                return Http::response("User-agent: *\nAllow: /\nSitemap: https://example.com/sitemap.xml\n", 200, ['Content-Type' => 'text/plain']);
            }
            if (str_contains($url, '/sitemap.xml')) {
                return Http::response(<<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<urlset>
  <url><loc>https://example.com/</loc></url>
  <url><loc>https://example.com/about</loc></url>
</urlset>
XML, 200, ['Content-Type' => 'application/xml']);
            }
            if (str_contains($url, '/services')) {
                return Http::response('missing', 404, ['Content-Type' => 'text/plain']);
            }
            if (str_contains($url, '/about')) {
                return Http::response(<<<'HTML'
<!doctype html><html><head>
<title>درباره سایت‌دوز برای خدمات طراحی</title>
<meta name="description" content="این یک توضیحات متای کافی برای صفحه درباره است تا طول آن در بازه مناسب قرار بگیرد و کامل باشد.">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="canonical" href="https://example.com/about">
</head><body><h1>درباره</h1><h2>تیم</h2><a href="/">خانه</a><p>متن درباره ما</p></body></html>
HTML, 200, ['Content-Type' => 'text/html']);
            }

            return Http::response(<<<'HTML'
<!doctype html><html lang="fa"><head><meta charset="utf-8"><title>کوتاه</title>
<link rel="canonical" href="https://example.com/"></head><body>
<h1>صفحه اصلی</h1><h3>پرش</h3>
<img src="http://cdn.example.com/a.jpg">
<img src="https://example.com/b.jpg" alt="نمونه">
<a href="/services">خدمات</a>
<a href="/about">درباره</a>
<a href="https://wa.me/989120000000">واتساپ</a>
<a href="tel:02112345678">تماس</a>
<form><input name="name"><button>مشاوره</button></form>
<p>متن کوتاه</p>
</body></html>
HTML, 200, ['Content-Type' => 'text/html']);
        });

        $created = $this->postJson('/api/audits', ['url' => 'https://example.com']);
        $created->assertCreated();
        $uuid = $created->json('data.uuid');

        $report = $this->getJson('/api/audits/'.$uuid);
        $report->assertOk()
            ->assertJsonPath('data.status', 'completed')
            ->assertJsonPath('data.lead.lead_source', 'sitedooz_insight');

        $this->assertNotNull($report->json('data.overall_score'));
        $this->assertGreaterThanOrEqual(0, $report->json('data.overall_score'));
        $this->assertLessThanOrEqual(100, $report->json('data.overall_score'));

        $finding = collect($report->json('data.findings_by_category.on_page_seo'))
            ->firstWhere('rule_key', 'missing_meta_description');
        $this->assertNotNull($finding);
        $this->assertSame('توضیحات متا برای این صفحه وجود ندارد.', $finding['title']);
        $this->assertSame('این موضوع می‌تواند روی نحوه نمایش صفحه در نتایج جستجو تأثیر بگذارد.', $finding['business_impact']);
        $this->assertSame('برای این صفحه یک توضیحات متای مرتبط و جذاب بنویسید.', $finding['recommendation']);

        $keys = collect($report->json('data.findings_by_category'))
            ->flatten(1)
            ->pluck('rule_key');
        $this->assertFalse($keys->contains('https_enabled'));
        $this->assertTrue($keys->contains('broken_internal_links'));
        $this->assertTrue($keys->contains('lcp'));

        $this->getJson('/api/audits/'.$uuid.'/summary')->assertOk()->assertJsonPath('data.status', 'completed');
        $this->getJson('/api/audits/'.$uuid.'/findings')->assertOk()->assertJsonFragment(['rule_key' => 'missing_meta_description']);
        $this->getJson('/api/audits/'.$uuid.'/pages')->assertOk()->assertJsonFragment(['status_code' => 404]);
    }
}
