<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum DepartmentType: int
{
    use EnumOptions, EnumValues;

    case ACADEMIC = 1;
    case ADMINISTRATIVE = 2;
    case SUPPORT = 3;

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
            self::ACADEMIC => __('general.common.academic'),
            self::ADMINISTRATIVE => __('general.common.administrative'),
            self::SUPPORT => __('general.common.support'),
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
