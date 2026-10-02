<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Audits\AuditProgress;
use Tests\TestCase;

class AuditProgressTest extends TestCase
{
    public function test_progress_follows_the_page_cap_instead_of_the_whole_link_queue(): void
    {
        config(['audit.crawl.max_pages' => 8]);

        $this->assertSame(9, AuditProgress::fromCrawl(1, 40));
        $this->assertSame(70, AuditProgress::fromCrawl(8, 40));
        $this->assertSame(70, AuditProgress::fromCrawl(3, 3));
        $this->assertSame(99, AuditProgress::fromAnalyzers(3, 3));
    }
}
