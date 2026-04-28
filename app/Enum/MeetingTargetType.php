<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum MeetingTargetType: int
{
    use EnumValues, EnumOptions;

    case USER = 1;
    case DEPARTMENT = 2;
    case SCHOOL = 3;

    /**
     * Get the display name of a status based on its value.
     *
     * @param int|string $value
     * @return string|null
     */
    public static function getNameByValue(MeetingTargetType|int|string $value): ?string
    {
        if ($value instanceof self) {
            $case = $value;
        } else {
            $case = self::from((int) $value);
        }
        return match ($case) {
            self::USER => __('general.common.user'),
            self::DEPARTMENT => __('general.common.department'),
            self::SCHOOL => __('general.common.school'),
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
