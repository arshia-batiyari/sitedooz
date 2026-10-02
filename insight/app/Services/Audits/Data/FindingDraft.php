<?php

declare(strict_types=1);

namespace App\Services\Audits\Data;

final class FindingDraft
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public function __construct(
        public readonly string $ruleKey,
        public readonly string $category,
        public readonly string $severity,
        public readonly string $title,
        public readonly string $description,
        public readonly string $businessImpact,
        public readonly string $recommendation,
        public readonly ?string $pageUrl,
        public readonly array $metadata,
        public readonly float $scoreImpact,
        public readonly bool $needsHumanReview,
    ) {}
}
