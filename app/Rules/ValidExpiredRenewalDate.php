<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidExpiredRenewalDate implements ValidationRule
{
    private $renewal_date;

    public function __construct($renewal_date)
    {
        $this->renewal_date = $renewal_date;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->isRenewalDateLargerExpiredDate($value, $this->renewal_date)) {
            $fail(__('validation.renewal_date'));
        }
    }

    private function isRenewalDateLargerExpiredDate(string $renewal_date, string $expired_date): bool
    {
        return strtotime($expired_date) < strtotime($renewal_date);
    }
}
