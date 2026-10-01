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
                    হোম
                </a>

                <span class="text-gray-300">›</span>

                <a href="#" class="text-gray-600 hover:text-black">
                    রিকোয়ারমেন্ট
                </a>


            </div>
        </div>
    </section>
    <div class="container mx-auto md:py-10 px-4">

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
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-blue-100 pb-5 mb-2 md:mb-6">
                    <h2 class="text-[#2c4294] text-lg md:text-xl font-extrabold uppercase tracking-wide">
                        আমি যে ধরণের প্রপার্টি খুঁজছি
                    </h2>
                    <div class="flex gap-5 text-base text-gray-700 font-bold">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="purpose" value="buy" {{ old('purpose', 'buy') === 'buy' ? 'checked' : '' }} class="text-[#2c4294] focus:ring-[#2c4294]/20">
                            ক্রয়
                        </label>
                    </div>
                </div>

                <!-- Two-Column Grid Form Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                    <!-- Left Column: Property Information -->
                    <div class="flex flex-col gap-4">
                        <!-- IMPROVED: Swapped h4 for h3 to avoid skipped heading levels -->
                        <h3 class="text-gray-800 font-extrabold text-base border-b border-blue-100 pb-2 mb-2">প্রপার্টির
                            তথ্য</h3>

                        <!-- Property Type Select -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="property_type_select"
                                class="text-sm font-bold text-gray-600 uppercase pl-1">প্রপার্টির ধরণ <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <!-- Added: id="property_type_select" -->
                                <select id="property_type_select" name="property_type" required
                                    class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all appearance-none cursor-pointer">
                                    <option value="Apartment/Flats" {{ old('property_type') === 'Apartment/Flats' ? 'selected' : '' }}>অ্যাপার্টমেন্ট/ফ্ল্যাট</option>
                                    <option value="Independent House" {{ old('property_type') === 'Independent House' ? 'selected' : '' }}>ইন্ডিপেন্ডেন্ট বাড়ি</option>
                                    <option value="Commercial Space" {{ old('property_type') === 'Commercial Space' ? 'selected' : '' }}>কমার্শিয়াল স্পেস</option>
                                    <option value="Land/Plot" {{ old('property_type') === 'Land/Plot' ? 'selected' : '' }}>জমি
                                        / প্লট</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                </div>
                            </div>
                            @error('property_type') <span
                            class="text-red-500 text-sm mt-1 pl-1 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <!-- Size Select -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="size_select" class="text-sm font-bold text-gray-600 uppercase pl-1">সাইজ (স্কয়ার
                                ফিট) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <!-- Added: id="size_select" -->
                                <select id="size_select" name="size" required
                                    class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all appearance-none cursor-pointer">
                                    <option value="any" {{ old('size') === 'any' ? 'selected' : '' }}>যেকোনো</option>
                                    <option value="500-1000" {{ old('size') === '500-1000' ? 'selected' : '' }}>৫০০ - ১০০০
                                    </option>
                                    <option value="1000-1500" {{ old('size') === '1000-1500' ? 'selected' : '' }}>১০০০ - ১৫০০
                                    </option>
                                    <option value="1500-2000" {{ old('size') === '1500-2000' ? 'selected' : '' }}>১৫০০ - ২০০০
                                    </option>
                                    <option value="2000+" {{ old('size') === '2000+' ? 'selected' : '' }}>২০০০+</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                </div>
                            </div>
                            @error('size') <span class="text-red-500 text-sm mt-1 pl-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- City Select -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="city_select" class="text-sm font-bold text-gray-600 uppercase pl-1">শহর <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <!-- Added: id="city_select" -->
                                <select id="city_select" name="city" required
                                    class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all appearance-none cursor-pointer">
                                    <option value="any" {{ old('city') === 'any' ? 'selected' : '' }}>যেকোনো</option>
                                    <option value="Dhaka" {{ old('city') === 'Dhaka' ? 'selected' : '' }}>ঢাকা</option>
                                    <option value="Chittagong" {{ old('city') === 'Chittagong' ? 'selected' : '' }}>চট্টগ্রাম
                                    </option>
                                    <option value="Sylhet" {{ old('city') === 'Sylhet' ? 'selected' : '' }}>সিলেট</option>
                                </select>
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                </div>
                            </div>
                            @error('city') <span class="text-red-500 text-sm mt-1 pl-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Location Input -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="location_input"
                                class="text-sm font-bold text-gray-600 uppercase pl-1">লোকেশন</label>
                            <input type="text" id="location_input" name="location" value="{{ old('location') }}"
                                placeholder="যেমন: উত্তরা, বনানী"
                                class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all">
                            @error('location') <span
                            class="text-red-500 text-sm mt-1 pl-1 font-semibold">{{ $message }}</span> @enderror
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
                        <h3 class="text-gray-800 font-extrabold text-base border-b border-blue-100 pb-2 mb-2">ব্যক্তিগত তথ্য
                        </h3>

                        <!-- Name Input -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="personal_name" class="text-sm font-bold text-gray-600 uppercase pl-1">নাম <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="personal_name" name="name" value="{{ old('name', $defaultName) }}"
                                required placeholder="আপনার নাম লিখুন"
                                class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all">
                            @error('name') <span class="text-red-500 text-sm mt-1 pl-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- E-mail Input -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="personal_email" class="text-sm font-bold text-gray-600 uppercase pl-1">ইমেইল</label>
                            <input type="email" id="personal_email" name="email" value="{{ old('email', $defaultEmail) }}"
                                required placeholder="আপনার ইমেইল লিখুন"
                                class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all">
                            @error('email') <span class="text-red-500 text-sm mt-1 pl-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mobile Number Input -->
                        <div class="flex flex-col gap-1.5">
                            <!-- IMPROVED: text-gray-500 changed to text-gray-600 and added for attribute -->
                            <label for="personal_phone" class="text-sm font-bold text-gray-600 uppercase pl-1">মোবাইল নম্বর
                                <span class="text-red-500">*</span></label>
                            <!-- Added: id="personal_phone" to match the label's for attribute -->
                            <input type="tel" id="personal_phone" name="phone" value="{{ old('phone', $defaultPhone) }}"
                                required placeholder="আপনার ফোন নম্বর লিখুন"
                                class="w-full bg-white px-5 py-4 rounded-2xl border border-gray-150 outline-none text-base font-bold text-gray-700 focus:ring-2 focus:ring-[#2C4798]/20 transition-all">
                            @error('phone') <span class="text-red-500 text-sm mt-1 pl-1 font-semibold">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Submit Button Footer -->
                <div class="mt-8 pt-5 border-t border-blue-100">
                    <!-- IMPROVED: bg-[#2ba351] changed to bg-[#2c4294] for color contrast compliance -->
                    <button type="button" id="requirementSubmitBtn"
                        class="bg-[#2c4294] hover:bg-[#1a285a] text-white px-10 py-4 rounded-2xl font-bold text-base transition-all shadow-lg shadow-[#2c4294]/10 active:scale-[0.98] cursor-pointer">
                        রিকোয়ারমেন্ট সাবমিট করুন
                    </button>
                </div>

            </form>
        </div>

        <!-- 3. How It Works Section -->
        <div class="bg-white rounded-xl p-6 mb-4 md:p-10 border border-gray-100 shadow-sm">
            <h3 class="text-center font-black text-gray-800 text-lg md:text-xl uppercase tracking-widest mb-4 md:mb-10">
                এটি কীভাবে কাজ করে?
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
                        <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">আপনার রিকোয়ারমেন্ট পোস্ট করুন
                        </h4>
                        <!-- IMPROVED: text-gray-500 changed to text-gray-600 -->
                        <p class="text-gray-600 text-sm leading-relaxed">
                            সম্পূর্ণ বিনামূল্যে আপনার রিকোয়ারমেন্ট পোস্ট করুন এবং সেরা লোকেশনে চমৎকার প্রপার্টি খুঁজে নিন।
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
                        <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">আমরা শেয়ার করব</h4>
                        <!-- IMPROVED: text-gray-500 changed to text-gray-600 -->
                        <p class="text-gray-600 text-sm leading-relaxed">
                            আপনার রিকোয়ারমেন্ট প্রপার্টি মালিক এবং রিয়েল এস্টেট কনসালটেন্টদের সাথে শেয়ার করা হবে।
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
                        <h4 class="text-gray-800 font-extrabold text-base mb-1.5 uppercase">ইমেইলে আপডেট</h4>
                        <!-- IMPROVED: text-gray-500 changed to text-gray-600 -->
                        <p class="text-gray-600 text-sm leading-relaxed">
                            আপনার পছন্দের রিকোয়ারমেন্টের সাথে মিলে যাওয়া নতুন কোনো প্রপার্টি যুক্ত হলে আপনাকে ইমেইলের
                            মাধ্যমে জানানো হবে।
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
                btn.innerHTML = 'অপেক্ষা করুন...';

                try {
                    const token = await window.getRecaptchaToken('post_requirement');
                    document.getElementById('recaptcha_token_field').value = token;
                    form.submit();
                } catch (error) {
                    console.error('reCAPTCHA error:', error);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    alert('ভেরিফিকেশন ব্যর্থ হয়েছে। আবার চেষ্টা করুন।');
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