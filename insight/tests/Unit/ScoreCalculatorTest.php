<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\FindingSeverity;
use App\Services\Audits\Analysis\AuditCheck;
use App\Services\Audits\Analysis\FindingDraft;
use App\Services\Audits\Analysis\ScoreCalculator;
use Tests\TestCase;

class ScoreCalculatorTest extends TestCase
{
    public function test_scores_use_real_checks_and_skip_missing_categories(): void
    {
        $scores = new ScoreCalculator;
        $technical = $scores->categoryScore([
            AuditCheck::pass('technical.https'),
            AuditCheck::fail($this->finding('technical.robots', FindingSeverity::Medium)),
        ]);
        $seo = $scores->categoryScore([
            AuditCheck::fail($this->finding('seo.meta_description', FindingSeverity::High)),
        ]);

        $this->assertSame(78, $technical);
        $this->assertSame(25, $seo);
        $this->assertSame(53, $scores->overall([
            'technical' => $technical,
            'seo' => $seo,
        ]));
        $this->assertSame(78, $scores->overall(['technical' => $technical]));
    }

    private function finding(string $key, FindingSeverity $severity): FindingDraft
    {
        return new FindingDraft($key, 'technical', $severity, 'عنوان', 'توضیح', 'اثر', 'پیشنهاد', 'https://example.com/');
    }
}
