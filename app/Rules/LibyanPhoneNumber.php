<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LibyanPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Must start with 091, 092, 093, or 094 followed by 7 digits
        if (!preg_match('/^(091|092|093|094)\d{7}$/', $value)) {
            $fail('رقم الهاتف يجب أن يبدأ بـ 091 أو 092 أو 093 أو 094 متبوعاً بـ 7 أرقام.');
        }
    }
}
