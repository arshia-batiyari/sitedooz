<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AuditStatus;
use App\Http\Controllers\Controller;
use App\Jobs\RunAuditJob;
use App\Models\Audit;
use App\Services\Audits\ReportBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuditAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.audits.index', [
            'audits' => Audit::query()->latest()->paginate(20),
        ]);
    }

    public function show(Audit $audit, ReportBuilder $reports): View
    {
        return view('admin.audits.show', [
            'audit' => $audit,
            'report' => $reports->build($audit),
        ]);
    }

    public function retry(Audit $audit): RedirectResponse
    {
        if ($audit->status !== AuditStatus::Failed) {
            return back()->with('status', 'فقط ممیزی ناموفق دوباره در صف قرار می‌گیرد.');
        }

        DB::transaction(function () use ($audit): void {
            $audit->findings()->delete();
            $audit->metrics()->delete();
            $audit->pages()->delete();
            $audit->update([
                'status' => AuditStatus::Queued,
                'error_message' => null,
                'overall_score' => null,
                'category_scores' => null,
                'crawl_stats' => null,
                'started_at' => null,
                'finished_at' => null,
            ]);
        });

        RunAuditJob::dispatch($audit->id);

        return redirect()->route('admin.audits.show', $audit);
    }
}
