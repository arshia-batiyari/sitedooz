<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Exceptions\Audits\CrawlException;
use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\PageSnapshot;
use Throwable;

final class SiteCrawler
{
    public function __construct(
        private readonly SafeHttpClient $http,
        private readonly UrlSafety $urls,
        private readonly RobotsParser $robotsParser,
        private readonly SitemapCollector $sitemaps,
        private readonly HtmlSnapshotParser $parser,
    ) {}

    public function crawl(string $startUrl): CrawlResult
    {
        $startUrl = $this->urls->assertSafe($startUrl);
        $deadline = microtime(true) + (int) config('audit.crawl.deadline_seconds');
        $maxPages = max(1, (int) config('audit.crawl.max_pages'));
        $maxDepth = max(0, (int) config('audit.crawl.max_depth'));
        $delay = max(0, (int) config('audit.crawl.delay_ms'));
        $userAgent = (string) config('audit.crawl.user_agent');
        $host = strtolower((string) parse_url($startUrl, PHP_URL_HOST));
        $origin = parse_url($startUrl, PHP_URL_SCHEME).'://'.$host;

        $robots = $this->fetchRobots($origin);
        $sitemap = $this->sitemaps->collect($robots->sitemaps, $startUrl, $this->http);

        $queue = [[$startUrl, 0]];
        $seen = [];
        $pages = [];
        $limited = false;
        $fetched = 0;

        while ($queue !== [] && count($pages) < $maxPages) {
            if (microtime(true) > $deadline) {
                $limited = true;
                break;
            }

            [$url, $depth] = array_shift($queue);
            if (isset($seen[$url])) {
                continue;
            }
            $seen[$url] = true;

            if (! $robots->isAllowed($url, $userAgent)) {
                $pages[] = PageSnapshot::make([
                    'url' => $url,
                    'finalUrl' => $url,
                    'statusCode' => 0,
                    'depth' => $depth,
                    'blockedByRobots' => true,
                    'title' => null,
                    'metaDescription' => null,
                    'canonical' => null,
                    'h1' => [],
                    'headingLevels' => [],
                    'contentType' => '',
                    'wordCount' => 0,
                    'phones' => [],
                    'hasForm' => false,
                    'formFieldCount' => 0,
                    'ctas' => [],
                    'hasWhatsapp' => false,
                    'hasOpenGraph' => false,
                    'hasTwitterCard' => false,
                    'hasViewport' => false,
                    'textSample' => '',
                ]);

                continue;
            }

            if ($fetched > 0 && $delay > 0) {
                usleep($delay * 1000);
            }

            try {
                $fetch = $this->http->get($url);
                $fetched++;
            } catch (Throwable $exception) {
                if ($pages === []) {
                    throw $exception instanceof CrawlException
                        ? $exception
                        : new CrawlException('دریافت صفحه اصلی ممکن نشد.', 0, $exception);
                }
                $pages[] = PageSnapshot::make([
                    'url' => $url,
                    'finalUrl' => $url,
                    'statusCode' => 0,
                    'depth' => $depth,
                    'error' => 'دریافت این صفحه ممکن نشد.',
                    'title' => null,
                    'metaDescription' => null,
                    'canonical' => null,
                    'h1' => [],
                    'contentType' => '',
                    'wordCount' => 0,
                    'textSample' => '',
                ]);

                continue;
            }

            $contentType = $fetch->header('content-type');
            if (str_contains(strtolower($contentType), 'html')) {
                $page = $this->parser->parse(
                    $fetch->body,
                    $url,
                    $fetch->finalUrl,
                    $fetch->status,
                    $fetch->redirectChain,
                    $fetch->headers,
                    $depth,
                );
            } else {
                $page = PageSnapshot::make([
                    'url' => $url,
                    'finalUrl' => $fetch->finalUrl,
                    'statusCode' => $fetch->status,
                    'redirectChain' => $fetch->redirectChain,
                    'depth' => $depth,
                    'headers' => $fetch->headers,
                    'title' => null,
                    'metaDescription' => null,
                    'canonical' => null,
                    'h1' => [],
                    'headingLevels' => [],
                    'contentType' => $contentType,
                    'wordCount' => 0,
                    'phones' => [],
                    'hasForm' => false,
                    'formFieldCount' => 0,
                    'ctas' => [],
                    'hasWhatsapp' => false,
                    'hasOpenGraph' => false,
                    'hasTwitterCard' => false,
                    'hasViewport' => false,
                    'textSample' => '',
                ]);
            }

            $pages[] = $page;

            if ($depth < $maxDepth && $page->isHtmlSuccess()) {
                foreach ($page->internalLinks as $link) {
                    $linkHost = strtolower((string) parse_url($link, PHP_URL_HOST));
                    if ($linkHost === $host && ! isset($seen[$link])) {
                        $queue[] = [$link, $depth + 1];
                    }
                }
            }
        }

        if ($queue !== [] && count($pages) >= $maxPages) {
            $limited = true;
        }

        $homepage = $pages[0] ?? null;

        return new CrawlResult(
            pages: $pages,
            robotsFound: $robots->found,
            sitemapStatus: $sitemap['status'],
            sitemapUrls: $sitemap['urls'],
            homepageBlocked: $homepage?->blockedByRobots === true,
            stats: [
                'pages' => count($pages),
                'fetched' => $fetched,
                'limited' => $limited,
                'sitemap_count' => count($sitemap['urls']),
                'robots_found' => $robots->found,
            ],
        );
    }

    private function fetchRobots(string $origin): RobotsRules
    {
        $url = $this->urls->tryNormalize($origin.'/robots.txt');
        if ($url === null) {
            return RobotsRules::allowAll();
        }

        try {
            $fetch = $this->http->get($url);
        } catch (Throwable) {
            return RobotsRules::allowAll();
        }

        if ($fetch->status === 404) {
            return RobotsRules::allowAll();
        }

        if ($fetch->status >= 400) {
            return new RobotsRules([], [], false);
        }

        return $this->robotsParser->parse($fetch->body, true);
    }
}
