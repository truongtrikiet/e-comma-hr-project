<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum FurloughStatus: int
{
    use EnumOptions, EnumValues;

    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 0;

    public function getBadge()
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
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
            self::PENDING => __('general.menu.furlough_management.furlough_status.pending'),
            self::APPROVED => __('general.menu.furlough_management.furlough_status.approved'),
            self::REJECTED => __('general.menu.furlough_management.furlough_status.rejected'),
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
