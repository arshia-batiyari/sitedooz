<?php

declare(strict_types=1);

namespace App\Services\Audits;

final class AuditProgress
{
    public static function fromCrawl(int $pageNumber, int $pagesFound): int
    {
        if ($pageNumber < 1) {
            return 0;
        }

        $limit = max(1, (int) config('audit.crawl.max_pages'));
        $expected = max(1, min(max($pagesFound, $pageNumber), $limit));

        return (int) min(70, round(($pageNumber / $expected) * 70));
    }

    public static function fromAnalyzers(int $completed, int $total): int
    {
        if ($total < 1 || $completed < 1) {
            return 70;
        }

        return (int) min(99, round(70 + ($completed / $total) * 29));
    }
}
