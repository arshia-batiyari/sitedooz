<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Contracts\Audits\CertificateInspector;
use App\Contracts\Audits\PageSpeedClient;
use App\Enums\AuditStatus;
use App\Exceptions\Audits\CrawlException;
use App\Exceptions\Audits\UnsafeUrlException;
use App\Models\Audit;
use App\Services\Audits\Analyzers\AnalyzerPipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AuditOrchestrator
{
    public function __construct(
        private readonly SiteCrawler $crawler,
        private readonly PageSpeedClient $pageSpeed,
        private readonly CertificateInspector $certificates,
        private readonly AnalyzerPipeline $pipeline,
        private readonly RuleRegistry $registry,
        private readonly ScoreCalculator $scores,
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
            $audit->update(['status' => AuditStatus::Analyzing]);
            $speed = $this->pageSpeed->analyze($audit->normalized_url);
            $certificate = $this->certificates->inspect($audit->normalized_url);
            $signals = $this->pipeline->analyze($crawl, $speed, $certificate);
            $audit->update(['status' => AuditStatus::GeneratingReport]);

            $rules = $this->registry->sync();
            $configs = [];
            foreach ($rules as $rule) {
                $configs[] = $rule->toConfig();
            }
            $score = $this->scores->calculate($signals, $configs, config('audit.weights'));
            $display = [];
            foreach (array_keys(config('audit.weights')) as $category) {
                $display[$category] = $score->categoryScores[$category] ?? null;
            }
            $drafts = $this->registry->drafts($signals, $rules);

            DB::transaction(function () use ($audit, $crawl, $drafts, $score, $display, $speed, $certificate): void {
                $this->persister->persist(
                    $audit,
                    $crawl,
                    $drafts,
                    $score->overall,
                    $display,
                    $speed,
                    $certificate,
                );
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
