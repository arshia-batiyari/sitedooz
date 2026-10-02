<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class InsightAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('INSIGHT_ADMIN_EMAIL', '');
        $password = (string) env('INSIGHT_ADMIN_PASSWORD', '');
        if ($email === '' || $password === '') {
            return;
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => 'Insight Admin', 'password' => $password],
        );
    }
}
