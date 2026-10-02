<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Services\Audits\Data\PageSnapshot;
use DOMDocument;
use DOMElement;
use DOMXPath;

final class HtmlSnapshotParser
{
    public function __construct(private readonly UrlSafety $urls) {}

    /**
     * @param  list<array{url: string, status: int}>  $redirectChain
     * @param  array<string, string>  $headers
     */
    public function parse(
        string $html,
        string $requestedUrl,
        string $finalUrl,
        int $status,
        array $redirectChain,
        array $headers,
        int $depth,
    ): PageSnapshot {
        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($document);
        $pageHost = strtolower((string) parse_url($finalUrl, PHP_URL_HOST));

        $title = $this->text($xpath, '//title');
        $metaDescription = $this->meta($xpath, 'name', 'description');
        $metaRobots = $this->meta($xpath, 'name', 'robots');
        $canonical = $this->href($xpath, '//link[translate(@rel,"CANONICAL","canonical")="canonical"]');
        if ($canonical !== null) {
            $resolved = $this->urls->resolveUrl($finalUrl, $canonical);
            $canonical = $resolved === null ? $canonical : ($this->urls->tryNormalize($resolved) ?? $resolved);
        }

        $h1 = [];
        $headingLevels = [];
        foreach ($xpath->query('//h1|//h2|//h3|//h4|//h5|//h6') ?: [] as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }
            $level = (int) substr($node->tagName, 1);
            $headingLevels[] = $level;
            if ($level === 1) {
                $text = trim($node->textContent ?? '');
                if ($text !== '') {
                    $h1[] = $text;
                }
            }
        }

