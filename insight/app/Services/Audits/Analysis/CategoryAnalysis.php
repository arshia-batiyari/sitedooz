<?php

declare(strict_types=1);

namespace App\Services\Audits\Analysis;

final class CategoryAnalysis
{
    /**
     * @param  list<AuditCheck>  $checks
     * @param  list<array{name: string, value: float|int, unit: ?string}>  $metrics
     */
    public function __construct(
        public readonly string $category,
        public readonly string $label,
        public readonly array $checks,
        public readonly array $metrics = [],
    ) {}
}
