<?php

declare(strict_types=1);

namespace App\Services\Audits\Data;

final class PageSpeedReport
{
    public function __construct(
        public readonly bool $available,
        public readonly ?StrategyMetrics $mobile,
        public readonly ?StrategyMetrics $desktop,
        public readonly ?string $reason = null,
    ) {}

    public static function unavailable(string $reason = 'داده PageSpeed در دسترس نیست.'): self
    {
        return new self(false, null, null, $reason);
    }
}
