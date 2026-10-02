<?php

declare(strict_types=1);

namespace App\Services\Audits\Data;

final class ScoreResult
{
    /**
     * @param  array<string, float>  $categoryScores
     */
    public function __construct(
        public readonly ?float $overall,
        public readonly array $categoryScores,
    ) {}
}
