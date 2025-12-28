<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidExpiredSignDate implements ValidationRule
{
    private $signed_date;

    public function __construct($signed_date)
    {
        $this->signed_date = $signed_date;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->isExpiredDateLargerSignDate($value, $this->signed_date)) {
            $fail(__('validation.expired_date'));
        }
    }

    private function isExpiredDateLargerSignDate(?string $expired_date, ?string $signed_date): bool
    {
        if (empty($expired_date) || empty($signed_date)) {
            return false;
        }

        return strtotime($signed_date) < strtotime($expired_date);
    }
}
