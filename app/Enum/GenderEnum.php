<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum GenderEnum: int
{
    use EnumOptions, EnumValues;

    case MALE = 1;
    case FEMALE = 2;

    /**
     * Get the display name of a gender based on its string value.
     *
     * @param string $value
     * @return string|null
     */
    public static function getNameByValue(string $value): ?string
    {
        $case = self::from($value);
        return match ($case) {
            self::MALE => __('general.common.male'),
            self::FEMALE => __('general.common.female'),
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
