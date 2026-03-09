<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum HalfDaySession: int
{
    use EnumOptions, EnumValues;

    case MORNING = 1;
    case AFTERNOON = 2;

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
            self::MORNING => __('general.menu.furlough_management.half_day_session.morning'),
            self::AFTERNOON => __('general.menu.furlough_management.half_day_session.afternoon'),
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
