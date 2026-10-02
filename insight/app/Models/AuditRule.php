<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Audits\Data\RuleConfig;
use Illuminate\Database\Eloquent\Model;

class AuditRule extends Model
{
    protected $fillable = [
        'key',
        'category',
        'name',
        'description',
        'business_impact',
        'recommendation',
        'weight',
        'severity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'weight' => 'integer',
        ];
    }

    public function toConfig(): RuleConfig
    {
        return new RuleConfig(
            key: $this->key,
            category: $this->category,
            severity: $this->severity,
            weight: (int) $this->weight,
            isActive: (bool) $this->is_active,
        );
    }
}
