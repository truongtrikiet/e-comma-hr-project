<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/\s/', $value) || !$this->isValidEmail($attribute, $value)) {
            $fail(__('validation.email'));
        }
    }

    /**
     * Check the validity of an email address according to RFC and DNS standards.
     *
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    private function isValidEmail(string $attribute, string $value): bool
    {
        $email = Validator::make(
            [$attribute => $value],
            [$attribute => 'email:rfc,dns']
        );

        return !$email->fails();
    }
}
