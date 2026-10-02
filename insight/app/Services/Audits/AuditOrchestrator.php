<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Enums\AuditStatus;
use App\Exceptions\Audits\CrawlException;
use App\Exceptions\Audits\UnsafeUrlException;
use App\Models\Audit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AuditOrchestrator
{
    public function __construct(
        private readonly SiteCrawler $crawler,
        private readonly AuditPersister $persister,
    ) {}

    public function run(Audit $audit): void
    {
        $audit->update([
            'status' => AuditStatus::Crawling,
            'started_at' => now(),
            'error_message' => null,
            'finished_at' => null,
        ]);

        try {
            $crawl = $this->crawler->crawl($audit->normalized_url);

            DB::transaction(function () use ($audit, $crawl): void {
                $this->persister->storeCrawl($audit, $crawl);
            });

            Log::info('audit.completed', ['uuid' => $audit->uuid]);
        } catch (Throwable $exception) {
            Log::error('audit.failed', [
                'uuid' => $audit->uuid,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            $message = $exception instanceof CrawlException || $exception instanceof UnsafeUrlException
                ? $exception->getMessage()
                : 'ممیزی به دلیل یک خطای داخلی متوقف شد.';

            $audit->update([
                'status' => AuditStatus::Failed,
                'error_message' => $message,
                'finished_at' => now(),
            ]);
        }
    }
}
