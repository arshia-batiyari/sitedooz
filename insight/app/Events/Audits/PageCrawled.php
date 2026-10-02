<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class PageCrawled extends AuditBroadcast
{
    public function __construct(
        string $auditUuid,
        private readonly string $url,
        private readonly int $pageNumber,
        private readonly int $pagesFound,
        private readonly int $statusCode,
        private readonly int $progress,
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
            'page_number' => $this->pageNumber,
            'pages_found' => $this->pagesFound,
            'status_code' => $this->statusCode,
            'progress' => $this->progress,
        ];
    }
}
