<?php

declare(strict_types=1);

namespace App\Services\Audits\Analyzers;

use App\Services\Audits\Data\CertificateReport;
use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\Signal;

final class SecurityAnalyzer
{
    /**
     * @return list<Signal>
     */
    public function analyze(CrawlResult $crawl, CertificateReport $certificate): array
    {
        $home = $crawl->homepage();
        $https = $home !== null && str_starts_with(strtolower($home->finalUrl), 'https://');

        $signals = [];
        if (! $certificate->applicable) {
            $signals[] = Signal::notApplicable('certificate_status');
        } elseif ($certificate->valid) {
            $signals[] = Signal::pass('certificate_status', metadata: ['valid_to' => $certificate->validTo]);
        } else {
            $signals[] = Signal::fail('certificate_status', 'critical', $home?->finalUrl, [
                'detail' => $certificate->detail,
            ]);
        }

        $mixed = [];
        $active = false;
        foreach ($crawl->htmlPages() as $page) {
            foreach ($page->mixedContent as $item) {
                $mixed[] = $item;
                if (in_array($item['kind'], ['script', 'iframe', 'stylesheet'], true)) {
                    $active = true;
                }
            }
        }
        if (! $https) {
            $signals[] = Signal::notApplicable('mixed_content');
            $signals[] = Signal::notApplicable('hsts');
        } else {
            $signals[] = $mixed === []
                ? Signal::pass('mixed_content')
                : Signal::fail('mixed_content', $active ? 'high' : 'medium', $home?->finalUrl, ['resources' => $mixed]);
            $hsts = strtolower($home?->header('strict-transport-security') ?? '');
            $signals[] = $hsts === ''
                ? Signal::fail('hsts', 'medium', $home?->finalUrl)
                : Signal::pass('hsts', $home?->finalUrl);
        }

        $headers = [
            'x_content_type_options' => 'x-content-type-options',
            'content_security_policy' => 'content-security-policy',
            'referrer_policy' => 'referrer-policy',
        ];
        foreach ($headers as $rule => $header) {
            $value = $home?->header($header) ?? '';
            $signals[] = $value === ''
                ? Signal::fail($rule, $rule === 'content_security_policy' ? 'low' : 'low', $home?->finalUrl)
                : Signal::pass($rule, $home?->finalUrl);
        }

        $frame = $home?->header('x-frame-options') ?? '';
        $csp = strtolower($home?->header('content-security-policy') ?? '');
        $signals[] = ($frame !== '' || str_contains($csp, 'frame-ancestors'))
            ? Signal::pass('x_frame_options', $home?->finalUrl)
            : Signal::fail('x_frame_options', 'medium', $home?->finalUrl);

        return $signals;
    }
}
