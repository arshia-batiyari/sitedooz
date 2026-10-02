<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Contracts\Audits\DnsResolver;
use App\Exceptions\Audits\UnsafeUrlException;
use App\Services\Audits\UrlSafety;
use PHPUnit\Framework\TestCase;

class UrlSafetyTest extends TestCase
{
    public function test_blocks_private_and_non_http_targets(): void
    {
        $safety = $this->safety(['93.184.216.34']);

        foreach ([
            'http://127.0.0.1/',
            'http://10.1.1.1/',
            'http://192.168.1.20/admin',
            'http://169.254.169.254/latest/meta-data',
            'http://localhost/',
            'http://user:secret@example.com/',
            'file:///etc/passwd',
            'http://100.64.0.5/',
            'http://example.com:22/',
            'http://[::1]/',
            'http://[::ffff:127.0.0.1]/',
        ] as $url) {
            try {
                $safety->assertSafe($url);
                $this->fail('Expected block for '.$url);
            } catch (UnsafeUrlException) {
                $this->assertTrue(true);
            }
        }
    }

    public function test_allows_public_addresses(): void
    {
        $safety = $this->safety(['93.184.216.34']);

        $this->assertSame('https://example.com/', $safety->assertSafe('https://Example.com'));
        $this->assertSame('http://1.1.1.1/', $safety->assertSafe('http://1.1.1.1'));
    }

    public function test_blocks_host_that_resolves_to_a_private_address(): void
    {
        $safety = $this->safety(['127.0.0.1']);

        $this->expectException(UnsafeUrlException::class);
        $safety->assertSafe('https://example.com/');
    }

    /**
     * @param  list<string>  $ips
     */
    private function safety(array $ips): UrlSafety
    {
        return new UrlSafety(new class($ips) implements DnsResolver
        {
            public function __construct(private array $ips) {}

            public function resolve(string $host): array
            {
                return $this->ips;
            }
        });
    }
}
