<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum SslStatus: int
{
    use EnumOptions, EnumValues;

    case DISABLED = 0;

    case ACTIVE = 1;

    public static function getBadge($status): string
    {
        return match ($status) {
            self::DISABLED => 'danger',
            self::ACTIVE => 'success',
            default => ''
        };
    }
}
