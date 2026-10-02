<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Services\Audits\Data\RuleConfig;
use App\Services\Audits\Data\ScoreResult;
use App\Services\Audits\Data\Signal;

final class ScoreCalculator
{
    /**
     * @param  list<Signal>  $signals
     * @param  list<RuleConfig>  $rules
     * @param  array<string, int>  $categoryWeights
     */
    public function calculate(array $signals, array $rules, array $categoryWeights): ScoreResult
    {
        $byKey = [];
        foreach ($signals as $signal) {
            $byKey[$signal->ruleKey][] = $signal;
        }

        $buckets = [];
        foreach ($rules as $rule) {
            if (! $rule->isActive) {
                continue;
            }
            $applicable = array_values(array_filter(
                $byKey[$rule->key] ?? [],
                fn (Signal $signal): bool => $signal->applicable && ! $signal->needsHumanReview,
            ));
            if ($applicable === []) {
                continue;
            }

            $worst = 100;
            foreach ($applicable as $signal) {
                $score = $signal->passed
                    ? 100
                    : $this->severityScore($signal->severity ?? $rule->severity);
                $worst = min($worst, $score);
            }

            $buckets[$rule->category][] = [
                'weight' => max(1, $rule->weight),
                'score' => $worst,
            ];
        }

        $categoryScores = [];
        foreach ($buckets as $category => $items) {
            $weightSum = array_sum(array_column($items, 'weight'));
            $total = 0;
            foreach ($items as $item) {
                $total += $item['score'] * $item['weight'];
            }
            $categoryScores[$category] = round($total / $weightSum, 2);
        }

        $overallWeight = 0;
        $overallTotal = 0.0;
        foreach ($categoryScores as $category => $score) {
            $weight = $categoryWeights[$category] ?? 0;
            if ($weight <= 0) {
                continue;
            }
            $overallWeight += $weight;
            $overallTotal += $score * $weight;
        }

        $overall = $overallWeight > 0 ? round($overallTotal / $overallWeight, 2) : null;

        return new ScoreResult($overall, $categoryScores);
    }

    public function severityScore(string $severity): int
    {
        return match ($severity) {
            'critical' => 0,
            'high' => 25,
            'medium' => 55,
            'low' => 80,
            'info' => 100,
            default => 55,
        };
    }
}
