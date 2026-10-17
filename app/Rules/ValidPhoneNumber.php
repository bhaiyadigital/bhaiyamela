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
        $value = str_replace($bengali, $english, (string)$value);
        
        // Disallow any alphabetic characters or invalid symbols
        if (preg_match('/[a-zA-Z]/', $value) || !preg_match('/^\+?[0-9\s\-()]+$/', $value)) {
            $fail('সঠিক ফোন নম্বর প্রদান করুন (কোনো বর্ণ অনুমোদিত নয়)।');
            return;
        }

        $digits = preg_replace('/\D/', '', $value);

        if (strlen($digits) < 7 || strlen($digits) > 15) {
            $fail('সঠিক ফোন নম্বর প্রদান করুন (কমপক্ষে ৭ এবং সর্বোচ্চ ১৫ ডিজিট)।');
            return;
        }

        if (preg_match('/^(\d)\1+$/', $digits)) {
            $fail('সঠিক ফোন নম্বর প্রদান করুন।');
            return;
        }

        $ascending = '0123456789';
        $descending = '9876543210';
        if (str_contains($ascending, $digits) || str_contains($descending, $digits)) {
            $fail('সঠিক ফোন নম্বর প্রদান করুন।');
            return;
        }
    }
}