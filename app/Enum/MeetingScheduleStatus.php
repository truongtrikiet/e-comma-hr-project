<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum MeetingScheduleStatus: int
{
    use EnumValues, EnumOptions;

    case UPCOMING = 1;
    case ONGOING = 2;
    case COMPLETED = 3;
    case CANCELLED = 4;

    public function getBadge()
    {
        return match ($this) {
            self::UPCOMING => 'info',
            self::ONGOING => 'primary',
            self::COMPLETED => 'success',
            self::CANCELLED => 'danger',
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
            self::UPCOMING => __('general.common.upcoming'),
            self::ONGOING => __('general.common.ongoing'),
            self::COMPLETED => __('general.common.completed'),
            self::CANCELLED => __('general.common.cancelled'),
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
