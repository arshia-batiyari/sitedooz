<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class MetricCalculated extends AuditBroadcast
{
    public function __construct(
        string $auditUuid,
        private readonly string $name,
        private readonly float|int $value,
        private readonly ?string $unit,
    ) {
        parent::__construct($auditUuid);
    }

    public function broadcastAs(): string
    {
        return 'MetricCalculated';
    }

    public function broadcastWith(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'unit' => $this->unit,
        ];
    }
}
