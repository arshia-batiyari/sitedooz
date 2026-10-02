<?php

declare(strict_types=1);

namespace App\Services\Audits;

final class AuditProgress
{
    public static function fromCrawl(int $pageNumber, int $pagesFound): int
    {
        if ($pageNumber < 1 || $pagesFound < 1) {
            return 0;
        }

        return (int) min(70, round(($pageNumber / $pagesFound) * 70));
    }

    public static function fromAnalyzers(int $completed, int $total): int
    {
        if ($total < 1 || $completed < 1) {
            return 70;
        }

        return (int) min(99, round(70 + ($completed / $total) * 29));
    }
}
