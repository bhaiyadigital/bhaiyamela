<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * Verification notice পেজ দেখাও
     */
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('frontend.auth.verify-email');
    }

    /**
     * Verification email পাঠাও - custom mailer API ব্যবহার করে
     */
    public function sendVerificationEmail(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        // Signed URL তৈরি করুন (৬০ মিনিট মেয়াদ)
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id]
        );

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-Key' => config('services.mailer.key'),
                ])
                ->post(config('services.mailer.url'), [
                    'type'    => 'verification',
                    'to'      => $user->email,
                    'subject' => 'Verify Your Email Address',
                    'data'    => [
                        'eyebrow'           => 'Email Verification',
                        'heading'           => 'Verify your email address',
                        'name'              => $user->name,
                        'message'           => 'Thanks for signing up! Please click the button below to verify your email address. This link is valid for 60 minutes.',
                        'button_text'       => 'Verify Email',
                        'verification_link' => $verificationUrl,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Mailer API failed for email verification: ' . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error('Mailer API exception for email verification: ' . $e->getMessage());
        }
    }

    /**
     * Signed URL থেকে verify করুন
     */
    public function verify(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('verification.notice')
                ->with('error', 'The verification link is invalid or has expired. Please request a new one.');
        }

        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            if (Auth::check() && Auth::id() === $user->id) {
                return redirect()->route('home')->with('success', 'Your email is already verified.');
            }
            return redirect()->route('signin')->with('success', 'Your email is already verified. Please sign in.');
        }

        $user->markEmailAsVerified();

        if (Auth::check() && Auth::id() === $user->id) {
            return redirect()->route('home')->with('success', 'Your email has been verified successfully!');
        }

        return redirect()->route('signin')->with('success', 'Your email has been verified. Please sign in.');
    }

    /**
     * Verification email আবার পাঠাও
     */
    public function resend(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('signin');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $this->sendVerificationEmail($user);

        return back()->with('success', 'A new verification link has been sent to your email address.');
    }
}
