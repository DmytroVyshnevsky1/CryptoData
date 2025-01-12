<?php

namespace App\Enum;

enum TimePeriodEnum : string
{
    case FIVE_SEC = '5SEC';
    case HRS_1 = '1HRS';

    public function getTtl(): int
    {
        return match ($this) {
            self::FIVE_SEC => 5, // 2 seconds
            self::HRS_1 => 3600, // 1 hour (3600 seconds)
        };
    }
}
