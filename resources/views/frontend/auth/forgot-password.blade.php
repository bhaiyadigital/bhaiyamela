@extends('layouts.front')
@section('title', 'Verify Your Email')
@section('content')
<section class="bg-white min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-gradient-to-br from-[#DFE8FF] to-white p-10 rounded-3xl border border-blue-100 shadow-xl">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Forgot Password?</h2>
        <p class="text-gray-600 mb-6 text-base">Enter your email address and we'll send you a link to reset your password.</p>

        @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 text-sm font-medium">
            {{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 text-sm font-medium">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('frontend.password.email') }}" method="POST" id="forgotPasswordForm">
            @csrf
            <input type="hidden" name="recaptcha_token" id="forgotPasswordRecaptchaToken">

            <div class="flex flex-col gap-2 mb-5">
                <label class="text-sm font-semibold text-gray-700">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="your@email.com"
                    class="w-full bg-white px-5 py-3.5 rounded-xl text-base outline-none border border-gray-200 shadow-sm focus:ring-2 focus:ring-[#2C4798]/30 focus:border-[#2C4798] transition-all">
            </div>

            <button type="button" id="forgotPasswordBtn"
                class="w-full bg-[#2C4798] hover:bg-[#1e2d6b] text-white py-3.5 rounded-xl font-bold text-base transition-all">
                Send Reset Link
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('company.login') }}" class="text-sm text-[#2C4798] font-bold hover:underline">Back to Sign In</a>
        </div>
    </div>
</section>

@include('partials.recaptcha')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('forgotPasswordBtn');
        const form = document.getElementById('forgotPasswordForm');

        if (!btn || !form) return;

        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'Please wait...';

            try {
                const token = await window.getRecaptchaToken('forgot_password');
                document.getElementById('forgotPasswordRecaptchaToken').value = token;
                form.submit();
            } catch (error) {
                console.error('reCAPTCHA error:', error);
                btn.disabled = false;
                btn.innerHTML = originalText;
                alert('Verification failed. Please try again.');
            }
        });
    });
</script>
@endsection
