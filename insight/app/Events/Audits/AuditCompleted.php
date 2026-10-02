<?php

declare(strict_types=1);

namespace App\Events\Audits;

final class AuditCompleted extends AuditBroadcast
{
    /**
     * @param  array<string, int>  $categories
     * @param  list<array<string, mixed>>  $findings
     * @param  list<array<string, mixed>>  $topIssues
     * @param  list<string>  $topRecommendations
     */
    public function __construct(
        string $auditUuid,
        private readonly ?int $overallScore,
        private readonly array $categories,
        private readonly array $findings,
        private readonly array $topIssues,
        private readonly array $topRecommendations,
    ) {
        parent::__construct($auditUuid);
    }

    public function broadcastAs(): string
    {
        return 'AuditCompleted';
    }

    public function broadcastWith(): array
    {
        return [
            'overall_score' => $this->overallScore,
            'categories' => $this->categories,
            'findings' => $this->findings,
            'top_issues' => $this->topIssues,
            'top_recommendations' => $this->topRecommendations,
            'progress' => 100,
        ];
    }
}
