<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum SettingStatus: int
{
    use EnumValues, EnumOptions;

    case ENABLED = 1;
    case DISABLED = 0;

    public function getBadge()
    {
        return match ($this) {
            self::ENABLED => 'success',
            self::DISABLED => 'warning',
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
            self::ENABLED => __('general.common.enabled'),
            self::DISABLED => __('general.common.disabled'),
            default => null,
        };
    }
}
