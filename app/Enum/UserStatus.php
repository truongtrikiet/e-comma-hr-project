<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum UserStatus: int
{
    use EnumValues, EnumOptions;

    case ACTIVE = 1;
    case LOCKED = 2;

    public function getBadge()
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::LOCKED => 'warning',
            default => '',
        };
    }

    /**
     * Get the display name of a status based on its string value.
     *
     * @param string $value
     * @return string|null
     */
    public static function getNameByValue(string $value): ?string
    {
        $case = self::from($value);
        return match ($case) {
            self::ACTIVE => __('general.common.active'),
            self::LOCKED => __('general.common.locked'),
            default => null,
        };
    }
}
