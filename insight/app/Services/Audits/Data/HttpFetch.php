<?php

declare(strict_types=1);

namespace App\Services\Audits\Data;

final class HttpFetch
{
    /**
     * @param  list<array{url: string, status: int}>  $redirectChain
     * @param  array<string, string>  $headers
     */
    public function __construct(
        public readonly string $requestedUrl,
        public readonly string $finalUrl,
        public readonly int $status,
        public readonly array $redirectChain,
        public readonly array $headers,
        public readonly string $body,
        public readonly int $durationMs = 0,
    ) {}

    public function header(string $name): string
    {
        return $this->headers[strtolower($name)] ?? '';
    }
}
