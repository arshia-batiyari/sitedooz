<?php

declare(strict_types=1);

namespace App\Services\Audits;

final class ReportSummary
{
    /**
     * @param  list<array{rule_key: string, category: string, severity: string, title: string, url: ?string, recommendation: string}>  $findings
     * @return array{top_issues: list<array{category: string, severity: string, title: string, url: ?string, recommendation: string}>, top_recommendations: list<string>}
     */
    public function summarize(array $findings): array
    {
        usort($findings, function (array $left, array $right): int {
            return $this->rank($left['severity']) <=> $this->rank($right['severity']);
        });

        $top = array_slice($findings, 0, 5);
        $recommendations = [];
        foreach ($top as $issue) {
            $text = trim($issue['recommendation']);
            if ($text !== '' && ! in_array($text, $recommendations, true)) {
                $recommendations[] = $text;
            }
        }

        return [
            'top_issues' => array_map(fn (array $issue): array => [
                'category' => $issue['category'],
                'severity' => $issue['severity'],
                'title' => $issue['title'],
                'url' => $issue['url'],
                'recommendation' => $issue['recommendation'],
            ], $top),
            'top_recommendations' => array_slice($recommendations, 0, 5),
        ];
    }

    private function rank(string $severity): int
    {
        return match ($severity) {
            'critical' => 0,
            'high' => 1,
            'medium' => 2,
            'low' => 3,
            default => 4,
        };
    }
}
