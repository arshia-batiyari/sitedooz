<?php

declare(strict_types=1);

namespace App\Services\Audits\Analyzers;

use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\PageSnapshot;
use App\Services\Audits\Data\Signal;

final class TechnicalSeoAnalyzer
{
    /**
     * @return list<Signal>
     */
    public function analyze(CrawlResult $crawl): array
    {
        $home = $crawl->homepage();
        $signals = [
            $this->https($home),
            $this->status($home),
            $this->redirects($home),
            $this->robots($crawl),
            $this->sitemap($crawl),
            $this->structuredData($crawl),
            $this->hreflang($crawl),
            $this->urlStructure($crawl),
            $this->brokenLinks($crawl),
        ];

        return array_merge($signals, $this->canonicals($crawl), $this->robotsDirectives($crawl));
    }

    private function https(?PageSnapshot $home): Signal
    {
        if ($home === null) {
            return Signal::notApplicable('https_enabled');
        }

        return str_starts_with(strtolower($home->finalUrl), 'https://')
            ? Signal::pass('https_enabled', $home->finalUrl)
            : Signal::fail('https_enabled', 'critical', $home->finalUrl);
    }

    private function status(?PageSnapshot $home): Signal
    {
        if ($home === null || $home->blockedByRobots) {
            return Signal::notApplicable('http_status');
        }

        return $home->statusCode === 200
            ? Signal::pass('http_status', $home->finalUrl)
            : Signal::fail('http_status', 'critical', $home->finalUrl, ['status' => $home->statusCode]);
    }

    private function redirects(?PageSnapshot $home): Signal
    {
        if ($home === null) {
            return Signal::notApplicable('redirect_chain');
        }

        $hops = 0;
        foreach ($home->redirectChain as $hop) {
            if ($hop['status'] >= 300 && $hop['status'] < 400) {
                $hops++;
            }
        }

        if ($hops > 3) {
            return Signal::fail('redirect_chain', 'critical', $home->finalUrl, ['hops' => $hops]);
        }
        if ($hops > 1) {
            return Signal::fail('redirect_chain', 'high', $home->finalUrl, ['hops' => $hops]);
        }

        return Signal::pass('redirect_chain', $home->finalUrl, ['hops' => $hops]);
    }

    private function robots(CrawlResult $crawl): Signal
    {
        return $crawl->robotsFound
            ? Signal::pass('robots_txt')
            : Signal::fail('robots_txt', 'low');
    }

    private function sitemap(CrawlResult $crawl): Signal
    {
        return match ($crawl->sitemapStatus) {
            'ok' => Signal::pass('sitemap_xml', metadata: ['count' => count($crawl->sitemapUrls)]),
            'unreadable' => Signal::fail('sitemap_xml', 'high'),
            default => Signal::fail('sitemap_xml', 'medium'),
        };
    }

    /**
     * @return list<Signal>
     */
    private function canonicals(CrawlResult $crawl): array
    {
        $pages = $crawl->htmlPages();
        if ($pages === []) {
            return [Signal::notApplicable('canonical_present')];
        }

        $signals = [];
        foreach ($pages as $page) {
            if ($page->canonical === null) {
                $signals[] = Signal::fail('canonical_present', 'medium', $page->finalUrl);
            }
        }

        return $signals === [] ? [Signal::pass('canonical_present')] : $signals;
    }

    /**
     * @return list<Signal>
     */
    private function robotsDirectives(CrawlResult $crawl): array
    {
        $pages = $crawl->htmlPages();
        if ($pages === []) {
            return [Signal::notApplicable('noindex_directive'), Signal::notApplicable('meta_robots')];
        }

        $noindex = [];
        $nofollow = [];
        foreach ($pages as $page) {
            $robots = strtolower($page->metaRobots ?? '');
            if (str_contains($robots, 'noindex') || str_contains($robots, 'none')) {
                $severity = $page->depth === 0 ? 'critical' : 'high';
                $noindex[] = Signal::fail('noindex_directive', $severity, $page->finalUrl);
            }
            if (str_contains($robots, 'nofollow') && ! str_contains($robots, 'noindex')) {
                $nofollow[] = Signal::fail('meta_robots', 'low', $page->finalUrl);
            }
        }

        return array_merge(
            $noindex === [] ? [Signal::pass('noindex_directive')] : $noindex,
            $nofollow === [] ? [Signal::pass('meta_robots')] : $nofollow,
        );
    }

    private function structuredData(CrawlResult $crawl): Signal
    {
        $pages = $crawl->htmlPages();
        if ($pages === []) {
            return Signal::notApplicable('structured_data');
        }
        foreach ($pages as $page) {
            if ($page->jsonLdTypes !== []) {
                return Signal::pass('structured_data');
            }
        }

        return Signal::fail('structured_data', 'low');
    }

    private function hreflang(CrawlResult $crawl): Signal
    {
        foreach ($crawl->htmlPages() as $page) {
            if ($page->hreflang !== []) {
                return Signal::pass('hreflang');
            }
        }

        return Signal::review('hreflang', null, 'اگر سایت چندزبانه نیست، این مورد می‌تواند بدون تغییر بماند.');
    }

    private function urlStructure(CrawlResult $crawl): Signal
    {
        $ugly = [];
        foreach ($crawl->htmlPages() as $page) {
            $path = (string) parse_url($page->finalUrl, PHP_URL_PATH);
            $query = (string) parse_url($page->finalUrl, PHP_URL_QUERY);
            $params = $query === '' ? 0 : count(explode('&', $query));
            if ($params > 3 || strlen($path) > 120 || preg_match('/[A-Z]/', $path) === 1) {
                $ugly[] = $page->finalUrl;
            }
        }

        return $ugly === []
            ? Signal::pass('url_structure')
            : Signal::fail('url_structure', 'low', metadata: ['urls' => $ugly]);
    }

    private function brokenLinks(CrawlResult $crawl): Signal
    {
        $byUrl = [];
        foreach ($crawl->pages as $page) {
            $byUrl[$page->url] = $page;
            $byUrl[$page->finalUrl] = $page;
        }

        $broken = [];
        $checked = 0;
        foreach ($crawl->htmlPages() as $page) {
            foreach ($page->internalLinks as $link) {
                $target = $byUrl[$link] ?? null;
                if ($target === null || $target->blockedByRobots || $target->error !== null) {
                    continue;
                }
                $checked++;
                if ($target->statusCode >= 400) {
                    $broken[] = [
                        'from' => $page->finalUrl,
                        'to' => $link,
                        'status' => $target->statusCode,
                    ];
                }
            }
        }

        if ($checked === 0) {
            return Signal::notApplicable('broken_internal_links');
        }

        return $broken === []
            ? Signal::pass('broken_internal_links')
            : Signal::fail('broken_internal_links', 'high', metadata: ['links' => $broken]);
    }
}
