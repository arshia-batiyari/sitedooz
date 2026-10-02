<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Audits\Data\RuleConfig;
use App\Services\Audits\Data\Signal;
use App\Services\Audits\ScoreCalculator;
use PHPUnit\Framework\TestCase;

class ScoreCalculatorTest extends TestCase
{
    public function test_weights_inactive_rules_and_renormalizes_missing_categories(): void
    {
        $calculator = new ScoreCalculator;

        $result = $calculator->calculate(
            [
                Signal::fail('a', 'critical'),
                Signal::pass('b'),
                Signal::fail('c', 'high'),
                Signal::notApplicable('d'),
            ],
            [
                new RuleConfig('a', 'technical_seo', 'critical', 1, true),
                new RuleConfig('b', 'technical_seo', 'low', 1, true),
                new RuleConfig('c', 'technical_seo', 'high', 5, false),
                new RuleConfig('d', 'performance', 'high', 1, true),
            ],
            ['technical_seo' => 20, 'performance' => 20],
        );

        $this->assertSame(50.0, $result->categoryScores['technical_seo']);
        $this->assertArrayNotHasKey('performance', $result->categoryScores);
        $this->assertSame(50.0, $result->overall);

        $renormalized = $calculator->calculate(
            [
                Signal::fail('a', 'critical'),
                Signal::pass('b'),
                Signal::review('e'),
            ],
            [
                new RuleConfig('a', 'technical_seo', 'critical', 1, true),
                new RuleConfig('b', 'security', 'low', 1, true),
                new RuleConfig('e', 'performance', 'info', 1, true),
            ],
            ['technical_seo' => 20, 'security' => 10, 'performance' => 20],
        );

        $this->assertSame(33.33, $renormalized->overall);
        $this->assertArrayNotHasKey('performance', $renormalized->categoryScores);
    }
}
