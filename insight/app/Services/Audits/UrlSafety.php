<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Contracts\Audits\DnsResolver;
use App\Exceptions\Audits\UnsafeUrlException;

final class UrlSafety
{
    /** @var array<string, list<string>> */
    private array $resolved = [];

    public function __construct(private readonly DnsResolver $dns) {}

    public function assertSafe(string $url): string
    {
        $parts = parse_url(trim($url));
        if (! is_array($parts) || ! isset($parts['scheme'], $parts['host'])) {
            throw new UnsafeUrlException('نشانی وب معتبر نیست.');
        }

        $scheme = strtolower((string) $parts['scheme']);
        if (! in_array($scheme, ['http', 'https'], true)) {
            throw new UnsafeUrlException('فقط نشانی‌های http و https پذیرفته می‌شوند.');
        }

        if (isset($parts['user']) || isset($parts['pass'])) {
            throw new UnsafeUrlException('نشانی نباید نام کاربری یا رمز داشته باشد.');
        }

        $host = strtolower((string) $parts['host']);
        if (str_starts_with($host, '[') && str_ends_with($host, ']')) {
            $host = substr($host, 1, -1);
        }

        $port = (int) ($parts['port'] ?? ($scheme === 'https' ? 443 : 80));
        if (! in_array($port, [80, 443], true)) {
            throw new UnsafeUrlException('فقط درگاه‌های ۸۰ و ۴۴۳ مجاز هستند.');
        }

        $this->assertHostSafe($host);

        $path = $parts['path'] ?? '/';
        if ($path === '') {
            $path = '/';
        }
        $query = isset($parts['query']) && $parts['query'] !== '' ? '?'.$parts['query'] : '';

        return $scheme.'://'.$host.$path.$query;
    }

    public function tryNormalize(string $url): ?string
    {
        try {
            return $this->assertSafe($url);
        } catch (UnsafeUrlException) {
            return null;
        }
    }

    public function resolveUrl(string $base, string $relative): ?string
    {
        $relative = trim($relative);
        if ($relative === '' || str_starts_with($relative, '#')) {
            return null;
        }
        if (preg_match('#^(javascript:|mailto:|tel:|data:)#i', $relative) === 1) {
            return null;
        }

        if (preg_match('#^https?://#i', $relative) === 1) {
            return $relative;
        }

        $parts = parse_url($base);
        if (! is_array($parts) || ! isset($parts['scheme'], $parts['host'])) {
            return null;
        }

        $origin = $parts['scheme'].'://'.$parts['host'];
        if (str_starts_with($relative, '//')) {
            return $parts['scheme'].':'.$relative;
        }
        if (str_starts_with($relative, '/')) {
            return $origin.$relative;
        }
        if (str_starts_with($relative, '?')) {
            $path = $parts['path'] ?? '/';

            return $origin.$path.$relative;
        }

        $path = $parts['path'] ?? '/';
        $slash = strrpos($path, '/');
        $dir = $slash === false ? '' : substr($path, 0, $slash);

        return $origin.$dir.'/'.$relative;
    }

    private function assertHostSafe(string $host): void
    {
        if ($host === '' || $this->isBlockedName($host)) {
            throw new UnsafeUrlException('نشانی داخلی مجاز نیست.');
        }

        if (ctype_digit($host)) {
            throw new UnsafeUrlException('نشانی داخلی مجاز نیست.');
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $this->assertIpSafe($host);

            return;
        }

        $ips = $this->resolved[$host] ??= $this->dns->resolve($host);
        if ($ips === []) {
            throw new UnsafeUrlException('نام میزبان قابل حل نیست.');
        }

        foreach ($ips as $ip) {
            $this->assertIpSafe($ip);
        }
    }

    public function assertIpSafe(string $ip): void
    {
        $ip = strtolower(trim($ip));
        if ($ip === '' || str_contains($ip, '%')) {
            throw new UnsafeUrlException('نشانی داخلی مجاز نیست.');
        }

        if (str_starts_with($ip, '::ffff:')) {
            $mapped = substr($ip, 7);
            if (filter_var($mapped, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $this->assertIpSafe($mapped);

                return;
            }
        }

        $flags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
        if (filter_var($ip, FILTER_VALIDATE_IP, $flags) === false) {
            throw new UnsafeUrlException('نشانی داخلی مجاز نیست.');
        }

        if ($this->isCgnat($ip) || $this->isIpv6Local($ip)) {
            throw new UnsafeUrlException('نشانی داخلی مجاز نیست.');
        }
    }

    private function isBlockedName(string $host): bool
    {
        return $host === 'localhost'
            || str_ends_with($host, '.localhost')
            || str_ends_with($host, '.local')
            || str_ends_with($host, '.internal')
            || $host === 'metadata.google.internal';
    }

    private function isCgnat(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            return false;
        }

        $long = ip2long($ip);
        if ($long === false) {
            return false;
        }

        return $long >= ip2long('100.64.0.0') && $long <= ip2long('100.127.255.255');
    }

    private function isIpv6Local(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            return false;
        }

        $bin = inet_pton($ip);
        if ($bin === false) {
            return true;
        }

        if ($bin === inet_pton('::1')) {
            return true;
        }

        $first = ord($bin[0]);
        if (($first & 0xFE) === 0xFC) {
            return true;
        }

        return $first === 0xFE && (ord($bin[1]) & 0xC0) === 0x80;
    }
}
