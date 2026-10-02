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

    public function test_valid_url_is_normalized(): void
    {
        $safety = $this->safety(['93.184.216.34']);

        $this->assertSame('https://example.com/docs', $safety->assertSafe('https://Example.com/docs#section'));
        $this->assertSame('http://1.1.1.1/', $safety->assertSafe('http://1.1.1.1'));
    }

    public function test_invalid_url_is_rejected(): void
    {
        $safety = $this->safety(['93.184.216.34']);

        foreach (['not a url', 'ftp://example.com/file', '/relative'] as $url) {
            try {
                $safety->assertSafe($url);
                $this->fail('Expected invalid URL rejection for '.$url);
            } catch (UnsafeUrlException) {
                $this->assertTrue(true);
            }
        }
    }

    public function test_localhost_is_rejected(): void
    {
        $this->expectException(UnsafeUrlException::class);
        $this->safety(['93.184.216.34'])->assertSafe('http://localhost/admin');
    }

    public function test_private_ip_is_rejected(): void
    {
        $safety = $this->safety(['93.184.216.34']);

        foreach (['http://127.0.0.1/', 'http://10.0.0.8/', 'http://192.168.0.4/', 'http://172.16.0.2/'] as $url) {
            try {
                $safety->assertSafe($url);
                $this->fail('Expected private IP rejection for '.$url);
            } catch (UnsafeUrlException) {
                $this->assertTrue(true);
            }
        }
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
