<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum UseBalanceFurloughEnum: int
{
    use EnumValues, EnumOptions;

    case NOT_USE = 0;
    case USE = 1;

    public function getBadge()
    {
        return match ($this) {
            self::NOT_USE => 'secondary',
            self::USE => 'primary',
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
            self::NOT_USE => __('general.common.not_use'),
            self::USE => __('general.common.use'),
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
