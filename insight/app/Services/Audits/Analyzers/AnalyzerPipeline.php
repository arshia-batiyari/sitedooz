<?php

declare(strict_types=1);

namespace App\Services\Audits\Analyzers;

use App\Services\Audits\Data\CertificateReport;
use App\Services\Audits\Data\CrawlResult;
use App\Services\Audits\Data\PageSpeedReport;
use App\Services\Audits\Data\Signal;

final class AnalyzerPipeline
{
    public function __construct(
        private readonly TechnicalSeoAnalyzer $technical,
        private readonly OnPageSeoAnalyzer $onPage,
        private readonly PerformanceAnalyzer $performance,
        private readonly MobileUxAnalyzer $mobile,
        private readonly SecurityAnalyzer $security,
        private readonly ConversionAnalyzer $conversion,
        private readonly SearchVisibilityAnalyzer $visibility,
    ) {}

    /**
     * @return list<Signal>
     */
    public function analyze(CrawlResult $crawl, PageSpeedReport $speed, CertificateReport $certificate): array
    {
        return array_merge(
            $this->technical->analyze($crawl),
            $this->onPage->analyze($crawl),
            $this->performance->analyze($speed),
            $this->mobile->analyze($crawl, $speed),
            $this->security->analyze($crawl, $certificate),
            $this->conversion->analyze($crawl),
            $this->visibility->analyze($crawl),
        );
    }
}
