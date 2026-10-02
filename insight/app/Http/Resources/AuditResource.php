<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Audit */
class AuditResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'url' => $this->url,
            'normalized_url' => $this->normalized_url,
            'host' => $this->host,
            'error_message' => $this->error_message,
            'crawl_stats' => $this->crawl_stats,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
        ];
    }
}
