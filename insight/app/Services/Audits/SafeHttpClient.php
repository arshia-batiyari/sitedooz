<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Exceptions\Audits\CrawlException;
use App\Exceptions\Audits\UnsafeUrlException;
use App\Services\Audits\Data\HttpFetch;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Throwable;

final class SafeHttpClient
{
    public function __construct(private readonly UrlSafety $safety) {}

    public function get(string $url): HttpFetch
    {
        $current = $this->safety->assertSafe($url);
        $maxRedirects = (int) config('audit.crawl.max_redirects');
        $timeout = (int) config('audit.crawl.timeout_seconds');
        $maxBytes = (int) config('audit.crawl.max_response_bytes');
        $chain = [];

        for ($hop = 0; $hop <= $maxRedirects; $hop++) {
            try {
                $response = Http::withOptions([
                    'allow_redirects' => false,
                    'http_errors' => false,
                ])
                    ->timeout($timeout)
                    ->connectTimeout(min(5, $timeout))
                    ->withUserAgent((string) config('audit.crawl.user_agent'))
                    ->withHeaders(['Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'])
                    ->get($current);
            } catch (ConnectionException $exception) {
                throw new CrawlException('ارتباط با سایت برقرار نشد.', 0, $exception);
            } catch (Throwable $exception) {
                throw new CrawlException('دریافت صفحه ممکن نشد.', 0, $exception);
            }

            $status = $response->status();
            $chain[] = ['url' => $current, 'status' => $status];

            if ($status >= 300 && $status < 400) {
                $location = $response->header('Location');
                if (! is_string($location) || $location === '') {
                    throw new CrawlException('ریدایرکت بدون مقصد معتبر است.');
                }
                if ($hop === $maxRedirects) {
                    throw new CrawlException('زنجیره ریدایرکت بیش از حد مجاز است.');
                }
                $next = $this->safety->resolveUrl($current, $location);
                if ($next === null) {
                    throw new CrawlException('مقصد ریدایرکت معتبر نیست.');
                }
                try {
                    $current = $this->safety->assertSafe($next);
                } catch (UnsafeUrlException $exception) {
                    throw new CrawlException($exception->getMessage(), 0, $exception);
                }

                continue;
            }

            $body = $response->body();
            if (strlen($body) > $maxBytes) {
                throw new CrawlException('حجم پاسخ بیش از حد مجاز است.');
            }

            $headers = [];
            foreach ($response->headers() as $name => $values) {
                $headers[strtolower((string) $name)] = implode(', ', $values);
            }

            return new HttpFetch($url, $current, $status, $chain, $headers, $body);
        }

        throw new CrawlException('زنجیره ریدایرکت بیش از حد مجاز است.');
    }
}
