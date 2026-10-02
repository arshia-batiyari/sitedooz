<?php

declare(strict_types=1);

namespace App\Services\Audits\Rules;

final class RuleDefinition
{
    public function __construct(
        public readonly string $key,
        public readonly string $category,
        public readonly string $name,
        public readonly string $description,
        public readonly string $businessImpact,
        public readonly string $recommendation,
        public readonly string $severity,
        public readonly int $weight = 1,
        public readonly bool $active = true,
    ) {}
}
