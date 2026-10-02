<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class FindingDetected extends AuditBroadcast
{
    public function __construct(
        string $auditUuid,
        private readonly string $ruleKey,
        private readonly string $category,
        private readonly string $severity,
        private readonly string $title,
        private readonly ?string $url,
        private readonly string $recommendation,
    ) {
        parent::__construct($auditUuid);
    }

    public function broadcastAs(): string
    {
        return 'FindingDetected';
    }

    public function broadcastWith(): array
    {
        return [
            'rule_key' => $this->ruleKey,
            'category' => $this->category,
            'severity' => $this->severity,
            'title' => $this->title,
            'url' => $this->url,
            'recommendation' => $this->recommendation,
        ];
    }
}
