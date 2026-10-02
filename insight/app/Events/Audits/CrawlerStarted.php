<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class CrawlerStarted extends AuditBroadcast
{
    public function broadcastAs(): string
    {
        return 'CrawlerStarted';
    }

    public function broadcastWith(): array
    {
        return [
            'status' => 'crawling',
        ];
    }
}
