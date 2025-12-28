<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRequestPaymentDate implements ValidationRule
{
    private $request_date;

    public function __construct($request_date)
    {
        $this->request_date = $request_date;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->isRenewalDateLargerExpiredDate($value, $this->request_date)) {
            $fail(__('validation.payment_date'));
        }
    }

    private function isRenewalDateLargerExpiredDate(string $request_date, string $payment_date): bool
    {
        return strtotime($payment_date) < strtotime($request_date);
    }
}
