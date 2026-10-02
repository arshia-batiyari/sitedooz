<?php

declare(strict_types=1);

namespace App\Services\Audits;

use App\Events\Audits\AnalyzerCompleted;
use App\Events\Audits\AnalyzerStarted;
use App\Events\Audits\AuditBroadcast;
use App\Events\Audits\AuditCompleted;
use App\Events\Audits\AuditFailed;
use App\Events\Audits\AuditStarted;
use App\Events\Audits\CrawlerStarted;
use App\Events\Audits\FindingDetected;
use App\Events\Audits\MetricCalculated;
use App\Events\Audits\PageCrawled;
use App\Events\Audits\ScoreUpdated;
use App\Models\Audit;
use App\Services\Audits\Analysis\FindingDraft;
use App\Services\Audits\Data\PageSnapshot;
use Illuminate\Support\Facades\Log;
use Throwable;

final class AuditPublisher
{
    public function started(Audit $audit): void
    {
        $this->send(new AuditStarted($audit->uuid, $audit->normalized_url));
    }

    public function crawlerStarted(Audit $audit): void
    {
        $this->send(new CrawlerStarted($audit->uuid));
    }

    public function pageCrawled(Audit $audit, PageSnapshot $page, int $pageNumber, int $pagesFound, ?string $discoveredFrom): void
    {
        $headings = array_filter($page->h1, fn (string $heading): bool => trim($heading) !== '');
        $title = $page->title;
        if (is_string($title) && mb_strlen($title) > 180) {
            $title = mb_substr($title, 0, 180);
        }

        $this->send(new PageCrawled(
            $audit->uuid,
            $page->url,
            $page->finalUrl,
            $this->pathOf($page->url),
            $pageNumber,
            $pagesFound,
            $page->statusCode,
            AuditProgress::fromCrawl($pageNumber, $pagesFound),
            $title,
            $page->depth,
            $discoveredFrom,
            count($page->internalLinks),
            array_slice($page->internalLinks, 0, 24),
            is_string($page->metaDescription) && trim($page->metaDescription) !== '',
            $headings !== [],
        ));
    }

    public function metric(Audit $audit, string $name, float|int $value, ?string $unit): void
    {
        $this->send(new MetricCalculated($audit->uuid, $name, $value, $unit));
    }

    public function analyzerStarted(Audit $audit, string $category, string $label): void
    {
        $this->send(new AnalyzerStarted($audit->uuid, $category, $label));
    }

    public function analyzerCompleted(Audit $audit, string $category, string $label, int $progress): void
    {
        $this->send(new AnalyzerCompleted($audit->uuid, $category, $label, $progress));
    }

    public function finding(Audit $audit, FindingDraft $finding): void
    {
        $this->send(new FindingDetected(
            $audit->uuid,
            $finding->ruleKey,
            $finding->category,
            $finding->severity->value,
            $finding->title,
            $finding->url,
            $finding->recommendation,
        ));
    }

    /**
     * @param  array<string, int>  $categories
     */
    public function score(Audit $audit, ?int $overall, array $categories, int $progress): void
    {
        $this->send(new ScoreUpdated($audit->uuid, $overall, $categories, $progress));
    }

    /**
     * @param  array<string, int>  $categories
     * @param  list<array<string, mixed>>  $findings
     * @param  list<array<string, mixed>>  $topIssues
     * @param  list<string>  $recommendations
     */
    public function completed(Audit $audit, ?int $overall, array $categories, array $findings, array $topIssues, array $recommendations): void
    {
        $this->send(new AuditCompleted($audit->uuid, $overall, $categories, $findings, $topIssues, $recommendations));
    }

    public function failed(Audit $audit, string $reason): void
    {
        $this->send(new AuditFailed($audit->uuid, $reason));
    }

    private function pathOf(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (! is_string($path) || $path === '' || $path === '/') {
            return '/';
        }

        return $path;
    }

    private function send(AuditBroadcast $event): void
    {
        try {
            broadcast($event);
        } catch (Throwable) {
            Log::warning('audit.broadcast_failed', ['event' => $event::class]);
        }
    }
}
