<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum ResetTypeEnum: int
{
    use EnumValues, EnumOptions;

    case NONE = 0;
    case MONTHLY = 1;
    case YEARLY = 2;

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
            self::NONE => __('general.common.none'),
            self::MONTHLY => __('general.common.monthly'),
            self::YEARLY => __('general.common.yearly'),
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
