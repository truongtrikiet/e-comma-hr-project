<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum ContractStatus: int
{
    use EnumValues, EnumOptions;

    case ACTIVE = 1;
    case UNDER_ACCEPTANCE = 2;
    case CLEARED = 3;
    case COMPLETED = 4;

    public function getBadge()
    {
        return match ($this) {
            self::ACTIVE => 'primary',
            self::UNDER_ACCEPTANCE => 'warning',
            self::CLEARED => 'dark',
            self::COMPLETED => 'success',
            default => '',
        };
    }
}
