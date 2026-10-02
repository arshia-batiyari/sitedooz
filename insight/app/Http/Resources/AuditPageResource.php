<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\AuditPage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AuditPage */
class AuditPageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'url' => $this->url,
            'final_url' => $this->final_url,
            'status_code' => $this->status_code,
            'depth' => $this->depth,
            'indexable' => $this->indexable,
            'title' => $this->title,
            'word_count' => $this->word_count,
            'metadata' => $this->metadata,
        ];
    }
}
