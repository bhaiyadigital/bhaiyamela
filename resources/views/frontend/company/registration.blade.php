@extends('layouts.front')

@section('title', 'Contact Us | Bhaiya Group')

@section('content')
<section class="bg-white py-12 md:py-20 border-t border-gray-200">
  <div class="container mx-auto px-4 md:px-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">

      <!-- RIGHT SIDE: Registration Form (Top on mobile, right on desktop) -->
      <div class="lg:col-span-6 order-first lg:order-last">
        <div class="bg-[#DFE8FF] p-7 md:p-10 rounded-[2.5rem] border border-blue-50 shadow-sm">
          <h2 class="text-xl md:text-xl font-bold text-gray-800 leading-snug mb-8">
            Create Your Account
          </h2>

          <div id="alert-message" class="hidden mt-2 mb-6 p-4 bg-green-100 text-green-700 rounded-xl text-center font-medium shadow-sm">
          </div>

          <form id="registerForm" action="{{ route('store.registration') }}" method="POST" class="flex flex-col gap-5" onsubmit="handleSubmit(event)">
            @csrf
            <input type="hidden" name="redirect" value="{{ request()->query('redirect') }}">

            <!-- Success/Error Message Container -->
            <div id="alert-message" class="hidden mt-2 mb-6 p-4 rounded-xl text-center font-medium shadow-sm"></div>

            <!-- Account Type Selection -->
            <div class="bg-white px-6 py-4 rounded-2xl border border-gray-100 shadow-sm">
              <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">I am</span>
              <div class="flex gap-6 text-base text-gray-700 font-bold">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="account_type" value="individual"
                    {{ old('account_type', 'individual') === 'individual' ? 'checked' : '' }}
                    onchange="toggleAccountType('individual')"
                    class="text-[#2ba351] focus:ring-[#2ba351]/20">
                  Customer
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="radio" name="account_type" value="company"
                    {{ old('account_type') === 'company' ? 'checked' : '' }}
                    onchange="toggleAccountType('company')"
                    class="text-[#2ba351] focus:ring-[#2ba351]/20">
                  Developer/Property Owner
                </label>
              </div>
            </div>

            <!-- INDIVIDUAL FIELDS -->
            <div id="individual-fields" style="display: {{ old('account_type', 'individual') === 'individual' ? 'block' : 'none' }};">
              <!-- Full Name -->
              <input type="text" name="full_name" placeholder="Full Name *"
                value="{{ old('full_name') }}"
                class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all" />
            </div>

            <!-- COMPANY FIELDS -->
            <div id="company-fields" style="display: {{ old('account_type') === 'company' ? 'block' : 'none' }};">
              <!-- Company Name -->
              <input type="text" name="company_name" placeholder="Company Name *"
                value="{{ old('company_name') }}"
                class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all mb-5" />

              <!-- Contact Person Name -->
              <input type="text" name="contact_person_name" placeholder="Contact Person Name *"
                value="{{ old('contact_person_name') }}"
                class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all" />
            </div>

            <!-- COMMON FIELDS (Both) -->
            <!-- Email Address -->
            <input type="email" name="email" placeholder="Email Address *"
              value="{{ old('email') }}"
              class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all" />

            <!-- Phone / Mobile -->
            <div class="flex gap-2">
              <select name="country_code" class="w-28 bg-white px-4 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all">
                <option value="+880" {{ old('country_code') === '+880' ? 'selected' : '' }}>🇧🇩 +880</option>
                <option value="+1" {{ old('country_code') === '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                <option value="+44" {{ old('country_code') === '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
              </select>
              <input type="tel" name="phone" placeholder="Mobile Number *"
                value="{{ old('phone') }}"
                class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all" />
            </div>

            <!-- Password -->
            <input type="password" name="password" placeholder="Password *"
              class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all" />

            <!-- Confirm Password -->
            <input type="password" name="password_confirmation" placeholder="Confirm Password *"
              class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all" />

            <!-- Terms & Conditions -->
            <label class="flex items-start gap-2 text-base text-gray-600 px-1">
              <input type="checkbox" name="terms" class="mt-1" checked required>
              <span>I agree to <a href="#" class="text-[#2C4798] font-medium underline">Terms &amp; Conditions</a></span>
            </label>

            <!-- Submit Button -->
            <button type="submit"
              class="cursor-pointer w-full bg-[#2C4798] hover:bg-[#1e2d6b] text-white py-5 rounded-2xl font-bold text-lg flex items-center justify-center gap-3 transition-all shadow-lg shadow-[#2C4798]/20 active:scale-[0.98]">
              <i class="fa-solid fa-user-plus text-base"></i>
              Register Now
            </button>
          </form>

          <div class="mt-8 pt-8 border-t border-blue-100">
            <p class="text-center text-gray-600 text-base">
              Have an account?
              <a href="{{ route('company.login', ['redirect' => request()->query('redirect')]) }}" class="text-[#2C4798] font-bold hover:underline">Login Here</a>
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
  function toggleAccountType(type) {
    const individualFields = document.getElementById('individual-fields');
    const companyFields = document.getElementById('company-fields');

    if (type === 'individual') {
      individualFields.style.display = 'block';
      companyFields.style.display = 'none';
    } else {
      individualFields.style.display = 'none';
      companyFields.style.display = 'block';
    }
  }

  async function handleSubmit(event) {
    event.preventDefault();

    const form = event.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const alertMessage = document.getElementById('alert-message');

    submitBtn.disabled = true;
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';

    alertMessage.classList.add('hidden');
    alertMessage.textContent = '';

    try {
      const token = await window.getRecaptchaToken('company_login');

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
        alertMessage.textContent = result.message || 'Registration successful! Your account is currently under review by our admin team and will be activated shortly.';

        form.reset();
        if (result.redirect_url) {
          setTimeout(() => {
            window.location.href = result.redirect_url;
          }, 1000);
        }
      } else {
        let errorMsg = result.message || 'Something went wrong.';

        if (result.errors) {
          const errorList = Object.values(result.errors).map(err => err[0]).join('<br>');
          errorMsg = errorList;
        }

        alertMessage.classList.remove('hidden', 'bg-green-100', 'text-green-700');
        alertMessage.classList.add('bg-red-100', 'text-red-700');
        alertMessage.innerHTML = errorMsg;
      }
    } catch (error) {
      console.error('Error:', error);
      alertMessage.classList.remove('hidden', 'bg-green-100', 'text-green-700');
      alertMessage.classList.add('bg-red-100', 'text-red-700');
      alertMessage.textContent = 'An unexpected error occurred. Please try again.';
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalBtnText;
    }
  }
</script>
@endsection