<?php

declare(strict_types=1);

namespace App\Enums;

enum FindingSeverity: string
{
    case Critical = 'critical';
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';
    case Info = 'info';

    public function label(): string
    {
        return match ($this) {
            self::Critical => 'بحرانی',
            self::High => 'بالا',
            self::Medium => 'متوسط',
            self::Low => 'کم',
            self::Info => 'اطلاعات',
        };
    }

    public function rank(): int
    {
        return match ($this) {
            self::Critical => 0,
            self::High => 1,
            self::Medium => 2,
            self::Low => 3,
            self::Info => 4,
        };
    }
}
