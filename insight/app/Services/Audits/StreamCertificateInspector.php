<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Contracts\Audits\CertificateInspector;
use App\Exceptions\Audits\UnsafeUrlException;
use App\Services\Audits\Data\CertificateReport;

final class StreamCertificateInspector implements CertificateInspector
{
    public function __construct(
        private readonly UrlSafety $safety,
        private readonly \App\Contracts\Audits\DnsResolver $dns,
    ) {}

    public function inspect(string $url): CertificateReport
    {
        try {
            $safe = $this->safety->assertSafe($url);
        } catch (UnsafeUrlException) {
            return CertificateReport::notApplicable();
        }

        if (! str_starts_with($safe, 'https://')) {
            return CertificateReport::notApplicable();
        }

        $host = (string) parse_url($safe, PHP_URL_HOST);
        try {
            $ips = $this->dns->resolve($host);
            foreach ($ips as $ip) {
                $this->safety->assertIpSafe($ip);
            }
        } catch (UnsafeUrlException $exception) {
            return CertificateReport::invalid($exception->getMessage());
        }

        if ($ips === []) {
            return CertificateReport::invalid('میزبان قابل حل نیست.');
        }

        $ip = $ips[0];
        $target = str_contains($ip, ':') ? '['.$ip.']' : $ip;
        $context = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
                'verify_peer' => true,
                'verify_peer_name' => true,
                'SNI_enabled' => true,
                'peer_name' => $host,
            ],
        ]);

        $errno = 0;
        $error = '';
        $client = @stream_socket_client(
            'ssl://'.$target.':443',
            $errno,
            $error,
            8,
            STREAM_CLIENT_CONNECT,
            $context,
        );

        if ($client === false) {
            return CertificateReport::invalid('گواهی قابل تأیید نیست.');
        }

        $params = stream_context_get_params($client);
        $peer = $params['options']['ssl']['peer_certificate'] ?? null;
        $parsed = is_resource($peer) || $peer instanceof \OpenSSLCertificate ? openssl_x509_parse($peer) : false;
        fclose($client);

        if (! is_array($parsed)) {
            return CertificateReport::invalid('گواهی خوانده نشد.');
        }

        $validTo = (int) ($parsed['validTo_time_t'] ?? 0);
        if ($validTo < time()) {
            return CertificateReport::invalid('گواهی منقضی شده است.');
        }

        return CertificateReport::valid($validTo);
    }
}
