<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AuditStatus;
use App\Exceptions\Audits\UnsafeUrlException;
use App\Http\Requests\StoreAuditRequest;
use App\Jobs\RunAuditJob;
use App\Models\Audit;
use App\Services\Audits\AuditCreator;
use App\Services\Audits\AuditPersister;
use App\Services\Audits\AuditSnapshot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuditLiveController extends Controller
{
    public function create(): View
    {
        return view('audits.create');
    }

    public function store(StoreAuditRequest $request, AuditCreator $creator): RedirectResponse
    {
        try {
            $audit = $creator->create($request->validated(), false);
        } catch (UnsafeUrlException $exception) {
            throw ValidationException::withMessages(['url' => $exception->getMessage()]);
        }

        return redirect()->route('audits.live', $audit);
    }

    public function show(Audit $audit, AuditSnapshot $snapshot): View
    {
        return view('audits.live', [
            'audit' => $audit,
            'snapshot' => $snapshot->for($audit),
        ]);
    }

    public function start(Audit $audit): JsonResponse
    {
        $audit->refresh();
        if ($audit->status === AuditStatus::Queued) {
            RunAuditJob::dispatch($audit->id);
        }

        return response()->json(['status' => $audit->fresh()->status->value]);
    }

    public function retry(Audit $audit, AuditPersister $persister): RedirectResponse
    {
        $audit->refresh();
        if ($audit->status === AuditStatus::Failed) {
            $persister->retry($audit);
        }

        return redirect()->route('audits.live', $audit);
    }
}
