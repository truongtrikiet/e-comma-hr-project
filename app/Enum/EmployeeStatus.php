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

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => __('Đang hoạt động'),
            self::INACTIVE => __('Ngừng hoạt động'),
            self::RESIGNED => __('Đã nghỉ việc'),
        };
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
