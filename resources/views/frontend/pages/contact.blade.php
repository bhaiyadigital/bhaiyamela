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

<!-- Message Modal -->
<div id="messageModal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-all" onclick="closeMessageModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center transform scale-95 opacity-0 transition-all duration-300 mx-4"
        id="messageModalContent">
        <div id="messageModalIconBg" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i id="messageModalIcon" class="fa-solid text-4xl"></i>
        </div>
        <h2 id="messageModalTitle" class="text-2xl font-bold text-gray-800 mb-2"></h2>
        <p id="messageModalText" class="text-gray-600 mb-6 font-medium text-base"></p>
        <button type="button" onclick="closeMessageModal()"
            class="w-full bg-[#2c4294] text-white rounded-xl py-3.5 font-bold text-base hover:bg-[#1a285a] shadow-md hover:shadow-lg transition-all active:scale-95">
            বন্ধ করুন
        </button>
    </div>
</div>
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
                    showMessageModal(result.message || "ধন্যবাদ! আপনার মেসেজটি সফলভাবে পাঠানো হয়েছে।", "success");
                    form.reset();
                } else {
                    let errorMsg = result.message || "দুঃখিত, কোনো একটি সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।";
                    if (result.errors) {
                        errorMsg = Object.values(result.errors).map(err => err[0]).join("\\n");
                    }
                    showMessageModal(errorMsg, "error");
                }
            } catch (error) {
                console.error("Contact form error:", error);
                showMessageModal("দুঃখিত, কোনো একটি সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।", "error");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    }

    function showMessageModal(message, type = 'success') {
        const modal = document.getElementById('messageModal');
        const content = document.getElementById('messageModalContent');
        const iconBg = document.getElementById('messageModalIconBg');
        const icon = document.getElementById('messageModalIcon');
        const title = document.getElementById('messageModalTitle');

        document.getElementById('messageModalText').textContent = message;

        if (type === 'success') {
            iconBg.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-green-100 text-green-600';
            icon.className = 'fa-solid fa-check text-4xl';
            title.textContent = 'ধন্যবাদ!';
        } else {
            iconBg.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-red-100 text-red-600';
            icon.className = 'fa-solid fa-xmark text-4xl';
            title.textContent = 'ত্রুটি!';
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeMessageModal() {
        const modal = document.getElementById('messageModal');
        const content = document.getElementById('messageModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endpush

@endsection