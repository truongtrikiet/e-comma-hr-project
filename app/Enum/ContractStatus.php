<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum ContractStatus: int
{
    use EnumValues, EnumOptions;

    case ACTIVE = 1;
    case UNDER_ACCEPTANCE = 2;
    case CLEARED = 3;
    case COMPLETED = 4;

    public function getBadge()
    {
        return match ($this) {
            self::ACTIVE => 'primary',
            self::UNDER_ACCEPTANCE => 'warning',
            self::CLEARED => 'dark',
            self::COMPLETED => 'success',
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
            self::UNDER_ACCEPTANCE => __('general.common.under_acceptance'),
            self::CLEARED => __('general.common.cleared'),
            self::COMPLETED => __('general.common.completed'),
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
