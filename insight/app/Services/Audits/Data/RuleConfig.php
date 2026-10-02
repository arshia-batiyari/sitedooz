<?php

declare(strict_types=1);

namespace App\Services\Audits\Data;

final class RuleConfig
{
    public function __construct(
        public readonly string $key,
        public readonly string $category,
        public readonly string $severity,
        public readonly int $weight,
        public readonly bool $isActive,
    ) {}
}
