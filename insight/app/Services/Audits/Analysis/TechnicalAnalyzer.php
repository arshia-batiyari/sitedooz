<?php

declare(strict_types=1);

namespace App\Services\Audits\Analysis;

use App\Enums\FindingSeverity;
use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\PageSnapshot;

final class TechnicalAnalyzer
{
    public function analyze(CrawlResult $crawl): CategoryAnalysis
    {
        $home = $crawl->homepage();
        $checks = [];

        if ($this->fetched($home)) {
            $https = str_starts_with(strtolower($home->finalUrl), 'https://');
            $checks[] = $https
                ? AuditCheck::pass('technical.https')
                : AuditCheck::fail(new FindingDraft(
                    ruleKey: 'technical.https',
                    category: 'technical',
                    severity: FindingSeverity::High,
                    title: 'سایت از HTTPS استفاده نمی‌کند',
                    description: 'صفحه اصلی روی HTTP باز شد.',
                    businessImpact: 'مرورگر سایت را ناامن نشان می‌دهد و اطلاعات فرم‌ها در مسیر انتقال محافظت نمی‌شود.',
                    recommendation: 'گواهی SSL نصب کنید و تمام نشانی‌ها را به HTTPS منتقل کنید.',
                    url: $home->finalUrl,
                ));
        }

        if (($crawl->stats['robots_checked'] ?? false) === true) {
            $checks[] = $crawl->robotsFound
                ? AuditCheck::pass('technical.robots')
                : AuditCheck::fail(new FindingDraft(
                    ruleKey: 'technical.robots',
                    category: 'technical',
                    severity: FindingSeverity::Medium,
                    title: 'فایل robots.txt پیدا نشد',
                    description: 'درخواست robots.txt پاسخ معتبری نداشت.',
                    businessImpact: 'موتورهای جستجو بدون راهنمای خزش، مسیرهای سایت را خودشان حدس می‌زنند.',
                    recommendation: 'فایل robots.txt را در ریشه سایت منتشر کنید.',
                    url: $this->origin($home).'/robots.txt',
                ));
        }

        if ($crawl->sitemapStatus !== 'unchecked') {
            $found = $crawl->sitemapStatus === 'ok' && $crawl->sitemapUrls !== [];
            $checks[] = $found
                ? AuditCheck::pass('technical.sitemap')
                : AuditCheck::fail(new FindingDraft(
                    ruleKey: 'technical.sitemap',
                    category: 'technical',
                    severity: FindingSeverity::Low,
                    title: 'نقشه سایت پیدا نشد',
                    description: 'sitemap معتبری برای این دامنه پیدا نشد.',
                    businessImpact: 'صفحه‌های تازه ممکن است دیرتر کشف شوند.',
                    recommendation: 'sitemap.xml بسازید و آن را در robots.txt معرفی کنید.',
                    url: $this->origin($home).'/sitemap.xml',
                ));
        }

        if ($home !== null) {
            $checks[] = $this->homepageStatus($home);
        }

        $broken = array_values(array_filter(
            $crawl->pages,
            fn (PageSnapshot $page): bool => $page->depth > 0 && $page->statusCode >= 400,
        ));
        if ($broken === []) {
            $checks[] = AuditCheck::pass('technical.broken_links');
        } else {
            $count = count($broken);
            $checks[] = AuditCheck::fail(new FindingDraft(
                ruleKey: 'technical.broken_links',
                category: 'technical',
                severity: FindingSeverity::High,
                title: $count === 1 ? 'لینک شکسته شناسایی شد' : $count.' لینک شکسته شناسایی شد',
                description: 'دست‌کم یک نشانی داخلی پاسخ ۴۰۰ یا بالاتر داد.',
                businessImpact: 'بازدیدکننده و موتور جستجو به بن‌بست می‌رسند.',
                recommendation: 'نشانی‌های داخلی خراب را اصلاح یا حذف کنید.',
                url: $broken[0]->url,
            ));
        }

        return new CategoryAnalysis('technical', (string) config('audit.labels.technical'), $checks);
    }

    private function homepageStatus(PageSnapshot $home): AuditCheck
    {
        if ($home->blockedByRobots) {
            return AuditCheck::fail(new FindingDraft(
                ruleKey: 'technical.homepage_status',
                category: 'technical',
                severity: FindingSeverity::High,
                title: 'صفحه اصلی در robots.txt بسته شده',
                description: 'خزنده اجازه دریافت صفحه اصلی را نداشت.',
                businessImpact: 'اگر این قانون برای موتورهای جستجو هم باشد، صفحه اصلی ایندکس نمی‌شود.',
                recommendation: 'قانون Disallow صفحه اصلی را در robots.txt بردارید.',
                url: $home->url,
            ));
        }

        if ($home->statusCode === 200 && $home->error === null) {
            return AuditCheck::pass('technical.homepage_status');
        }

        $severity = $home->statusCode >= 500 || $home->statusCode === 0
            ? FindingSeverity::Critical
            : FindingSeverity::High;

        return AuditCheck::fail(new FindingDraft(
            ruleKey: 'technical.homepage_status',
            category: 'technical',
            severity: $severity,
            title: 'صفحه اصلی پاسخ درستی ندارد',
            description: 'وضعیت دریافت صفحه اصلی موفق نبود.',
            businessImpact: 'کاربر در اولین ورود با خطا روبه‌رو می‌شود.',
            recommendation: 'پاسخ ۲۰۰ صفحه اصلی را برگردانید و خطای سرور را برطرف کنید.',
            url: $home->url,
        ));
    }

    private function fetched(?PageSnapshot $home): bool
    {
        return $home !== null
            && ! $home->blockedByRobots
            && $home->error === null
            && $home->statusCode > 0;
    }

    private function origin(?PageSnapshot $home): string
    {
        $url = $home?->finalUrl ?? 'https://example.com/';
        $scheme = parse_url($url, PHP_URL_SCHEME) ?: 'https';
        $host = parse_url($url, PHP_URL_HOST) ?: '';

        return $scheme.'://'.$host;
    }
}
