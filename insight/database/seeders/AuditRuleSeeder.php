<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\Audits\RuleRegistry;
use Illuminate\Database\Seeder;

class AuditRuleSeeder extends Seeder
{
    public function run(): void
    {
        app(RuleRegistry::class)->sync();
    }
}
