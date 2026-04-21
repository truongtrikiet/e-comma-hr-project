<?php

namespace App\Enum;

use App\Traits\EnumOptions;
use App\Traits\EnumValues;

enum CandidateScreeningStatus: int
{
    use EnumOptions, EnumValues;

    case PASSED = 1;
    case FAILED = 0;
    case EMAIL_PENDING = 3;
    case EMAIL_SENT = 4;

    public function getBadge()
    {
        return match ($this) {
            self::PASSED => 'success',
            self::FAILED => 'danger',
            self::EMAIL_PENDING => 'warning',
            self::EMAIL_SENT => 'info',
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
                self::PASSED => __('general.common.passed'),
                self::FAILED => __('general.common.failed'),
                self::EMAIL_PENDING => __('general.common.email_pending'),
                self::EMAIL_SENT => __('general.common.email_sent'),
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
