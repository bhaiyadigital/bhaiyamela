<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class ValidPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // শুধু সংখ্যা রেখে বাকি সব (space, dash, +, parentheses) ফেলে দিন
        $digits = preg_replace('/\D/', '', $value);

        // ১. সঠিক length কিনা (যেকোনো দেশের জন্য সাধারণ রেঞ্জ)
        if (strlen($digits) < 7 || strlen($digits) > 15) {
            $fail('Please enter a valid phone number.');
            return;
        }

        // ২. সব ডিজিট একই কিনা (01111111111, 00000000000)
        if (preg_match('/^(\d)\1+$/', $digits)) {
            $fail('Please enter a valid phone number.');
            return;
        }

        // ৩. সরল ধারাবাহিক সংখ্যা কিনা (12345678, 87654321)
        $ascending = '0123456789';
        $descending = '9876543210';
        if (str_contains($ascending, $digits) || str_contains($descending, $digits)) {
            $fail('Please enter a valid phone number.');
            return;
        }
    }
}