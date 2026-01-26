<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum SslStatus: int
{
    use EnumOptions, EnumValues;

    case DISABLED = 0;

    case ACTIVE = 1;

    public function getBadge()
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::DISABLED => 'danger',
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
            self::DISABLED => __('general.common.disabled'),
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
