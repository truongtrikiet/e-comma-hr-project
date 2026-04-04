<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum IsPaid: int
{
    use EnumValues, EnumOptions;

    case PAID = 1;
    case UNPAID = 0;

    public function getBadge()
    {
        return match ($this) {
            self::PAID => 'success',
            self::UNPAID => 'danger',
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
            self::PAID => __('general.common.paid'),
            self::UNPAID => __('general.common.unpaid'),
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
