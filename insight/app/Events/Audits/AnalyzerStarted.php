<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class AnalyzerStarted extends AuditBroadcast
{
    public function __construct(
        string $auditUuid,
        private readonly string $category,
        private readonly string $label,
    ) {
        parent::__construct($auditUuid);
    }

    public function broadcastAs(): string
    {
        return 'AnalyzerStarted';
    }

    public function broadcastWith(): array
    {
        return [
            'category' => $this->category,
            'label' => $this->label,
        ];
    }
}
