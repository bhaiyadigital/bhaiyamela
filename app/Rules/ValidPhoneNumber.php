<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class ValidPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $bengali = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $value = str_replace($bengali, $english, $value);
        
        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) < 7 || strlen($digits) > 15) {
            $fail('Please enter a valid phone number.');
            return;
        }

        if (preg_match('/^(\d)\1+$/', $digits)) {
            $fail('Please enter a valid phone number.');
            return;
        }

        $ascending = '0123456789';
        $descending = '9876543210';
        if (str_contains($ascending, $digits) || str_contains($descending, $digits)) {
            $fail('Please enter a valid phone number.');
            return;
        }
    }
}