<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Enum\DayEnum;

class DayEnumHelper
{
    /**
     * Convert Carbon date to DayEnum value (1–7)
     */
    public static function fromCarbon(Carbon $date): int
    {
        // Carbon: 0 = Sunday, 6 = Saturday
        return $date->dayOfWeek === Carbon::SUNDAY
            ? DayEnum::SUNDAY->value
            : $date->dayOfWeek + 1;
    }
}
