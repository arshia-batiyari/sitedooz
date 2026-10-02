<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Models\AuditRule;
use App\Services\Audits\Data\FindingDraft;
use App\Services\Audits\Data\Signal;
use App\Services\Audits\Rules\RuleCatalog;
use Illuminate\Support\Collection;

final class RuleRegistry
{
    public function __construct(
        private readonly RuleCatalog $catalog,
        private readonly ScoreCalculator $scores,
    ) {}

    /**
     * @return Collection<int, AuditRule>
     */
    public function sync(): Collection
    {
        $models = new Collection;
        foreach ($this->catalog->all() as $definition) {
            $models->push(AuditRule::query()->firstOrCreate(
                ['key' => $definition->key],
                [
                    'category' => $definition->category,
                    'name' => $definition->name,
                    'description' => $definition->description,
                    'business_impact' => $definition->businessImpact,
                    'recommendation' => $definition->recommendation,
                    'weight' => $definition->weight,
                    'severity' => $definition->severity,
                    'is_active' => $definition->active,
                ],
            ));
        }

        return $models;
    }

    /**
     * @param  list<Signal>  $signals
     * @param  Collection<int, AuditRule>  $rules
     * @return list<FindingDraft>
     */
    public function drafts(array $signals, Collection $rules): array
    {
        $byKey = $rules->keyBy('key');
        $drafts = [];

        foreach ($signals as $signal) {
            if ($signal->passed && ! $signal->needsHumanReview) {
                continue;
            }
            if (! $signal->applicable && ! $signal->needsHumanReview) {
                continue;
            }

            $rule = $byKey->get($signal->ruleKey);
            if (! $rule instanceof AuditRule || ! $rule->is_active) {
                continue;
            }

            $severity = $signal->needsHumanReview ? 'info' : ($signal->severity ?? $rule->severity);
            $description = $rule->description;
            if ($signal->detail) {
                $description .= ' '.$signal->detail;
            }

            $drafts[] = new FindingDraft(
                ruleKey: $rule->key,
                category: $rule->category,
                severity: $severity,
                title: $rule->name,
                description: $description,
                businessImpact: $rule->business_impact,
                recommendation: $rule->recommendation,
                pageUrl: $signal->pageUrl,
                metadata: $signal->metadata,
                scoreImpact: $signal->needsHumanReview ? 0 : (100 - $this->scores->severityScore($severity)),
                needsHumanReview: $signal->needsHumanReview,
            );
        }

        return $drafts;
    }
}
