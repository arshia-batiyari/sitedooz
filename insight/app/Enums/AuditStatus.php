<?php

declare(strict_types=1);

namespace App\Enums;

enum AuditStatus: string
{
    case Queued = 'queued';
    case Crawling = 'crawling';
    case Analyzing = 'analyzing';
    case GeneratingReport = 'generating_report';
    case Completed = 'completed';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Queued => 'در صف',
            self::Crawling => 'در حال بررسی صفحات',
            self::Analyzing => 'در حال تحلیل',
            self::GeneratingReport => 'در حال ساخت گزارش',
            self::Completed => 'تکمیل شده',
            self::Failed => 'ناموفق',
        };
    }

    public function isFinished(): bool
    {
        return $this === self::Completed || $this === self::Failed;
    }
}
