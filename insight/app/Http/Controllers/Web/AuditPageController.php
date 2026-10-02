<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Exceptions\Audits\UnsafeUrlException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuditRequest;
use App\Models\Audit;
use App\Services\Audits\AuditCreator;
use App\Services\Audits\ReportBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuditPageController extends Controller
{
    public function create(): View
    {
        return view('audits.create');
    }

    public function store(StoreAuditRequest $request, AuditCreator $creator): RedirectResponse
    {
        try {
            $audit = $creator->create($request->validated());
        } catch (UnsafeUrlException $exception) {
            throw ValidationException::withMessages(['url' => $exception->getMessage()]);
        }

        return redirect()->route('audits.show', $audit);
    }

    public function show(Audit $audit, ReportBuilder $reports): View
    {
        return view('audits.show', [
            'audit' => $audit,
            'report' => $reports->build($audit),
        ]);
    }
}
