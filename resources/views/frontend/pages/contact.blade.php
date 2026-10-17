@extends('layouts.front')

@section('meta')
@include('frontend.partials.meta', ['pageKey' => 'contact'])
@endsection

@section('content')

<!-- MAIN SECTION -->
<section class="py-10">
    <div class="container mx-auto px-4 md:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">

            <!-- LEFT SIDE: Contact Information (No Background) -->
            <div class="lg:col-span-6 flex flex-col gap-10 py-4 mt-10">

                <!-- 2. Head Office -->
                <div class="flex items-start gap-5">
                    <div
                        class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 border border-gray-200">
                        <i class="fa-solid fa-building text-lg text-gray-700"></i>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="text-xl font-bold text-gray-900">প্রধান কার্যালয়</h4>
                        <p class="text-gray-600 text-base md:text-base">
                            {{ $setting->address ?? 'সন্দ্বীপ ভবন, ২৮/এ-৩, টয়েনবি সার্কুলার রোড, মতিঝিল বা/এ, ঢাকা' }}
                        </p>
                    </div>
                </div>

                <!-- 3. Phone Number -->
                <div class="flex items-start gap-5">
                    <div
                        class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 border border-gray-200">
                        <i class="fa-solid fa-phone text-lg text-gray-700"></i>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="text-xl font-bold text-gray-900">ফোন নম্বর</h4>
                        <p class="text-gray-600 text-base md:text-base">
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', strip_tags($setting->phone ?? '+8801938886333')) }}" class="hover:text-[#2c4294] transition-colors">
                                {!! $setting->phone ?? '+880 1938-886333' !!}
                            </a>
                        </p>
                    </div>
                </div>

                <!-- 4. Email Us -->
                <div class="flex items-start gap-5">
                    <div
                        class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 border border-gray-200">
                        <i class="fa-solid fa-envelope text-lg text-gray-700"></i>
                    </div>
                    <div class="flex flex-col">
                        <h4 class="text-xl font-bold text-gray-900">ইমেইল করুন</h4>
                        <p class="text-gray-600 text-base md:text-base">
                            <a href="mailto:{{ strip_tags($setting->email ?? 'info@domain.com') }}" class="hover:text-[#2c4294] transition-colors">
                                {{ $setting->email ?? '-' }}
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Need Help Box (Interior Card) -->
                <div class="mt-4 bg-[#2C4798] p-8 rounded-[2rem] text-white shadow-xl">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-headset text-xl text-black"></i>
                        </div>
                        <div>
                            <h5 class="text-lg font-bold">সাহায্য প্রয়োজন?</h5>
                            <p class=" text-base">আমাদের টিমকে কল করুন</p>
                        </div>
                    </div>

                    <div class="text-xl md:text-xl font-bold mb-4 tracking-tight">
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', strip_tags($setting->phone ?? '+8801938886333')) }}" class="hover:text-gray-200 transition-colors">
                            {{ $setting->phone ?? '-' }}
                        </a>
                    </div>

                    <div class="text-xs font-medium uppercase tracking-widest leading-loose">
                        শনিবার - শুক্রবার <br>
                        <span class=" normal-case tracking-normal">সকাল ০৯:০০ - রাত ১০:০০</span>
                    </div>
                </div>

            </div>

            <!-- RIGHT SIDE: Interest Form -->
            <div class="lg:col-span-6 md:mt-8">
                <div class="bg-[#DFE8FF] p-7 md:p-10 rounded-[2.5rem] border border-blue-50 shadow-sm">
                    <h2 class="text-xl md:text-xl font-bold text-gray-800 leading-snug mb-8">
                        আমাদের সাথে যোগাযোগ করুন
                    </h2>

                    @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl text-base">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form id="contactForm" action="{{ route('contact.store') }}" method="POST"
                        class="flex flex-col gap-5">
                        @csrf
                        <input type="text" name="name" placeholder="আপনার নাম (বাধ্যতামূলক)" required
                            class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all"
                            value="{{ old('name') }}" />

                        <input type="email" name="email" placeholder="ইমেইল ঠিকানা" required
                            class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all"
                            value="{{ old('email') }}" />

                        <input type="tel" name="phone" placeholder="ফোন নম্বর (বাধ্যতামূলক)" required
                            class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all"
                            value="{{ old('phone') }}" />

                        <textarea name="message" placeholder="আপনি কী জানতে চান? (ঐচ্ছিক)" rows="4"
                            class="w-full bg-white px-6 py-4 rounded-xl text-base outline-none border border-transparent shadow-sm focus:ring-2 focus:ring-[#2C4798]/20 transition-all resize-none">{{ old('message') }}</textarea>

                        <button type="submit"
                            class="cursor-pointer w-full bg-[#2C4798] hover:bg-[#1e2d6b] text-white py-5 rounded-2xl font-bold text-lg flex items-center justify-center gap-3 transition-all shadow-lg shadow-[#2C4798]/20 active:scale-[0.98]">
                            <i class="fa-solid fa-paper-plane text-base"></i>
                            সাবমিট করুন
                        </button>

                        <div class="text-[11px] text-gray-700 text-center leading-relaxed mt-2.5 flex items-center justify-center gap-1.5 flex-wrap font-medium">
                            <i class="fa-solid fa-shield-halved text-[#1e2d6b] text-[12px]"></i>
                            <span>এই সাইটটি reCAPTCHA দ্বারা সুরক্ষিত এবং গুগলের</span>
                            <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" class="text-[#1e2d6b] font-semibold underline hover:text-black">গোপনীয়তা নীতি</a>
                            <span>ও</span>
                            <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer" class="text-[#1e2d6b] font-semibold underline hover:text-black">সেবার শর্তাবলী</a>
                            <span>প্রযোজ্য।</span>
                        </div>
                    </form>

                    @if (session('success'))
                    <div class="mt-6 p-4 bg-green-100 text-green-700 rounded-xl text-center font-medium shadow-sm">
                        {{ session('success') }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Common Alert Modal Component -->
<x-modal-alert id="contactAlertModal" />

@include('partials.recaptcha')

@push('scripts')
<script>
    const form = document.getElementById('contactForm');
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> সাবমিট হচ্ছে...';

            try {
                const token = await window.getRecaptchaToken('contact');
                const formData = new FormData(form);
                formData.append('recaptcha_token', token);

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    if (typeof fbq !== 'undefined') fbq('track', 'Lead');
                    const successMsg = result.message || "Your information has been submitted successfully. Our team will contact you soon.\nWe appreciate your interest and look forward to helping you find your dream property!";
                    showAlertModal(successMsg, "success", "Thank you for your interest!", "contactAlertModal");
                    form.reset();
                } else {
                    let errorMsg = result.message || "দুঃখিত, কোনো একটি সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।";
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).map(err => err[0]).join("\n");
                    }
                    showAlertModal(errorMsg, "error", "ত্রুটি!", "contactAlertModal");
                }
            } catch (error) {
                console.error("Contact form error:", error);
                showAlertModal("দুঃখিত, কোনো একটি সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।", "error", "ত্রুটি!", "contactAlertModal");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }
</script>

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endpush

@endsection