<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Models\Audit;
use App\Services\Audits\Data\PageSnapshot;

final class BroadcastingCrawlObserver implements CrawlObserver
{
    public function __construct(
        private readonly Audit $audit,
        private readonly AuditPersister $persister,
        private readonly AuditPublisher $publisher,
    ) {}

    public function robotsChecked(bool $checked, bool $found): void
    {
        if (! $checked) {
            return;
        }

        $this->persister->rememberMetric($this->audit, 'crawl', 'robots_txt', $found ? 1 : 0, 'bool');
        $this->publisher->metric($this->audit, 'robots_txt', $found ? 1 : 0, 'bool');
    }

    public function sitemapChecked(string $status, int $count): void
    {
        if ($status === 'unchecked') {
            return;
        }

        $this->persister->rememberMetric($this->audit, 'crawl', 'sitemap', $count, 'urls');
        $this->publisher->metric($this->audit, 'sitemap', $count, 'urls');
    }

    public function pageCrawled(PageSnapshot $page, int $pageNumber, int $pagesFound, ?string $discoveredFrom): void
    {
        $this->persister->rememberPage($this->audit, $page, $discoveredFrom);
        $this->persister->rememberCrawlProgress($this->audit, $pageNumber, $pagesFound);

        if ($page->depth === 0 && $page->statusCode > 0 && $page->error === null && ! $page->blockedByRobots) {
            $secure = str_starts_with(strtolower($page->finalUrl), 'https://') ? 1 : 0;
            $this->persister->rememberMetric($this->audit, 'crawl', 'https', $secure, 'bool');
            $this->publisher->metric($this->audit, 'https', $secure, 'bool');
        }

        $this->publisher->pageCrawled($this->audit, $page, $pageNumber, $pagesFound, $discoveredFrom);
    }
}
