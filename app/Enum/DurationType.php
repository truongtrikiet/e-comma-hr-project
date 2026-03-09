<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum DurationType: int
{
    use EnumOptions, EnumValues;

    case FULL_DAY = 1;
    case HALF_DAY = 2;

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
            self::FULL_DAY => __('general.menu.furlough_management.duration_type.full_day'),
            self::HALF_DAY => __('general.menu.furlough_management.duration_type.half_day'),
            default => null,
        };
    }

    public static function options(): array
    {
        return array_map(function ($case) {
            return [
                'value' => $case->value,
                'label' => self::getNameByValue($case->value),
            ];
        }, self::cases());
    }
}
