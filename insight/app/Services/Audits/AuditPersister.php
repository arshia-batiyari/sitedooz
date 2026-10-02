<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Enums\AuditStatus;
use App\Models\Audit;
use App\Services\Audits\Data\CrawlResult;

final class AuditPersister
{
    public function storeCrawl(Audit $audit, CrawlResult $crawl): void
    {
        $audit->findings()->delete();
        $audit->metrics()->delete();
        $audit->pages()->delete();

        foreach ($crawl->pages as $page) {
            $robots = strtolower($page->metaRobots ?? '');
            $audit->pages()->create([
                'url' => $page->url,
                'final_url' => $page->finalUrl,
                'status_code' => $page->statusCode,
                'redirect_chain' => $page->redirectChain,
                'depth' => $page->depth,
                'indexable' => $page->isHtmlSuccess()
                    && ! str_contains($robots, 'noindex')
                    && ! str_contains($robots, 'none'),
                'title' => $page->title,
                'content_hash' => hash('sha256', ($page->title ?? '').'|'.($page->metaDescription ?? '')),
                'word_count' => $page->wordCount,
                'metadata' => [
                    'canonical' => $page->canonical,
                    'error' => $page->error,
                    'blocked_by_robots' => $page->blockedByRobots,
                    'content_type' => $page->contentType,
                ],
            ]);
        }

        $audit->update([
            'status' => AuditStatus::Completed,
            'crawl_stats' => array_merge($crawl->stats, [
                'sitemap_status' => $crawl->sitemapStatus,
                'robots_found' => $crawl->robotsFound,
            ]),
            'error_message' => null,
            'finished_at' => now(),
        ]);
    }
}
