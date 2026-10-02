<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FindingSeverity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditFinding extends Model
{
    protected $fillable = [
        'audit_id',
        'audit_page_id',
        'rule_key',
        'category',
        'severity',
        'title',
        'description',
        'business_impact',
        'recommendation',
        'page_url',
        'metadata',
        'score_impact',
        'needs_human_review',
    ];

    protected function casts(): array
    {
        return [
            'severity' => FindingSeverity::class,
            'metadata' => 'array',
            'score_impact' => 'float',
            'needs_human_review' => 'boolean',
        ];
    }

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(AuditPage::class, 'audit_page_id');
    }
}
