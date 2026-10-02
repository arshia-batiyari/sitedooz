<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\AuditFinding;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AuditFinding */
class FindingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rule_key' => $this->rule_key,
            'category' => $this->category,
            'severity' => $this->severity->value,
            'severity_label' => $this->severity->label(),
            'title' => $this->title,
            'description' => $this->description,
            'business_impact' => $this->business_impact,
            'recommendation' => $this->recommendation,
            'page_url' => $this->page_url,
            'metadata' => $this->metadata,
            'score_impact' => $this->score_impact,
            'needs_human_review' => $this->needs_human_review,
        ];
    }
}
