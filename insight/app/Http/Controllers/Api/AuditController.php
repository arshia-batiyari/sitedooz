<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Exceptions\Audits\UnsafeUrlException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuditRequest;
use App\Http\Resources\AuditPageResource;
use App\Http\Resources\AuditResource;
use App\Models\Audit;
use App\Services\Audits\AuditCreator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

class AuditController extends Controller
{
    public function store(StoreAuditRequest $request, AuditCreator $creator): JsonResponse
    {
        try {
            $audit = $creator->create($request->validated());
        } catch (UnsafeUrlException $exception) {
            throw ValidationException::withMessages(['url' => $exception->getMessage()]);
        }

        return (new AuditResource($audit))->response()->setStatusCode(201);
    }

    public function show(Audit $audit): AuditResource
    {
        return new AuditResource($audit);
    }

    public function pages(Audit $audit): AnonymousResourceCollection
    {
        return AuditPageResource::collection($audit->pages()->get());
    }
}
