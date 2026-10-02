<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Audit;
use App\Services\Audits\ReportBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Audit */
class AuditReportResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return app(ReportBuilder::class)->build($this->resource);
    }
}
