<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class AuditFailed extends AuditBroadcast
{
    public function __construct(string $auditUuid, private readonly string $reason)
    {
        parent::__construct($auditUuid);
    }

    public function broadcastAs(): string
    {
        return 'AuditFailed';
    }

    public function broadcastWith(): array
    {
        return [
            'reason' => $this->reason,
        ];
    }
}
