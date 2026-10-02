<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class ScoreUpdated extends AuditBroadcast
{
    /**
     * @param  array<string, int>  $categories
     */
    public function __construct(
        string $auditUuid,
        private readonly ?int $overallScore,
        private readonly array $categories,
        private readonly int $progress,
    ) {
        parent::__construct($auditUuid);
    }

    public function broadcastAs(): string
    {
        return 'ScoreUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'overall_score' => $this->overallScore,
            'categories' => $this->categories,
            'progress' => $this->progress,
        ];
    }
}
