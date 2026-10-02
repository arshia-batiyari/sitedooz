<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Enums\AuditStatus;
use App\Exceptions\Audits\CrawlException;
use App\Exceptions\Audits\UnsafeUrlException;
use App\Models\Audit;
use App\Services\Audits\Analysis\CategoryAnalysis;
use App\Services\Audits\Analysis\FindingDraft;
use App\Services\Audits\Analysis\PerformanceAnalyzer;
use App\Services\Audits\Analysis\ScoreCalculator;
use App\Services\Audits\Analysis\SeoAnalyzer;
use App\Services\Audits\Analysis\TechnicalAnalyzer;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AuditOrchestrator
{
    public function __construct(
        private readonly SiteCrawler $crawler,
        private readonly AuditPersister $persister,
        private readonly AuditPublisher $publisher,
        private readonly TechnicalAnalyzer $technical,
        private readonly SeoAnalyzer $seo,
        private readonly PerformanceAnalyzer $performance,
        private readonly ScoreCalculator $scores,
        private readonly ReportSummary $summary,
    ) {}

    public function run(Audit $audit): void
    {
        $audit->refresh();
        if ($audit->status !== AuditStatus::Queued) {
            return;
        }

        $audit->update([
            'status' => AuditStatus::Crawling,
            'started_at' => now(),
            'error_message' => null,
            'finished_at' => null,
        ]);

        try {
            $this->publisher->started($audit);
            $this->publisher->crawlerStarted($audit);

            $crawl = $this->crawler->crawl(
                $audit->normalized_url,
                new BroadcastingCrawlObserver($audit, $this->persister, $this->publisher),
            );
            $this->persister->finishCrawl($audit, $crawl);
            $audit->update(['status' => AuditStatus::Analyzing]);

            $analyses = array_values(array_filter([
                $this->technical->analyze($crawl),
                $this->seo->analyze($crawl),
                $this->performance->analyze($crawl),
            ]));

            $categoryScores = [];
            $findings = [];
            $total = count($analyses);
            $done = 0;
            $this->persister->rememberAnalyzerProgress($audit, 0, $total);

            foreach ($analyses as $analysis) {
                $done++;
                $findings = array_merge($findings, $this->runAnalyzer($audit, $analysis, $categoryScores, $done, $total));
            }

            $overall = $this->scores->overall($categoryScores);
            $publicFindings = array_map(fn (FindingDraft $finding): array => $finding->toPublic(), $findings);
            $report = $this->summary->summarize($publicFindings);
            $this->persister->complete($audit, $overall, $categoryScores);
            $this->publisher->completed(
                $audit,
                $overall,
                $categoryScores,
                $publicFindings,
                $report['top_issues'],
                $report['top_recommendations'],
            );
            Log::info('audit.completed', ['uuid' => $audit->uuid]);
        } catch (Throwable $exception) {
            Log::error('audit.failed', [
                'uuid' => $audit->uuid,
                'exception' => $exception::class,
            ]);

            $message = $this->publicMessage($exception);
            $audit->update([
                'status' => AuditStatus::Failed,
                'error_message' => $message,
                'finished_at' => now(),
            ]);
            $this->publisher->failed($audit, $message);
        }
    }

    /**
     * @param  array<string, int>  $categoryScores
     * @return list<FindingDraft>
     */
    private function runAnalyzer(Audit $audit, CategoryAnalysis $analysis, array &$categoryScores, int $done, int $total): array
    {
        $this->publisher->analyzerStarted($audit, $analysis->category, $analysis->label);
        foreach ($analysis->metrics as $metric) {
            $this->persister->rememberMetric($audit, $analysis->category, $metric['name'], $metric['value'], $metric['unit']);
            $this->publisher->metric($audit, $metric['name'], $metric['value'], $metric['unit']);
        }

        $found = [];
        foreach ($analysis->checks as $check) {
            if ($check->passed || $check->finding === null) {
                continue;
            }
            $this->persister->rememberFinding($audit, $check->finding);
            $this->publisher->finding($audit, $check->finding);
            $found[] = $check->finding;
        }

        $score = $this->scores->categoryScore($analysis->checks);
        if ($score !== null) {
            $categoryScores[$analysis->category] = $score;
        }

        $progress = AuditProgress::fromAnalyzers($done, $total);
        $this->persister->rememberAnalyzerProgress($audit, $done, $total);
        $overall = $this->scores->overall($categoryScores);
        $this->persister->rememberScore($audit, $overall, $categoryScores);
        $this->publisher->analyzerCompleted($audit, $analysis->category, $analysis->label, $progress);
        $this->publisher->score($audit, $overall, $categoryScores, $progress);

        return $found;
    }

    private function publicMessage(Throwable $exception): string
    {
        if ($exception instanceof CrawlException || $exception instanceof UnsafeUrlException) {
            return $exception->getMessage();
        }

        return 'ممیزی به دلیل یک خطای داخلی متوقف شد.';
    }
}
