<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class AuditStarted extends AuditBroadcast
{
    public function __construct(string $auditUuid, private readonly string $url)
    {
        parent::__construct($auditUuid);
    }

    public function broadcastAs(): string
    {
        return 'AuditStarted';
    }

    public function broadcastWith(): array
    {
        return [
            'url' => $this->url,
            'status' => 'crawling',
        ];
    }
}
