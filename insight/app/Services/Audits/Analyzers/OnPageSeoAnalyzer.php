<?php

declare(strict_types=1);

namespace App\Services\Audits\Analyzers;

use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\PageSnapshot;
use App\Services\Audits\Data\Signal;

final class OnPageSeoAnalyzer
{
    /**
     * @return list<Signal>
     */
    public function analyze(CrawlResult $crawl): array
    {
        $pages = $crawl->htmlPages();
        if ($pages === []) {
            return [
                Signal::notApplicable('missing_title'),
                Signal::notApplicable('title_length'),
                Signal::notApplicable('duplicate_titles'),
                Signal::notApplicable('missing_meta_description'),
                Signal::notApplicable('meta_description_length'),
                Signal::notApplicable('missing_h1'),
                Signal::notApplicable('multiple_h1'),
                Signal::notApplicable('heading_hierarchy'),
                Signal::notApplicable('image_alt'),
                Signal::notApplicable('content_length'),
                Signal::notApplicable('open_graph'),
                Signal::notApplicable('twitter_card'),
                Signal::notApplicable('internal_linking'),
                Signal::notApplicable('url_readability'),
            ];
        }

        $signals = [];
        $titles = [];
        $missingAlt = false;
        $sawImage = false;

        foreach ($pages as $page) {
            $signals = array_merge($signals, $this->pageSignals($page));
            if ($page->title !== null) {
                $titles[mb_strtolower($page->title)][] = $page->finalUrl;
            }
            foreach ($page->images as $image) {
                $sawImage = true;
                if ($image['alt'] === null) {
                    $missingAlt = true;
                }
            }
        }

        $duplicates = array_filter($titles, fn (array $urls): bool => count($urls) > 1);
        $signals[] = $duplicates === []
            ? Signal::pass('duplicate_titles')
            : Signal::fail('duplicate_titles', 'medium', metadata: ['titles' => $duplicates]);

        if (! $sawImage) {
            $signals[] = Signal::notApplicable('image_alt');
        } else {
            $signals[] = $missingAlt
                ? Signal::fail('image_alt', 'medium')
                : Signal::pass('image_alt');
        }

        return $signals;
    }

    /**
     * @return list<Signal>
     */
    private function pageSignals(PageSnapshot $page): array
    {
        $signals = [];
        $titleLength = $page->title === null ? 0 : mb_strlen($page->title);
        if ($page->title === null) {
            $signals[] = Signal::fail('missing_title', 'high', $page->finalUrl);
            $signals[] = Signal::notApplicable('title_length', $page->finalUrl);
        } else {
            $signals[] = Signal::pass('missing_title', $page->finalUrl);
            $signals[] = ($titleLength < 30 || $titleLength > 60)
                ? Signal::fail('title_length', 'low', $page->finalUrl, ['length' => $titleLength])
                : Signal::pass('title_length', $page->finalUrl, ['length' => $titleLength]);
        }

        $descriptionLength = $page->metaDescription === null ? 0 : mb_strlen($page->metaDescription);
        if ($page->metaDescription === null) {
            $signals[] = Signal::fail('missing_meta_description', 'medium', $page->finalUrl);
            $signals[] = Signal::notApplicable('meta_description_length', $page->finalUrl);
        } else {
            $signals[] = Signal::pass('missing_meta_description', $page->finalUrl);
            $signals[] = ($descriptionLength < 70 || $descriptionLength > 160)
                ? Signal::fail('meta_description_length', 'low', $page->finalUrl, ['length' => $descriptionLength])
                : Signal::pass('meta_description_length', $page->finalUrl, ['length' => $descriptionLength]);
        }

        $h1Count = count($page->h1);
        if ($h1Count === 0) {
            $signals[] = Signal::fail('missing_h1', 'high', $page->finalUrl);
            $signals[] = Signal::notApplicable('multiple_h1', $page->finalUrl);
        } else {
            $signals[] = Signal::pass('missing_h1', $page->finalUrl);
            $signals[] = $h1Count > 1
                ? Signal::fail('multiple_h1', 'medium', $page->finalUrl, ['count' => $h1Count])
                : Signal::pass('multiple_h1', $page->finalUrl);
        }

        $signals[] = $this->headings($page);
        $signals[] = $page->wordCount < 200
            ? Signal::fail('content_length', 'medium', $page->finalUrl, ['words' => $page->wordCount])
            : Signal::pass('content_length', $page->finalUrl, ['words' => $page->wordCount]);
        $signals[] = $page->hasOpenGraph
            ? Signal::pass('open_graph', $page->finalUrl)
            : Signal::fail('open_graph', 'low', $page->finalUrl);
        $signals[] = $page->hasTwitterCard
            ? Signal::pass('twitter_card', $page->finalUrl)
            : Signal::fail('twitter_card', 'low', $page->finalUrl);

        $otherLinks = array_filter(
            $page->internalLinks,
            fn (string $link): bool => $link !== $page->finalUrl && $link !== $page->url,
        );
        $signals[] = $otherLinks === []
            ? Signal::fail('internal_linking', 'low', $page->finalUrl)
            : Signal::pass('internal_linking', $page->finalUrl);

        $path = (string) parse_url($page->finalUrl, PHP_URL_PATH);
        $signals[] = str_contains($path, '_') || str_ends_with(strtolower($path), '.php')
            ? Signal::fail('url_readability', 'low', $page->finalUrl)
            : Signal::pass('url_readability', $page->finalUrl);

        return $signals;
    }

    private function headings(PageSnapshot $page): Signal
    {
        if (count($page->headingLevels) < 2) {
            return Signal::notApplicable('heading_hierarchy', $page->finalUrl);
        }

        $previous = 0;
        foreach ($page->headingLevels as $level) {
            if ($previous > 0 && $level > $previous + 1) {
                return Signal::fail('heading_hierarchy', 'medium', $page->finalUrl, [
                    'levels' => $page->headingLevels,
                ]);
            }
            $previous = $level;
        }

        return Signal::pass('heading_hierarchy', $page->finalUrl);
    }
}
