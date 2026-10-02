<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\AuditRule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_deactivate_a_rule(): void
    {
        $this->get('/admin/audits')->assertRedirect('/admin/login');

        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secret-secret',
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'secret-secret',
        ])->assertRedirect('/admin/audits');

        $rule = AuditRule::query()->create([
            'key' => 'missing_meta_description',
            'category' => 'on_page_seo',
            'name' => 'توضیحات متا برای این صفحه وجود ندارد.',
            'description' => 'توضیحات متا برای این صفحه وجود ندارد.',
            'business_impact' => 'این موضوع می‌تواند روی نحوه نمایش صفحه در نتایج جستجو تأثیر بگذارد.',
            'recommendation' => 'برای این صفحه یک توضیحات متای مرتبط و جذاب بنویسید.',
            'weight' => 1,
            'severity' => 'medium',
            'is_active' => true,
        ]);

        $this->put('/admin/rules/'.$rule->id, [
            'weight' => 4,
            'severity' => 'low',
            'recommendation' => 'پیشنهاد تازه‌تری بنویسید.',
        ])->assertRedirect('/admin/rules');

        $fresh = $rule->fresh();
        $this->assertNotNull($fresh);
        $this->assertFalse($fresh->is_active);
        $this->assertSame(4, $fresh->weight);
        $this->assertSame('low', $fresh->severity);
    }
}
