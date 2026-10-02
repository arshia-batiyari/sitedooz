<?php

declare(strict_types=1);

namespace App\Services\Audits\Data;

final class Signal
{
    /**
     * @param  array<string, mixed>  $metadata
     */
    public function __construct(
        public readonly string $ruleKey,
        public readonly bool $applicable,
        public readonly bool $passed,
        public readonly ?string $severity,
        public readonly ?string $pageUrl,
        public readonly array $metadata = [],
        public readonly bool $needsHumanReview = false,
        public readonly ?string $detail = null,
    ) {}

    /**
     * @param  array<string, mixed>  $metadata
     */
    public static function pass(string $key, ?string $pageUrl = null, array $metadata = []): self
    {
        return new self($key, true, true, null, $pageUrl, $metadata);
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    public static function fail(
        string $key,
        string $severity,
        ?string $pageUrl = null,
        array $metadata = [],
        ?string $detail = null,
    ): self {
        return new self($key, true, false, $severity, $pageUrl, $metadata, false, $detail);
    }

    public static function notApplicable(string $key, ?string $pageUrl = null): self
    {
        return new self($key, false, true, null, $pageUrl);
    }

    public static function review(string $key, ?string $pageUrl = null, ?string $detail = null): self
    {
        return new self($key, false, false, 'info', $pageUrl, [], true, $detail);
    }
}
