@extends('layouts.front')
@section('title', 'Reset Password')
@section('content')
<section class="bg-white min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-gradient-to-br from-[#DFE8FF] to-white p-10 rounded-3xl border border-blue-100 shadow-xl">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Reset Your Password</h2>
        <p class="text-gray-600 mb-6 text-base">Enter your new password below.</p>

        @if($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 text-sm font-medium">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('frontend.password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="flex flex-col gap-2 mb-4">
                <label class="text-sm font-semibold text-gray-700">Email</label>
                <input type="email" value="{{ $email }}" readonly
                    class="w-full bg-gray-100 px-5 py-3.5 rounded-xl text-base outline-none border border-gray-200 text-gray-500">
            </div>

            <div class="flex flex-col gap-2 mb-4">
                <label class="text-sm font-semibold text-gray-700">New Password</label>
                <input type="password" name="password" required
                    placeholder="••••••••"
                    class="w-full bg-white px-5 py-3.5 rounded-xl text-base outline-none border border-gray-200 shadow-sm focus:ring-2 focus:ring-[#2C4798]/30 focus:border-[#2C4798] transition-all">
            </div>

            <div class="flex flex-col gap-2 mb-6">
                <label class="text-sm font-semibold text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                    placeholder="••••••••"
                    class="w-full bg-white px-5 py-3.5 rounded-xl text-base outline-none border border-gray-200 shadow-sm focus:ring-2 focus:ring-[#2C4798]/30 focus:border-[#2C4798] transition-all">
            </div>

            <button type="submit"
                class="w-full bg-[#2C4798] hover:bg-[#1e2d6b] text-white py-3.5 rounded-xl font-bold text-base transition-all">
                Reset Password
            </button>
        </form>
    </div>
</section>
@endsection
