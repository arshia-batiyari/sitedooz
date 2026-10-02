<?php

declare(strict_types=1);

namespace App\Services\Audits;

use Throwable;

final class SitemapCollector
{
    public function __construct(private readonly UrlSafety $safety) {}

    /**
     * @param  list<string>  $seeds
     * @return array{status: string, urls: list<string>}
     */
    public function collect(array $seeds, string $startUrl, SafeHttpClient $http): array
    {
        $parts = parse_url($startUrl);
        $origin = is_array($parts) ? ($parts['scheme'].'://'.$parts['host']) : '';
        if ($seeds === [] && $origin !== '') {
            $seeds[] = $origin.'/sitemap.xml';
        }

        $urls = [];
        $queue = array_slice(array_values(array_unique($seeds)), 0, 3);
        $seen = [];
        $sawDocument = false;
        $sawLocations = false;
        $attempts = 0;
        $transportFailures = 0;

        while ($queue !== [] && count($seen) < 5 && count($urls) < 500) {
            $mapUrl = array_shift($queue);
            $normalized = $this->safety->tryNormalize($mapUrl);
            if ($normalized === null || isset($seen[$normalized])) {
                continue;
            }
            $seen[$normalized] = true;
            $attempts++;

            try {
                $fetch = $http->get($normalized);
            } catch (Throwable) {
                $transportFailures++;

                continue;
            }

            if ($fetch->status >= 400) {
                continue;
            }

            $sawDocument = true;
            $locs = $this->locations($fetch->body);
            if ($locs === []) {
                continue;
            }
            $sawLocations = true;
            $isIndex = str_contains(strtolower($fetch->body), '<sitemapindex');
            foreach ($locs as $loc) {
                if ($isIndex) {
                    if (count($queue) < 5) {
                        $queue[] = $loc;
                    }

                    continue;
                }
                $safe = $this->safety->tryNormalize($loc);
                if ($safe === null) {
                    continue;
                }
                $host = strtolower((string) parse_url($safe, PHP_URL_HOST));
                $startHost = strtolower((string) parse_url($startUrl, PHP_URL_HOST));
                if ($host === $startHost) {
                    $urls[] = $safe;
                }
            }
        }

        $status = 'missing';
        if ($sawLocations) {
            $status = 'ok';
        } elseif ($sawDocument) {
            $status = 'unreadable';
        } elseif ($attempts > 0 && $transportFailures === $attempts) {
            $status = 'unchecked';
        }

        return [
            'status' => $status,
            'urls' => array_values(array_unique($urls)),
        ];
    }

    /**
     * @return list<string>
     */
    private function locations(string $xml): array
    {
        $previous = libxml_use_internal_errors(true);
        $document = simplexml_load_string($xml);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        if ($document === false) {
            return [];
        }

        $locs = [];
        foreach ($document->xpath('//*[local-name()="loc"]') ?: [] as $node) {
            $value = trim((string) $node);
            if ($value !== '') {
                $locs[] = $value;
            }
        }

        return $locs;
    }
}
