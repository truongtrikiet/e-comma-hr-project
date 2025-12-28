<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExcludeCurrentDepartment implements ValidationRule
{
    public function __construct(protected $currentDepartmentId)
    {
        //
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value == $this->currentDepartmentId) {
            $fail(__('Phòng ban trực thuộc phải khác với phòng ban hiện tại.'));
        }
    }
}
