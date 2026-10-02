<?php

declare(strict_types=1);

namespace App\Services\Audits\Analyzers;

use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\Signal;

final class SearchVisibilityAnalyzer
{
    /**
     * @return list<Signal>
     */
    public function analyze(CrawlResult $crawl): array
    {
        $home = $crawl->homepage();
        $robots = strtolower($home?->metaRobots ?? '');
        $blocked = $crawl->homepageBlocked
            || str_contains($robots, 'noindex')
            || str_contains($robots, 'none');

        $signals = [
            $blocked
                ? Signal::fail('indexability', 'critical', $home?->finalUrl)
                : Signal::pass('indexability', $home?->finalUrl),
        ];

        if ($crawl->sitemapStatus !== 'ok' || $crawl->sitemapUrls === []) {
            $signals[] = Signal::notApplicable('sitemap_coverage');
        } else {
            $listed = array_fill_keys($crawl->sitemapUrls, true);
            $html = $crawl->htmlPages();
            $covered = 0;
            foreach ($html as $page) {
                if (isset($listed[$page->finalUrl]) || isset($listed[$page->url])) {
                    $covered++;
                }
            }
            $ratio = $html === [] ? 1 : $covered / count($html);
            $signals[] = $ratio < 0.5
                ? Signal::fail('sitemap_coverage', 'medium', metadata: ['ratio' => round($ratio, 2)])
                : Signal::pass('sitemap_coverage', metadata: ['ratio' => round($ratio, 2)]);
        }

        $mismatch = [];
        foreach ($crawl->htmlPages() as $page) {
            if ($page->canonical === null) {
                continue;
            }
            $canonicalHost = strtolower((string) parse_url($page->canonical, PHP_URL_HOST));
            $pageHost = strtolower((string) parse_url($page->finalUrl, PHP_URL_HOST));
            if ($canonicalHost !== '' && $canonicalHost !== $pageHost) {
                $mismatch[] = Signal::fail('canonical_consistency', 'high', $page->finalUrl, [
                    'canonical' => $page->canonical,
                ]);
            }
        }
        $signals[] = $mismatch === [] ? Signal::pass('canonical_consistency') : $mismatch[0];
        if (count($mismatch) > 1) {
            $signals = array_merge($signals, array_slice($mismatch, 1));
        }

        return $signals;
    }
}
