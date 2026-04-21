<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum IsSuitableStatus: int
{
    use EnumValues, EnumOptions;
    
    case NOT_SUITABLE = 0;
    case SUITABLE = 1;
    case MAYBE_SUITABLE = 2;

    public function getBadge()
    {
        return match ($this) {
            self::NOT_SUITABLE => 'secondary',
            self::SUITABLE => 'success',
            self::MAYBE_SUITABLE => 'warning',
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
                self::NOT_SUITABLE => __('general.common.not_suitable'),
                self::SUITABLE => __('general.common.suitable'),
                self::MAYBE_SUITABLE => __('general.common.maybe_suitable'),
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

