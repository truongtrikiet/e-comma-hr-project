<?php

namespace App\Enum;

use App\Traits\EnumValues;
use App\Traits\EnumOptions;

enum RelativeRole: int
{
    use EnumValues, EnumOptions;

    case FATHER_OR_MOTHER     = 1;
    case BROTHER_OR_SISTER    = 2;
    case HUSBAND_OR_WIFE      = 3;
    case GRANDPARENTS         = 4;
    case PARTNER              = 5;
    case RELATIVE             = 6;
    case FRIEND               = 7;
    case OTHER                = 8;

    /**
     * Get the display name of a RelativeRole based on its integer value.
     *
     * @param int $value
     * @return string|null
     */
    public static function getNameByValue(int $value): ?string
    {
        $case = self::from($value);
        return match ($case) {
            self::FATHER_OR_MOTHER => __('Father Or Mother'),
            self::BROTHER_OR_SISTER => __('Brother Or Sister'),
            self::HUSBAND_OR_WIFE => __('Husband Or Wife'),
            self::GRANDPARENTS => __('Grandparents'),
            self::PARTNER => __('Partner'),
            self::RELATIVE => __('Relative'),
            self::FRIEND => __('Friend'),
            self::OTHER => __('Other'),
            default => null,
        };
    }
}