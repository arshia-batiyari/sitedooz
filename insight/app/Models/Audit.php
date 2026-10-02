<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AuditStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Audit extends Model
{
    protected $fillable = [
        'uuid',
        'url',
        'normalized_url',
        'host',
        'status',
        'overall_score',
        'category_scores',
        'error_message',
        'crawl_stats',
        'name',
        'mobile',
        'email',
        'business_name',
        'lead_source',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => AuditStatus::class,
            'overall_score' => 'float',
            'category_scores' => 'array',
            'crawl_stats' => 'array',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Audit $audit): void {
            if (! $audit->uuid) {
                $audit->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function pages(): HasMany
    {
        return $this->hasMany(AuditPage::class);
    }

    public function findings(): HasMany
    {
        return $this->hasMany(AuditFinding::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(AuditMetric::class);
    }
}
