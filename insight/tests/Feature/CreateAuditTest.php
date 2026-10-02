<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contracts\Audits\DnsResolver;
use App\Jobs\RunAuditJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_is_queued_and_private_urls_are_rejected(): void
    {
        Queue::fake();
        $this->app->instance(DnsResolver::class, new class implements DnsResolver
        {
            public function resolve(string $host): array
            {
                return ['93.184.216.34'];
            }
        });

        $response = $this->postJson('/api/audits', [
            'url' => 'https://example.com',
            'name' => 'نگار',
            'mobile' => '09120000000',
            'email' => 'negaar@example.com',
            'business_name' => 'نمونه',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.status', 'queued')
            ->assertJsonPath('data.normalized_url', 'https://example.com/');

        $this->assertDatabaseHas('audits', [
            'uuid' => $response->json('data.uuid'),
            'lead_source' => 'sitedooz_insight',
            'business_name' => 'نمونه',
        ]);
        Queue::assertPushed(RunAuditJob::class);

        $this->postJson('/api/audits', ['url' => 'http://127.0.0.1'])->assertStatus(422);
        $this->postJson('/api/audits', ['url' => 'not-a-url'])->assertStatus(422);
    }

    public function test_marketing_url_prefills_the_create_form(): void
    {
        $this->get('/?url=https://example.com&autostart=1')
            ->assertOk()
            ->assertSee('value="https://example.com"', false)
            ->assertSee('requestSubmit', false);
    }
}
