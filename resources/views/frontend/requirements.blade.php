@extends('layouts.front')
@section('meta')
    @include('frontend.partials.meta', ['pageKey' => 'post-requirement'])
@endsection
@section('content')
    <section style="border: 1px solid gainsboro;margin-bottom: 20px;">
        <div class="container mx-auto px-4">
            <!-- Image Overlap using Negative Margin -->
            <div class="flex gap-2 text-base py-2  border-gray-100">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-black">
                    Home
                </a>

                <span class="text-gray-300">›</span>

                <a href="#" class="text-gray-600 hover:text-black">
                    Requirement
                </a>


            </div>
        </div>
    </section>
    <div class="container mx-auto py-8 px-4">

        <div class="bg-[#DFE8FF] rounded-xl p-6 md:p-10 border border-blue-50 shadow-sm mb-8">

            @if(session('success'))
                <!-- IMPROVED: text-[#2ba351] changed to text-[#2c4294] for contrast check -->
                <div
                    class="bg-green-50 text-[#2c4294] border border-green-150 p-4 mb-6 rounded-2xl text-center font-bold text-base shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div
                    class="bg-red-50 text-red-700 border border-red-150 p-4 mb-6 rounded-2xl text-center font-bold text-base shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('requirements.store') }}" method="POST" id="requirementForm">
                @csrf
                <input type="hidden" name="recaptcha_token" id="recaptcha_token_field">

                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-blue-100 pb-5 mb-6">
                    <h2 class="text-[#2c4294] text-lg md:text-xl font-extrabold uppercase tracking-wide">
                        Property I'm looking for
                    </h2>
                    <div class="flex gap-5 text-base text-gray-700 font-bold">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="purpose" value="buy" {{ old('purpose', 'buy') === 'buy' ? 'checked' : '' }} class="text-[#2c4294] focus:ring-[#2c4294]/20">
                            Buy
                        </label>
                        <!-- <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="purpose" value="rent" {{ old('purpose') === 'rent' ? 'checked' : '' }} class="text-[#2c4294] focus:ring-[#2c4294]/20">
                                    Rent
                                </label> -->
                    </div>
                </div>

                <!-- Two-Column Grid Form Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                    <!-- Left Column: Property Information -->
                    <div class="flex flex-col gap-4">
                        <!-- IMPROVED: Swapped h4 for h3 to avoid skipped heading levels -->
                        <h3 class="text-gray-800 font-extrabold text-base border-b border-blue-100 pb-2 mb-2">Property
                            Information</h3>

                        <!-- Property Type Select -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="property_type_select" class="text-xs font-bold text-gray-600 uppercase pl-1">Types
                                of Property <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <!-- Added: id="property_type_select" -->
                                <select id="property_type_select" name="property_type" required
                                    class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all appearance-none cursor-pointer">
                                    <option value="Apartment/Flats" {{ old('property_type') === 'Apartment/Flats' ? 'selected' : '' }}>Apartment/Flats</option>
                                    <option value="Independent House" {{ old('property_type') === 'Independent House' ? 'selected' : '' }}>Independent House</option>
                                    <option value="Commercial Space" {{ old('property_type') === 'Commercial Space' ? 'selected' : '' }}>Commercial Space</option>
                                    <option value="Land/Plot" {{ old('property_type') === 'Land/Plot' ? 'selected' : '' }}>
                                        Land / Plot</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                </div>
                            </div>
                            @error('property_type') <span
                            class="text-red-500 text-xs mt-1 pl-1 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Size Select -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="size_select" class="text-xs font-bold text-gray-600 uppercase pl-1">Size in Sqft
                                <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <!-- Added: id="size_select" -->
                                <select id="size_select" name="size" required
                                    class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all appearance-none cursor-pointer">
                                    <option value="any" {{ old('size') === 'any' ? 'selected' : '' }}>Any</option>
                                    <option value="500-1000" {{ old('size') === '500-1000' ? 'selected' : '' }}>500 - 1000
                                        Sqft</option>
                                    <option value="1000-1500" {{ old('size') === '1000-1500' ? 'selected' : '' }}>1000 - 1500
                                        Sqft</option>
                                    <option value="1500-2000" {{ old('size') === '1500-2000' ? 'selected' : '' }}>1500 - 2000
                                        Sqft</option>
                                    <option value="2000+" {{ old('size') === '2000+' ? 'selected' : '' }}>2000+ Sqft</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                </div>
                            </div>
                            @error('size') <span class="text-red-500 text-xs mt-1 pl-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- City Select -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="city_select" class="text-xs font-bold text-gray-600 uppercase pl-1">City <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <!-- Added: id="city_select" -->
                                <select id="city_select" name="city" required
                                    class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all appearance-none cursor-pointer">
                                    <option value="any" {{ old('city') === 'any' ? 'selected' : '' }}>Any</option>
                                    <option value="Dhaka" {{ old('city') === 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                                    <option value="Chittagong" {{ old('city') === 'Chittagong' ? 'selected' : '' }}>Chittagong
                                    </option>
                                    <option value="Sylhet" {{ old('city') === 'Sylhet' ? 'selected' : '' }}>Sylhet</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                </div>
                            </div>
                            @error('city') <span class="text-red-500 text-xs mt-1 pl-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Location Input -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="location_input"
                                class="text-xs font-bold text-gray-600 uppercase pl-1">Location</label>
                            <input type="text" id="location_input" name="location" value="{{ old('location') }}"
                                placeholder="e.g. Uttara, Banani"
                                class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all">
                            @error('location') <span
                            class="text-red-500 text-xs mt-1 pl-1 font-semibold">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <!-- Right Column: Personal Information -->

                    @php
                        $currentUser = auth()->user();
                        $defaultName = $currentUser ? $currentUser->name : '';
                        $defaultEmail = $currentUser ? $currentUser->email : '';

                        $defaultCountry = '+880';
                        $defaultPhone = '';

                        if ($currentUser && $currentUser->phone) {
                            $phoneParts = explode(' ', $currentUser->phone, 2);
                            if (count($phoneParts) === 2) {
                                $defaultCountry = $phoneParts[0];
                                $defaultPhone = $phoneParts[1];
                            } else {
                                $defaultPhone = $currentUser->phone;
                            }
                        }
                    @endphp

                    <div class="flex flex-col gap-4">
                        <!-- IMPROVED: Swapped h4 for h3 -->
                        <h3 class="text-gray-800 font-extrabold text-base border-b border-blue-100 pb-2 mb-2">Personal
                            Information</h3>

                        <!-- Name Input -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="personal_name" class="text-xs font-bold text-gray-600 uppercase pl-1">Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="personal_name" name="name" value="{{ old('name', $defaultName) }}"
                                required placeholder="Enter Your Name"
                                class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all">
                            @error('name') <span class="text-red-500 text-xs mt-1 pl-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- E-mail Input -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="personal_email"
                                class="text-xs font-bold text-gray-600 uppercase pl-1">E-mail</label>
                            <input type="email" id="personal_email" name="email" value="{{ old('email', $defaultEmail) }}"
                                required placeholder="Enter Your Email"
                                class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all">
                            @error('email') <span class="text-red-500 text-xs mt-1 pl-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mobile Number Input -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="personal_phone" class="text-xs font-bold text-gray-600 uppercase pl-1">Mobile No
                                <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <!-- Added: aria-label="Country Code" -->
                                <select name="country_code" aria-label="Country Code"
                                    class="bg-white border border-gray-150 px-3 py-4 rounded-2xl text-xs md:text-base outline-none w-24 font-bold text-gray-700">
                                    <option value="+880" {{ old('country_code', $defaultCountry) === '+880' ? 'selected' : '' }}>🇧🇩 +880</option>
                                    <option value="+1" {{ old('country_code', $defaultCountry) === '+1' ? 'selected' : '' }}>
                                        🇺🇸 +1</option>
                                    <option value="+44" {{ old('country_code') === '+44' ? 'selected' : '' }}>🇬🇧 +44
                                    </option>
                                </select>
                                <!-- Added: id="personal_phone" to match the label's for attribute -->
                                <input type="tel" id="personal_phone" name="phone" value="{{ old('phone', $defaultPhone) }}"
                                    required placeholder="Enter Your Phone Number"
                                    class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all">
                            </div>
                            @error('phone') <span class="text-red-500 text-xs mt-1 pl-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Submit Button Footer -->
                <div class="mt-8 pt-5 border-t border-blue-100">
                    <!-- IMPROVED: bg-[#2ba351] changed to bg-[#2c4294] for color contrast compliance -->
                    <button type="button" id="requirementSubmitBtn"
                        class="bg-[#2c4294] hover:bg-[#1a285a] text-white px-10 py-4 rounded-2xl font-bold text-base transition-all shadow-lg shadow-[#2c4294]/10 active:scale-[0.98] cursor-pointer">
                        Submit Requirement
                    </button>
                </div>

            </form>
        </div>

        <!-- 3. How It Works Section -->
        <div class="bg-white rounded-xl p-6 md:p-10 border border-gray-100 shadow-sm">
            <h3 class="text-center font-black text-gray-800 text-lg md:text-xl uppercase tracking-widest mb-10">
                How It Works?
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Step 1 Info Card -->
                <div class="flex gap-4">
                    <!-- IMPROVED: border-[#2ba351] and text-[#2ba351] updated to #2c4294 -->
                    <div
                        class="w-14 h-14 rounded-full border-2 border-[#2c4294] text-[#2c4294] flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-hand-pointer" aria-hidden="true"></i>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">Post your requirement</h4>
                        <!-- IMPROVED: text-gray-500 changed to text-gray-600 -->
                        <p class="text-gray-600 text-xs leading-relaxed">
                            Post your requirements at bdHousing Fully Free and get Best Property in any Prime Location.
                        </p>
                    </div>
                </div>

                <!-- Step 2 Info Card -->
                <div class="flex gap-4">
                    <!-- IMPROVED: border-[#2ba351] and text-[#2ba351] updated to #2c4294 -->
                    <div
                        class="w-14 h-14 rounded-full border-2 border-[#2c4294] text-[#2c4294] flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-share-nodes" aria-hidden="true"></i>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">We will Share</h4>
                        <!-- IMPROVED: text-gray-500 changed to text-gray-600 -->
                        <p class="text-gray-600 text-xs leading-relaxed">
                            Your requirement is shared with owners and relevant property consultants.
                        </p>
                    </div>
                </div>

                <!-- Step 3 Info Card -->
                <div class="flex gap-4">
                    <!-- IMPROVED: border-[#2ba351] and text-[#2ba351] updated to #2c4294 -->
                    <div
                        class="w-14 h-14 rounded-full border-2 border-[#2c4294] text-[#2c4294] flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-bell" aria-hidden="true"></i>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">Updates by Email</h4>
                        <!-- IMPROVED: text-gray-500 changed to text-gray-600 -->
                        <p class="text-gray-600 text-xs leading-relaxed">
                            You will be notified whenever a new property is added which meets these requirements.
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>
    @include('partials.recaptcha')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('requirementSubmitBtn');
            const form = document.getElementById('requirementForm');

            if (!btn || !form) {
                console.error('Requirement form: button or form not found');
                return;
            }

            btn.addEventListener('click', async function (e) {
                e.preventDefault();

                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = 'Please wait...';

                try {
                    const token = await window.getRecaptchaToken('post_requirement');
                    document.getElementById('recaptcha_token_field').value = token;
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

    @php
        $page = $allMeta->get('post-requirement');
    @endphp
    @if($page)
        @include('frontend.partials.page_descriptions', ['model' => $page])
    @endif
@endsection
