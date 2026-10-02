<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditMetric extends Model
{
    protected $fillable = [
        'audit_id',
        'audit_page_id',
        'source',
        'name',
        'value',
        'unit',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'float',
            'payload' => 'array',
        ];
    }

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }
}