        $images = [];
        foreach ($xpath->query('//img') ?: [] as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }
            $src = $node->getAttribute('src');
            $images[] = [
                'src' => $src,
                'alt' => $node->hasAttribute('alt') ? $node->getAttribute('alt') : null,
            ];
        }

        $internal = [];
        $external = [];
        $phones = [];
        $ctas = [];
        $hasWhatsapp = false;
        foreach ($xpath->query('//a[@href]') ?: [] as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }
            $href = trim($node->getAttribute('href'));
            $label = trim(preg_replace('/\s+/u', ' ', $node->textContent ?? '') ?? '');
            if (preg_match('#^tel:#i', $href) === 1) {
                $phones[] = rawurldecode(substr($href, 4));
            }
            if (preg_match('#wa\.me|whatsapp\.com#i', $href) === 1) {
                $hasWhatsapp = true;
            }
            if ($label !== '' && preg_match('/مشاوره|تماس|ثبت|سفارش|خرید|درخواست|شروع|contact|call/iu', $label) === 1) {
                $ctas[] = $label;
            }
            $absolute = $this->urls->resolveUrl($finalUrl, $href);
            if ($absolute === null) {
                continue;
            }
            $host = strtolower((string) parse_url($absolute, PHP_URL_HOST));
            if ($host === $pageHost) {
                $safe = $this->urls->tryNormalize($absolute);
                if ($safe !== null) {
                    $internal[] = $safe;
                }
            } else {
                $external[] = $absolute;
            }
        }

        $hreflang = [];
        foreach ($xpath->query('//link[contains(translate(@rel,"ALTERNATE","alternate"),"alternate") and @hreflang]') ?: [] as $node) {
            if ($node instanceof DOMElement) {
                $hreflang[] = strtolower($node->getAttribute('hreflang'));
            }
        }

        $jsonLdTypes = [];
        foreach ($xpath->query('//script[translate(@type,"LD+JSON","ld+json")="application/ld+json"]') ?: [] as $node) {
            $decoded = json_decode(trim($node->textContent ?? ''), true);
            foreach ($this->jsonTypes($decoded) as $type) {
                $jsonLdTypes[] = $type;
            }
        }

        $openGraph = $xpath->query('//meta[starts-with(translate(@property,"OG","og"),"og:")]');
        $twitter = $xpath->query('//meta[starts-with(translate(@name,"TWITTER","twitter"),"twitter:")]');
        $hasOpenGraph = $openGraph !== false && $openGraph->length > 0;
        $hasTwitter = $twitter !== false && $twitter->length > 0;
        $hasViewport = $this->meta($xpath, 'name', 'viewport') !== null;

        $bodyText = trim(preg_replace('/\s+/u', ' ', $this->text($xpath, '//body') ?? '') ?? '');
        $wordCount = $bodyText === '' ? 0 : count(preg_split('/\s+/u', $bodyText) ?: []);
        $textSample = mb_substr($bodyText, 0, 1500);

        if (preg_match_all('/(?:\+98|0)9\d{9}/', $bodyText, $matches) === 1 || isset($matches[0])) {
            foreach ($matches[0] as $phone) {
                $phones[] = $phone;
            }
        }

        $forms = $xpath->query('//form') ?: [];
        $hasForm = $forms->length > 0;
        $fieldCount = 0;
        if ($hasForm) {
            foreach ($xpath->query('//form//input|//form//select|//form//textarea') ?: [] as $field) {
                if (! $field instanceof DOMElement) {
                    continue;
                }
                $type = strtolower($field->getAttribute('type'));
                if (in_array($type, ['hidden', 'submit', 'button', 'image', 'reset'], true)) {
                    continue;
                }
                $fieldCount++;
            }
        }
        foreach ($xpath->query('//button') ?: [] as $button) {
            $label = trim(preg_replace('/\s+/u', ' ', $button->textContent ?? '') ?? '');
            if ($label !== '' && preg_match('/مشاوره|تماس|ثبت|سفارش|خرید|درخواست|شروع|contact/iu', $label) === 1) {
                $ctas[] = $label;
            }
        }

        $mixed = [];
        if (str_starts_with(strtolower($finalUrl), 'https://')) {
            $mixed = $this->mixedContent($xpath, $finalUrl);
        }

        $headerRobots = $headers['x-robots-tag'] ?? '';
        if ($metaRobots === null && $headerRobots !== '') {
            $metaRobots = $headerRobots;
        }

        return new PageSnapshot(
            url: $requestedUrl,
            finalUrl: $finalUrl,
            statusCode: $status,
            redirectChain: $redirectChain,
            depth: $depth,
            headers: $headers,
            title: $title !== '' ? $title : null,
            metaDescription: $metaDescription,
            metaRobots: $metaRobots,
            canonical: $canonical,
            h1: $h1,
            headingLevels: $headingLevels,
            images: $images,
            internalLinks: array_values(array_unique($internal)),
            externalLinks: array_values(array_unique($external)),
            hreflang: array_values(array_unique($hreflang)),
            jsonLdTypes: array_values(array_unique($jsonLdTypes)),
            hasOpenGraph: $hasOpenGraph,
            hasTwitterCard: $hasTwitter,
            hasViewport: $hasViewport,
            wordCount: $wordCount,
            phones: array_values(array_unique($phones)),
            hasForm: $hasForm,
            formFieldCount: $fieldCount,
            ctas: array_values(array_unique($ctas)),
            hasWhatsapp: $hasWhatsapp,
            mixedContent: $mixed,
            blockedByRobots: false,
            error: null,
            contentType: 'text/html',
            textSample: $textSample,
        );
    }

    private function text(DOMXPath $xpath, string $query): string
    {
        $value = $xpath->evaluate('string('.$query.')');

        return trim(is_string($value) ? $value : '');
    }

    private function meta(DOMXPath $xpath, string $attribute, string $name): ?string
    {
        $query = sprintf(
            '//meta[translate(@%s, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")="%s"]',
            $attribute,
            strtolower($name),
        );
        $node = $xpath->query($query)?->item(0);
        if (! $node instanceof DOMElement) {
            return null;
        }
        $content = trim($node->getAttribute('content'));

        return $content === '' ? null : $content;
    }

    private function href(DOMXPath $xpath, string $query): ?string
    {
        $node = $xpath->query($query)?->item(0);
        if (! $node instanceof DOMElement) {
            return null;
        }
        $href = trim($node->getAttribute('href'));

        return $href === '' ? null : $href;
    }

    /**
     * @return list<string>
     */
    private function jsonTypes(mixed $decoded): array
    {
        if (! is_array($decoded)) {
            return [];
        }
        $types = [];
        if (isset($decoded['@type'])) {
            $raw = $decoded['@type'];
            foreach (is_array($raw) ? $raw : [$raw] as $type) {
                if (is_string($type) && $type !== '') {
                    $types[] = $type;
                }
            }
        }
        foreach ($decoded as $value) {
            if (is_array($value)) {
                $types = array_merge($types, $this->jsonTypes($value));
            }
        }

        return $types;
    }

    /**
     * @return list<array{url: string, kind: string}>
     */
    private function mixedContent(DOMXPath $xpath, string $base): array
    {
        $found = [];
        $selectors = [
            'script' => 'src',
            'iframe' => 'src',
            'img' => 'src',
            'audio' => 'src',
            'video' => 'src',
            'source' => 'src',
        ];
        foreach ($selectors as $tag => $attribute) {
            foreach ($xpath->query('//'.$tag.'[@'.$attribute.']') ?: [] as $node) {
                if (! $node instanceof DOMElement) {
                    continue;
                }
                $this->collectMixed($found, $node->getAttribute($attribute), $tag, $base);
            }
        }
        foreach ($xpath->query('//link[@rel="stylesheet" and @href]') ?: [] as $node) {
            if ($node instanceof DOMElement) {
                $this->collectMixed($found, $node->getAttribute('href'), 'stylesheet', $base);
            }
        }

        return $found;
    }

    /**
     * @param  list<array{url: string, kind: string}>  $found
     */
    private function collectMixed(array &$found, string $value, string $kind, string $base): void
    {
        $absolute = $this->urls->resolveUrl($base, trim($value));
        if ($absolute !== null && str_starts_with(strtolower($absolute), 'http://')) {
            $found[] = ['url' => $absolute, 'kind' => $kind];
        }
    }
}
