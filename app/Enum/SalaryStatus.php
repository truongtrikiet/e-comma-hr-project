<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum SalaryStatus: int
{
    use EnumOptions, EnumValues;

    case REJECTED = 0;
    case PENDING = 1;
    case APPROVED = 2;

    public function getBadge()
    {
        return match ($this) {
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::PENDING => 'warning',
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
            self::APPROVED => __('general.common.approved'),
            self::REJECTED => __('general.common.rejected'),
            self::PENDING => __('general.common.pending'),
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
