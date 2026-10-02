<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class AnalyzerCompleted extends AuditBroadcast
{
    public function __construct(
        string $auditUuid,
        private readonly string $category,
        private readonly string $label,
        private readonly int $progress,
    ) {
        parent::__construct($auditUuid);
    }

    public function broadcastAs(): string
    {
        return 'AnalyzerCompleted';
    }

    public function broadcastWith(): array
    {
        return [
            'category' => $this->category,
            'label' => $this->label,
            'progress' => $this->progress,
        ];
    }
}
