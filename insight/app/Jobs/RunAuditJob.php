<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Audit;
use App\Services\Audits\AuditOrchestrator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RunAuditJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 120;

    public function __construct(public int $auditId) {}

    public function handle(AuditOrchestrator $orchestrator): void
    {
        $audit = Audit::query()->find($this->auditId);
        if ($audit === null) {
            return;
        }

        $orchestrator->run($audit);
    }
}
