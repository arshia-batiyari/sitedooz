<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Enums\AuditStatus;
use App\Jobs\RunAuditJob;
use App\Models\Audit;

final class AuditCreator
{
    public function __construct(private readonly UrlSafety $safety) {}

    /**
     * @param  array{url: string, name?: string|null, mobile?: string|null, email?: string|null, business_name?: string|null}  $input
     */
    public function create(array $input, bool $queue = true): Audit
    {
        $normalized = $this->safety->assertSafe($input['url']);
        $host = (string) parse_url($normalized, PHP_URL_HOST);

        $audit = Audit::query()->create([
            'url' => $input['url'],
            'normalized_url' => $normalized,
            'host' => $host,
            'status' => AuditStatus::Queued,
            'name' => $input['name'] ?? null,
            'mobile' => $input['mobile'] ?? null,
            'email' => $input['email'] ?? null,
            'business_name' => $input['business_name'] ?? null,
            'lead_source' => 'sitedooz_insight',
        ]);

        if ($queue) {
            RunAuditJob::dispatch($audit->id);
        }

        return $audit;
    }
}
