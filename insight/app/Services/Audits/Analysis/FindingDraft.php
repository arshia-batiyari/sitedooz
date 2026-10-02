<?php

declare(strict_types=1);

namespace App\Services\Audits\Analysis;

use App\Enums\FindingSeverity;

final class FindingDraft
{
    public function __construct(
        public readonly string $ruleKey,
        public readonly string $category,
        public readonly FindingSeverity $severity,
        public readonly string $title,
        public readonly string $description,
        public readonly string $businessImpact,
        public readonly string $recommendation,
        public readonly ?string $url,
    ) {}

    /**
     * @return array{rule_key: string, category: string, severity: string, title: string, url: ?string, recommendation: string}
     */
    public function toPublic(): array
    {
        return [
            'rule_key' => $this->ruleKey,
            'category' => $this->category,
            'severity' => $this->severity->value,
            'title' => $this->title,
            'url' => $this->url,
            'recommendation' => $this->recommendation,
        ];
    }
}
