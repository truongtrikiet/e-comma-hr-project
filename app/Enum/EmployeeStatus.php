<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum EmployeeStatus: int
{
    use EnumOptions, EnumValues;

    case ACTIVE = 1;
    case INACTIVE = 2;
    case RESIGNED = 3;

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
            self::INACTIVE => __('general.common.inactive'),
            self::RESIGNED => __('general.common.resigned'),
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

    public static function getBadge($statusValue): string
    {
        return match ($statusValue) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'warning',
            self::RESIGNED => 'danger',
            default => '',
        };
    }
}
