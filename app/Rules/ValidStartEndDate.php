<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidStartEndDate implements ValidationRule
{
    private $start_date;

    public function __construct($start_date)
    {
        $this->start_date = $start_date;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($this->start_date)) {
            return;
        }

        if (! $this->isEndDateLargerStartDate($value, $this->start_date)) {
            $fail(__('validation.end_date'));
        }
    }

    private function isEndDateLargerStartDate(string $end_date, string $start_date): bool
    {
        return strtotime($start_date) <= strtotime($end_date);
    }
}
