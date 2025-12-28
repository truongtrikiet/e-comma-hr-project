<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDocument implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validExtensions = ['.pdf', '.doc', '.docx', '.xls', '.xlsx', '.txt', '.ppt', '.pptx'];

        $fileTypes = explode(',', $value);

        foreach ($fileTypes as $type) {
            if (!in_array(trim($type), $validExtensions)) {
                $fail(__('validation.document'));
                return;
            }
        }
    }
}
