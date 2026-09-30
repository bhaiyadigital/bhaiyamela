@extends('layouts.front')

@section('title', 'Login')

@section('content')

<section class="bg-white py-12 md:py-20 border-t border-gray-200 min-h-screen flex items-center justify-center">
  <div class="container mx-auto px-4 md:px-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-center">

      <!-- RIGHT SIDE: Login Form (Top on mobile, right on desktop) -->
      <div class="lg:col-span-6 order-first lg:order-last">
        <div class="bg-gradient-to-br from-[#DFE8FF] to-white p-8 md:p-12 rounded-3xl border border-blue-100 shadow-xl">
          <h2 class="text-xl font-bold text-gray-900 mb-2">
            Welcome Back
          </h2>
          <p class="text-gray-600 mb-8 text-base">Login to your account to continue</p>

          <div id="login-alert-message" class="hidden mb-6 p-4 rounded-xl text-center font-medium shadow-sm text-base"></div>

          <form id="loginForm" action="{{ route('company.login') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="redirect" value="{{ request()->query('redirect') }}">

            <div class="flex flex-col gap-2">
              <label class="text-base font-semibold text-gray-700">Email Address</label>
              <input type="email" name="email" placeholder="your@email.com" required
                class="w-full bg-white px-5 py-3.5 rounded-xl text-base outline-none border border-gray-200 shadow-sm focus:ring-2 focus:ring-[#2C4798]/30 focus:border-[#2C4798] transition-all" />
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-base font-semibold text-gray-700">Password</label>
              <input type="password" name="password" placeholder="••••••••" required
                class="w-full bg-white px-5 py-3.5 rounded-xl text-base outline-none border border-gray-200 shadow-sm focus:ring-2 focus:ring-[#2C4798]/30 focus:border-[#2C4798] transition-all" />
            </div>

            <div class="flex justify-between items-center text-base mt-2">
              <label class="flex items-center gap-2.5 text-gray-600 cursor-pointer hover:text-gray-900">
                <input type="checkbox" name="remember" class="rounded text-[#2C4798] focus:ring-[#2C4798]/30 cursor-pointer">
                <span>Remember me</span>
              </label>

              <a href="{{ route('frontend.password.request') }}" class="text-[#2C4798] font-semibold hover:underline">
                Forgot password?
              </a>
            </div>

            <button type="submit"
              class="cursor-pointer w-full bg-[#2C4798] hover:bg-[#1e2d6b] text-white py-5 rounded-2xl font-bold text-lg flex items-center justify-center gap-3 transition-all shadow-lg shadow-[#2C4798]/20 active:scale-[0.98]">
              <i class="fa-solid fa-user-plus text-base"></i>
              <span>Login Now</span>
            </button>
          </form>

          <div class="mt-8 pt-8 border-t border-blue-100">
            <p class="text-center text-gray-600 text-base">
              Don't have an account?
              <a href="{{ route('company.registration', ['redirect' => request()->query('redirect')]) }}" class="text-[#2C4798] font-bold hover:underline">Register Here</a>
            </p>
          </div>
        </div>
      </div>

      <!-- LEFT SIDE: Benefits Grid (Bottom on mobile, left on desktop) -->
      <div class="lg:col-span-6 flex flex-col gap-12 py-4 order-last lg:order-first">

        @if($loginBenefits->count() > 0)
        <div>
          <h3 class="text-xl font-bold text-gray-900 mb-10">Why Login With Us?</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($loginBenefits as $benefit)
            <div class="group flex flex-col items-center text-center p-6 rounded-2xl bg-gradient-to-br from-[#2C4798]/5 to-transparent border border-[#2C4798]/10 hover:border-[#2C4798]/30 transition-all duration-300 hover:shadow-lg hover:bg-gradient-to-br hover:from-[#2C4798]/10">
              <p class="text-gray-700 font-semibold text-base leading-relaxed">{{ $benefit->title }}</p>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        <!-- Need Help Box -->
        @if($helpline)
        <div class="mt-4 bg-[#2C4798] p-8 rounded-[2rem] text-white shadow-xl">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
              <i class="fa-solid fa-headset text-xl text-black"></i>
            </div>
            <div>
              <h5 class="text-lg font-bold">{{$helpline->title}}</h5>
              <p class="text-base">{{$helpline->short}}</p>
            </div>
          </div>
          <div class="text-xl md:text-xl font-bold mb-4 tracking-tight">{{$helpline->location}}</div>
          <div class="text-xs font-medium uppercase tracking-widest leading-loose">
            {{$helpline->description}} <br>
            <span class="normal-case tracking-normal">{{$helpline->description_1}}</span>
          </div>
        </div>
        @endif

      </div>

    </div>
  </div>
</section>
@include('partials.recaptcha')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');

    if (loginForm) {
      loginForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const alertMessage = document.getElementById('login-alert-message');
        submitBtn.disabled = true;
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Authenticating...';
        alertMessage.classList.add('hidden');

        try {
          const token = await window.getRecaptchaToken('company_registration');

          const formData = new FormData(form);
          formData.append('recaptcha_token', token);

          const response = await fetch(form.action, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            },
            body: formData
          });

          const result = await response.json();

          if (response.ok && result.success) {
            alertMessage.classList.remove('hidden', 'bg-red-100', 'text-red-700');
            alertMessage.classList.add('bg-green-100', 'text-green-700');
            alertMessage.textContent = '✓ Login successful! Redirecting...';

            setTimeout(() => {
              window.location.href = result.redirect_url;
            }, 1000);

          } else {
            let errorMsg = result.message || 'Invalid email or password.';

            if (result.errors) {
              const errorList = Object.values(result.errors).map(err => err[0]).join(' | ');
              errorMsg = errorList;
            }

            alertMessage.classList.remove('hidden', 'bg-green-100', 'text-green-700');
            alertMessage.classList.add('bg-red-100', 'text-red-700');
            alertMessage.innerHTML = errorMsg;
          }
        } catch (error) {
          console.error('Login Error:', error);
          alertMessage.classList.remove('hidden', 'bg-green-100', 'text-green-700');
          alertMessage.classList.add('bg-red-100', 'text-red-700');
          alertMessage.textContent = 'An error occurred. Please try again.';
        } finally {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }
      });
    }
  });
</script>
@endsection
