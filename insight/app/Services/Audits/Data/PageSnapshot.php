<?php

declare(strict_types=1);

namespace App\Services\Audits\Data;

final class PageSnapshot
{
    /**
     * @param  list<array{url: string, status: int}>  $redirectChain
     * @param  array<string, string>  $headers
     * @param  list<string>  $h1
     * @param  list<int>  $headingLevels
     * @param  list<array{src: string, alt: ?string}>  $images
     * @param  list<string>  $internalLinks
     * @param  list<string>  $externalLinks
     * @param  list<string>  $hreflang
     * @param  list<string>  $jsonLdTypes
     * @param  list<string>  $phones
     * @param  list<string>  $ctas
     * @param  list<array{url: string, kind: string}>  $mixedContent
     */
    public function __construct(
        public readonly string $url,
        public readonly string $finalUrl,
        public readonly int $statusCode,
        public readonly array $redirectChain,
        public readonly int $depth,
        public readonly array $headers,
        public readonly ?string $title,
        public readonly ?string $metaDescription,
        public readonly ?string $metaRobots,
        public readonly ?string $canonical,
        public readonly array $h1,
        public readonly array $headingLevels,
        public readonly array $images,
        public readonly array $internalLinks,
        public readonly array $externalLinks,
        public readonly array $hreflang,
        public readonly array $jsonLdTypes,
        public readonly bool $hasOpenGraph,
        public readonly bool $hasTwitterCard,
        public readonly bool $hasViewport,
        public readonly int $wordCount,
        public readonly array $phones,
        public readonly bool $hasForm,
        public readonly int $formFieldCount,
        public readonly array $ctas,
        public readonly bool $hasWhatsapp,
        public readonly array $mixedContent,
        public readonly bool $blockedByRobots,
        public readonly ?string $error,
        public readonly string $contentType,
        public readonly string $textSample,
    ) {}

    /**
     * @param  array<string, mixed>  $overrides
     */
    public static function make(array $overrides = []): self
    {
        $data = array_merge([
            'url' => 'https://example.com/',
            'finalUrl' => 'https://example.com/',
            'statusCode' => 200,
            'redirectChain' => [],
            'depth' => 0,
            'headers' => [],
            'title' => 'عنوان نمونه برای صفحه اصلی',
            'metaDescription' => 'توضیح نمونه برای صفحه که طول مناسبی دارد و برای تست نوشته شده است.',
            'metaRobots' => null,
            'canonical' => 'https://example.com/',
            'h1' => ['سلام'],
            'headingLevels' => [1],
            'images' => [],
            'internalLinks' => [],
            'externalLinks' => [],
            'hreflang' => [],
            'jsonLdTypes' => [],
            'hasOpenGraph' => true,
            'hasTwitterCard' => true,
            'hasViewport' => true,
            'wordCount' => 400,
            'phones' => ['02100000000'],
            'hasForm' => true,
            'formFieldCount' => 3,
            'ctas' => ['مشاوره'],
            'hasWhatsapp' => true,
            'mixedContent' => [],
            'blockedByRobots' => false,
            'error' => null,
            'contentType' => 'text/html',
            'textSample' => 'مشاوره',
        ], $overrides);

        return new self(
            url: $data['url'],
            finalUrl: $data['finalUrl'],
            statusCode: $data['statusCode'],
            redirectChain: $data['redirectChain'],
            depth: $data['depth'],
            headers: $data['headers'],
            title: $data['title'],
            metaDescription: $data['metaDescription'],
            metaRobots: $data['metaRobots'],
            canonical: $data['canonical'],
            h1: $data['h1'],
            headingLevels: $data['headingLevels'],
            images: $data['images'],
            internalLinks: $data['internalLinks'],
            externalLinks: $data['externalLinks'],
            hreflang: $data['hreflang'],
            jsonLdTypes: $data['jsonLdTypes'],
            hasOpenGraph: $data['hasOpenGraph'],
            hasTwitterCard: $data['hasTwitterCard'],
            hasViewport: $data['hasViewport'],
            wordCount: $data['wordCount'],
            phones: $data['phones'],
            hasForm: $data['hasForm'],
            formFieldCount: $data['formFieldCount'],
            ctas: $data['ctas'],
            hasWhatsapp: $data['hasWhatsapp'],
            mixedContent: $data['mixedContent'],
            blockedByRobots: $data['blockedByRobots'],
            error: $data['error'],
            contentType: $data['contentType'],
            textSample: $data['textSample'],
        );
    }

    public function isHtmlSuccess(): bool
    {
        return $this->error === null
            && ! $this->blockedByRobots
            && $this->statusCode === 200
            && str_contains(strtolower($this->contentType), 'html');
    }

    public function header(string $name): string
    {
        return $this->headers[strtolower($name)] ?? '';
    }
}
