<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Services\Audits\Data\PageSnapshot;

interface CrawlObserver
{
    public function robotsChecked(bool $checked, bool $found): void;

    public function sitemapChecked(string $status, int $count): void;

    public function pageCrawled(PageSnapshot $page, int $pageNumber, int $pagesFound, ?string $discoveredFrom): void;
}
