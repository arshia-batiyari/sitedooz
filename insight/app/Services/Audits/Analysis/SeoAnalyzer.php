<?php

declare(strict_types=1);

namespace App\Services\Audits\Analysis;

use App\Enums\FindingSeverity;
use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\PageSnapshot;

final class SeoAnalyzer
{
    public function analyze(CrawlResult $crawl): ?CategoryAnalysis
    {
        $pages = $crawl->htmlPages();
        if ($pages === []) {
            return null;
        }

        $checks = [
            $this->textCheck($pages, 'seo.title', 'title', FindingSeverity::High, 'عنوان صفحه وجود ندارد', 'برای هر صفحه یک عنوان یکتا بنویسید.'),
            $this->textCheck($pages, 'seo.meta_description', 'metaDescription', FindingSeverity::High, 'توضیح متا وجود ندارد', 'برای هر صفحه مهم یک توضیح متای مشخص بنویسید.'),
            $this->h1Check($pages),
            $this->textCheck($pages, 'seo.canonical', 'canonical', FindingSeverity::Low, 'نشانی canonical تعیین نشده', 'نشانی canonical صفحه را مشخص کنید.'),
        ];

        return new CategoryAnalysis('seo', (string) config('audit.labels.seo'), $checks);
    }

    /**
     * @param  list<PageSnapshot>  $pages
     */
    private function textCheck(array $pages, string $key, string $field, FindingSeverity $severity, string $title, string $recommendation): AuditCheck
    {
        foreach ($pages as $page) {
            $value = $page->{$field};
            if (! is_string($value) || trim($value) === '') {
                return AuditCheck::fail(new FindingDraft(
                    ruleKey: $key,
                    category: 'seo',
                    severity: $severity,
                    title: $title,
                    description: 'این مورد در دست‌کم یک صفحه HTML با پاسخ ۲۰۰ خالی بود.',
                    businessImpact: 'صفحه در نتایج جستجو ضعیف‌تر فهمیده و نمایش داده می‌شود.',
                    recommendation: $recommendation,
                    url: $page->url,
                ));
            }
        }

        return AuditCheck::pass($key);
    }

    /**
     * @param  list<PageSnapshot>  $pages
     */
    private function h1Check(array $pages): AuditCheck
    {
        foreach ($pages as $page) {
            $present = array_filter($page->h1, fn (string $heading): bool => trim($heading) !== '');
            if ($present === []) {
                return AuditCheck::fail(new FindingDraft(
                    ruleKey: 'seo.h1',
                    category: 'seo',
                    severity: FindingSeverity::Medium,
                    title: 'تگ H1 وجود ندارد',
                    description: 'دست‌کم یک صفحه HTML عنوان سطح یک ندارد.',
                    businessImpact: 'ساختار صفحه برای کاربر و موتور جستجو مبهم می‌شود.',
                    recommendation: 'در هر صفحه یک عنوان H1 روشن قرار دهید.',
                    url: $page->url,
                ));
            }
        }

        return AuditCheck::pass('seo.h1');
    }
}
