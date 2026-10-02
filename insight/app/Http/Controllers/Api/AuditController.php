<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\Audits\UnsafeUrlException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuditRequest;
use App\Http\Resources\AuditPageResource;
use App\Http\Resources\AuditReportResource;
use App\Http\Resources\AuditResource;
use App\Http\Resources\FindingResource;
use App\Models\Audit;
use App\Services\Audits\AuditCreator;
use App\Services\Audits\ReportBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

class AuditController extends Controller
{
    public function store(StoreAuditRequest $request, AuditCreator $creator): JsonResponse
    {
        $audit = $this->create($request, $creator);

        return (new AuditResource($audit))->response()->setStatusCode(201);
    }

    public function show(Audit $audit): AuditReportResource
    {
        return new AuditReportResource($audit);
    }

    public function summary(Audit $audit, ReportBuilder $reports): JsonResponse
    {
        $report = $reports->build($audit);

        return response()->json([
            'data' => [
                'uuid' => $report['uuid'],
                'status' => $report['status'],
                'status_label' => $report['status_label'],
                'url' => $report['url'],
                'overall_score' => $report['overall_score'],
                'category_scores' => $report['category_scores'],
                'top_actions' => $report['top_actions'],
                'error_message' => $report['error_message'],
            ],
        ]);
    }

    public function findings(Audit $audit): AnonymousResourceCollection
    {
        return FindingResource::collection($audit->findings()->get());
    }

    public function pages(Audit $audit): AnonymousResourceCollection
    {
        return AuditPageResource::collection($audit->pages()->get());
    }

    private function create(StoreAuditRequest $request, AuditCreator $creator): Audit
    {
        try {
            $audit = $creator->create($request->validated());
        } catch (UnsafeUrlException $exception) {
            throw ValidationException::withMessages(['url' => $exception->getMessage()]);
        }

        return $audit->refresh();
    }
}
