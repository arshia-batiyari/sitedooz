<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditPage extends Model
{
    protected $fillable = [
        'audit_id',
        'url',
        'final_url',
        'status_code',
        'redirect_chain',
        'depth',
        'indexable',
        'title',
        'content_hash',
        'word_count',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'redirect_chain' => 'array',
            'indexable' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }
}
