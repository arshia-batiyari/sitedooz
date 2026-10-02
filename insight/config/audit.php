<?php

declare(strict_types=1);

return [
    'weights' => [
        'technical_seo' => 20,
        'performance' => 20,
        'on_page_seo' => 15,
        'mobile_ux' => 15,
        'security' => 10,
        'conversion' => 10,
        'search_visibility' => 10,
    ],

    'labels' => [
        'technical_seo' => 'سئو فنی',
        'performance' => 'سرعت و عملکرد',
        'on_page_seo' => 'سئو داخل صفحه',
        'mobile_ux' => 'موبایل و تجربه کاربری',
        'security' => 'امنیت',
        'conversion' => 'تبدیل بازدیدکننده',
        'search_visibility' => 'دیده‌شدن در جستجو',
    ],

    'crawl' => [
        'max_pages' => (int) env('AUDIT_MAX_PAGES', 25),
        'max_depth' => (int) env('AUDIT_MAX_DEPTH', 2),
        'timeout_seconds' => (int) env('AUDIT_TIMEOUT_SECONDS', 10),
        'deadline_seconds' => (int) env('AUDIT_DEADLINE_SECONDS', 60),
        'max_response_bytes' => (int) env('AUDIT_MAX_RESPONSE_BYTES', 1_500_000),
        'max_redirects' => 5,
        'delay_ms' => (int) env('AUDIT_CRAWL_DELAY_MS', 200),
        'user_agent' => 'SitedoozInsightBot/1.0 (+https://sitedooz.ir)',
    ],
];
