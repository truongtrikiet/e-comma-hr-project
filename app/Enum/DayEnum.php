<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum DayEnum: int
{
    use EnumOptions, EnumValues;

    case SUNDAY = 1;
    case MONDAY = 2;
    case TUESDAY = 3;
    case WEDNESDAY = 4;
    case THURSDAY = 5;
    case FRIDAY = 6;
    case SATURDAY = 7;

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
            self::SUNDAY => __('general.common.sunday'),
            self::MONDAY => __('general.common.monday'),
            self::TUESDAY => __('general.common.tuesday'),
            self::WEDNESDAY => __('general.common.wednesday'),
            self::THURSDAY => __('general.common.thursday'),
            self::FRIDAY => __('general.common.friday'),
            self::SATURDAY => __('general.common.saturday'),
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
