<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum MonthEnum: int
{
    use EnumValues, EnumOptions;

    case JANUARY = 1;
    case FEBRUARY = 2;
    case MARCH = 3;
    case APRIL = 4;
    case MAY = 5;
    case JUNE = 6;
    case JULY = 7;
    case AUGUST = 8;
    case SEPTEMBER = 9;
    case OCTOBER = 10;
    case NOVEMBER = 11;
    case DECEMBER = 12;

    /**
     * Get the display name of a month based on its value.
     *
     * @param int|string|null $value
     * @return string|null
     */
    public static function getNameByValue(int|string|null $value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        $case = self::from((int) $value);
        return match ($case) {
            self::JANUARY => __('general.monthly.january'),
            self::FEBRUARY => __('general.monthly.february'),
            self::MARCH => __('general.monthly.march'),
            self::APRIL => __('general.monthly.april'),
            self::MAY => __('general.monthly.may'),
            self::JUNE => __('general.monthly.june'),
            self::JULY => __('general.monthly.july'),
            self::AUGUST => __('general.monthly.august'),
            self::SEPTEMBER => __('general.monthly.september'),
            self::OCTOBER => __('general.monthly.october'),
            self::NOVEMBER => __('general.monthly.november'),
            self::DECEMBER => __('general.monthly.december'),
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
