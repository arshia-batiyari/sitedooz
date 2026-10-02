<?php

declare(strict_types=1);

namespace App\Services\Audits\Data;

final class CertificateReport
{
    public function __construct(
        public readonly bool $applicable,
        public readonly bool $valid,
        public readonly ?int $validTo,
        public readonly ?string $detail,
    ) {}

    public static function valid(int $validTo): self
    {
        return new self(true, true, $validTo, null);
    }

    public static function invalid(string $detail): self
    {
        return new self(true, false, null, $detail);
    }

    public static function notApplicable(): self
    {
        return new self(false, false, null, null);
    }
}
