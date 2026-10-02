<?php

declare(strict_types=1);

namespace App\Contracts\Audits;

use App\Services\Audits\Data\CertificateReport;

interface CertificateInspector
{
    public function inspect(string $url): CertificateReport;
}
