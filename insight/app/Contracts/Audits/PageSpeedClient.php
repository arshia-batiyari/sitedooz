<?php

declare(strict_types=1);

namespace App\Contracts\Audits;

use App\Services\Audits\Data\PageSpeedReport;

interface PageSpeedClient
{
    public function analyze(string $url): PageSpeedReport;
}
