<?php

declare(strict_types=1);

namespace App\Services\Audits\Analysis;

use App\Enums\FindingSeverity;

final class AuditCheck
{
    public function __construct(
        public readonly string $key,
        public readonly bool $passed,
        public readonly FindingSeverity $severity,
        public readonly ?FindingDraft $finding = null,
    ) {}

    public static function pass(string $key): self
    {
        return new self($key, true, FindingSeverity::Info);
    }

    public static function fail(FindingDraft $finding): self
    {
        return new self($finding->ruleKey, false, $finding->severity, $finding);
    }
}
