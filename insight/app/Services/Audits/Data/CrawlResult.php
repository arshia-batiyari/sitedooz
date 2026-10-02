<?php

declare(strict_types=1);

namespace App\Services\Audits\Data;

final class CrawlResult
{
    /**
     * @param  list<PageSnapshot>  $pages
     * @param  list<string>  $sitemapUrls
     * @param  array<string, mixed>  $stats
     */
    public function __construct(
        public readonly array $pages,
        public readonly bool $robotsFound,
        public readonly string $sitemapStatus,
        public readonly array $sitemapUrls,
        public readonly bool $homepageBlocked,
        public readonly array $stats,
    ) {}

    public function homepage(): ?PageSnapshot
    {
        return $this->pages[0] ?? null;
    }

    /**
     * @return list<PageSnapshot>
     */
    public function htmlPages(): array
    {
        return array_values(array_filter(
            $this->pages,
            fn (PageSnapshot $page): bool => $page->isHtmlSuccess(),
        ));
    }
}
