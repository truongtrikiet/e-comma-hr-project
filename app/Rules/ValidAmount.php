<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAmount implements ValidationRule
{
    protected $min;
    protected $max;

    /**
     * Create a new rule instance.
     *
     * @param  int  $min
     * @param  int  $max
     * @return void
     */
    public function __construct($min = 0, $max = 1000000000)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_numeric($value)) {
            $fail(__('validation.integer'));
            return;
        }

        if ($value < $this->min) {
            $fail(__('validation.min.numeric', ['min' => $this->min]));
            return;
        }

        if ($value > $this->max) {
            $fail(__('validation.max.numeric', ['max' => $this->max]));
            return;
        }
    }
}
