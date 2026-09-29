<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        $result = $response->json();

        if (!($result['success'] ?? false)) {
            $fail('reCAPTCHA verification failed. Please try again.');
            return;
        }

        // ✅ Score check — v3-e 0.0 (bot) theকে 1.0 (human) porjonto score dেয়
        $score = $result['score'] ?? 0;

        if ($score < 0.5) {
            $fail('Suspicious activity detected. Please try again.');
        }
    }
}
