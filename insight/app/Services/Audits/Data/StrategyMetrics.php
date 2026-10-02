<?php

declare(strict_types=1);

namespace App\Services\Audits\Data;

final class StrategyMetrics
{
    public function __construct(
        public readonly ?float $performanceScore,
        public readonly ?float $lcp,
        public readonly ?float $inp,
        public readonly ?float $cls,
        public readonly ?float $fcp,
        public readonly ?float $ttfb,
        public readonly ?float $speedIndex,
        public readonly ?float $tbt,
        public readonly ?float $fontSizeScore,
        public readonly ?float $tapTargetsScore,
    ) {}
}
