<?php

declare(strict_types=1);

return [
    'weights' => [
        'technical' => 40,
        'seo' => 35,
        'performance' => 25,
    ],

    'labels' => [
        'technical' => 'فنی',
        'seo' => 'سئو',
        'performance' => 'سرعت',
    ],

    'performance' => [
        'good_ms' => (int) env('AUDIT_RESPONSE_GOOD_MS', 800),
        'poor_ms' => (int) env('AUDIT_RESPONSE_POOR_MS', 1800),
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
