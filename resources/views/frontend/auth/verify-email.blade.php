@extends('layouts.front')
@section('title', 'Verify Your Email')
@section('content')
<section class="bg-white min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md text-center bg-gradient-to-br from-[#DFE8FF] to-white p-10 rounded-3xl border border-blue-100 shadow-xl">
        <div class="w-16 h-16 mx-auto mb-6 bg-[#2C4798]/10 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-envelope-circle-check text-2xl text-[#2C4798]"></i>
        </div>

        <h2 class="text-xl font-bold text-gray-900 mb-3">Verify Your Email</h2>
        <p class="text-gray-600 mb-6 text-base">
            We've sent a verification link to <strong>{{ auth()->user()->email }}</strong>.
            Please check your inbox (and spam folder) and click the link to activate your account.
        </p>

        @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 text-sm font-medium">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-5 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 text-sm font-medium">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('verification.resend') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-[#2C4798] hover:bg-[#1e2d6b] text-white py-3.5 rounded-xl font-bold text-base transition-all">
                Resend Verification Email
            </button>
        </form>

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:underline">
                Log out
            </button>
        </form>
    </div>
</section>
@endsection
