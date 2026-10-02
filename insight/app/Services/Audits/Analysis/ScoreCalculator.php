<?php

declare(strict_types=1);

namespace App\Services\Audits\Analysis;

use App\Enums\FindingSeverity;

final class ScoreCalculator
{
    /**
     * @param  list<AuditCheck>  $checks
     */
    public function categoryScore(array $checks): ?int
    {
        $scores = [];
        foreach ($checks as $check) {
            if ($check->severity === FindingSeverity::Info && ! $check->passed) {
                continue;
            }
            $scores[] = $check->passed ? 100 : $this->penalty($check->severity);
        }

        if ($scores === []) {
            return null;
        }

        return (int) round(array_sum($scores) / count($scores));
    }

    /**
     * @param  array<string, int>  $categoryScores
     */
    public function overall(array $categoryScores): ?int
    {
        $weights = config('audit.weights', []);
        $weighted = 0.0;
        $weight = 0.0;

        foreach ($categoryScores as $key => $score) {
            $item = (float) ($weights[$key] ?? 0);
            if ($item <= 0) {
                continue;
            }
            $weighted += $score * $item;
            $weight += $item;
        }

        if ($weight <= 0) {
            return null;
        }

        return (int) round($weighted / $weight);
    }

    public function penalty(FindingSeverity $severity): int
    {
        return match ($severity) {
            FindingSeverity::Critical => 0,
            FindingSeverity::High => 25,
            FindingSeverity::Medium => 55,
            FindingSeverity::Low => 80,
            FindingSeverity::Info => 100,
        };
    }
}
