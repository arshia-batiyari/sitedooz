<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class PageCrawled extends AuditBroadcast
{
    /**
     * @param  list<string>  $internalLinks
     */
    public function __construct(
        string $auditUuid,
        private readonly string $url,
        private readonly string $finalUrl,
        private readonly string $path,
        private readonly int $pageNumber,
        private readonly int $pagesFound,
        private readonly int $statusCode,
        private readonly int $progress,
        private readonly ?string $title,
        private readonly int $depth,
        private readonly ?string $discoveredFrom,
        private readonly int $internalLinkCount,
        private readonly array $internalLinks,
        private readonly bool $hasMetaDescription,
        private readonly bool $hasH1,
    ) {
        parent::__construct($auditUuid);
    }

    public function broadcastAs(): string
    {
        return 'PageCrawled';
    }

    public function broadcastWith(): array
    {
        return [
            'url' => $this->url,
            'final_url' => $this->finalUrl,
            'path' => $this->path,
            'page_number' => $this->pageNumber,
            'pages_found' => $this->pagesFound,
            'status_code' => $this->statusCode,
            'progress' => $this->progress,
            'title' => $this->title,
            'depth' => $this->depth,
            'discovered_from' => $this->discoveredFrom,
            'internal_link_count' => $this->internalLinkCount,
            'internal_links' => $this->internalLinks,
            'has_meta_description' => $this->hasMetaDescription,
            'has_h1' => $this->hasH1,
        ];
    }
}
