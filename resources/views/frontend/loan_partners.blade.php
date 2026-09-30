@extends('layouts.front')
@section('meta')
    @include('frontend.partials.meta', ['pageKey' => 'partner'])
@endsection
@section('content')
    <section style="border: 1px solid gainsboro;margin-bottom: 20px;">
        <div class="container mx-auto px-4">
            <!-- Image Overlap using Negative Margin -->
            <div class="flex   gap-2 text-base  px-4 py-2  border-gray-100">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-black">
                    Home
                </a>

                <span class="text-gray-300">›</span>

                <a href="#" class="text-gray-600 hover:text-black">
                    Loan Partners
                </a>


            </div>
        </div>
    </section>
    <div class="container mx-auto py-8 px-4">

        <!-- 2. Header Section -->
        <div class="text-center max-w-4xl mx-auto mb-12">
            <!-- IMPROVED: text-[#2ba351] changed to text-[#1b6e35] for contrast check -->
            <h1 class="text-xl md:text-xl font-black text-[#1b6e35] uppercase tracking-wide mb-3">Our Financial Partners
            </h1>
            <p class="text-gray-500 text-xs md:text-base leading-relaxed">
                Get the best home loan rates and mortgage solutions from Bangladesh's top banks and financial institutions.
                Compare annual interest rates and apply directly through our platform.
            </p>
            <!-- IMPROVED: bg-[#2ba351] changed to bg-[#1b6e35] -->
            <div class="w-24 h-1 bg-[#1b6e35] mx-auto mt-4"></div>
        </div>

        <!-- 3. Two-Column Layout Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- ── Left: Partners Cards List (8 Columns) ── --}}
            <div class="lg:col-span-8 flex flex-col gap-6">
                @if($partners->isEmpty())
                    <div class="text-center py-16 bg-white border border-gray-100 rounded-3xl p-8 max-w-md mx-auto shadow-sm">
                        <i class="fa-solid fa-bank text-gray-300 text-5xl mb-4" aria-hidden="true"></i>
                        <!-- IMPROVED: h4 changed to h2 to satisfy heading sequence -->
                        <h2 class="font-bold text-gray-700 text-base">No Partners Found</h2>
                        <!-- IMPROVED: text-gray-400 changed to text-gray-600 -->
                        <p class="text-xs text-gray-600 mt-2">We are currently linking up with financial institutions. Stay
                            tuned!</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($partners as $partner)
                            <!-- Partner Bank Card -->
                            <div
                                class="bg-white border border-gray-100 rounded-3xl p-6 shadow-[0_10px_30px_rgba(0,0,0,0.01)] hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
                                <div>
                                    <!-- Bank Logo -->
                                    <div
                                        class="w-full flex items-center justify-center p-2 mb-4 h-24 bg-gray-50/50 rounded-2xl border border-gray-50">
                                        <img src="{{ asset('storage/' . $partner->img_path) }}"
                                            class="max-h-full max-w-full object-contain transition-transform group-hover:scale-105 duration-300"
                                            alt="Bank Logo">
                                    </div>

                                    <!-- Bank Name & Interest Rate Badge -->
                                    <div class="flex justify-between items-start gap-2 mb-3">
                                        <!-- IMPROVED: Swapped h3 for h2 for optimal heading hierarchy -->
                                        <h2 class="font-extrabold text-gray-800 text-base leading-tight">{{ $partner->title }}</h2>
                                        <!-- IMPROVED: text-[#2ba351] changed to text-[#1b6e35] -->
                                        <span
                                            class="bg-green-50 text-[#1b6e35] text-[10px] font-black px-2.5 py-1 rounded-lg flex-shrink-0 uppercase tracking-wider">
                                            {{ $partner->short }}
                                        </span>
                                    </div>

                                    <!-- Loan Details (description field) -->
                                    <div class="text-gray-500 text-xs leading-relaxed mb-4 prose max-w-none">
                                        {!! $partner->description !!}
                                    </div>
                                </div>

                                <!-- Apply Now Action Buttons -->
                                <div class="flex gap-2 mt-4 pt-3 border-t border-gray-50">
                                    <!-- IMPROVED: bg-[#2ba351] and shadow updated to #1b6e35 -->
                                    <button type="button" onclick="applyForLoan('{{ $partner->title }}')"
                                        class="w-full text-center bg-[#1b6e35] hover:bg-[#1f7035] text-white py-2.5 rounded-xl font-bold text-xs transition-all uppercase tracking-wider shadow-md shadow-[#1b6e35]/10">
                                        Apply Now
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Right: Loan Inquiry & Eligibility Form (4 Columns) ── --}}
            <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm sticky top-24">
                <!-- IMPROVED: Swapped h5 for p, and text-gray-500 to text-gray-600 for contrast compliance -->
                <p class="text-center font-bold text-gray-600 border-b border-gray-100 pb-3 mb-5 uppercase tracking-wider">
                    Home Loan Inquiry
                </p>

                <!-- Dynamic Alert Container -->
                <div id="loanAlert" class="hidden p-3 mb-4 rounded-xl text-center font-bold text-xs shadow-sm"></div>

                <!-- Standard AJAX lead form sharing the contacts table -->
                <form id="loanForm" action="{{ route('contact.owner') }}" method="POST"
                    class="flex flex-col gap-4 select-none">
                    @csrf
                    <input type="hidden" name="subject" id="loanSubjectInput" value="General Home Loan Inquiry">
                    <input type="hidden" name="recaptcha_token" id="loanRecaptchaToken">

                    <!-- Selected Bank Display -->
                    <div class="flex flex-col gap-1.5">
                        <!-- Added: for="display_preferred_bank", text-gray-500 changed to text-gray-600 -->
                        <label for="display_preferred_bank" class="text-[11px] font-bold text-gray-600 uppercase">Preferred
                            Institution</label>
                        <input type="text" id="display_preferred_bank" readonly value="General / Not Selected"
                            class="w-full bg-gray-100 border border-gray-200 px-4 py-3 rounded-xl text-xs font-bold text-gray-700 outline-none">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <!-- Added: for="lead_name", text-gray-500 changed to text-gray-600 -->
                        <label for="lead_name" class="text-[11px] font-bold text-gray-600 uppercase">Full Name <span
                                class="text-red-500">*</span></label>
                        <!-- Added: id="lead_name", focus:ring-[#1b6e35]/20 -->
                        <input type="text" name="lead_name" id="lead_name" required
                            value="{{ old('name', auth()->user() ? auth()->user()->name : '') }}"
                            placeholder="Enter Your Name"
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <!-- Added: for="lead_email", text-gray-500 changed to text-gray-600 -->
                        <label for="lead_email" class="text-[11px] font-bold text-gray-600 uppercase">Email Address</label>
                        <!-- Added: id="lead_email", focus:ring-[#1b6e35]/20 -->
                        <input type="email" name="lead_email" id="lead_email"
                            value="{{ old('email', auth()->user() ? auth()->user()->email : '') }}"
                            placeholder="Enter Your Email"
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <!-- Added: for="lead_phone", text-gray-500 changed to text-gray-600 -->
                        <label for="lead_phone" class="text-[11px] font-bold text-gray-600 uppercase">Mobile Number <span
                                class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <!-- Added: aria-label="Country Code" -->
                            <select name="country_code" aria-label="Country Code"
                                class="bg-gray-50 border border-gray-200 px-2 py-3 rounded-xl text-xs outline-none w-20 text-gray-600 font-bold">
                                <option value="+880">🇧🇩 +88</option>
                            </select>
                            <!-- Added: id="lead_phone", focus:ring-[#1b6e35]/20 -->
                            <input type="tel" name="lead_phone" id="lead_phone" required
                                placeholder="Enter Your Phone Number"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <!-- Added: for="lead_message", text-gray-500 changed to text-gray-600 -->
                        <label for="lead_message" class="text-[11px] font-bold text-gray-600 uppercase">Additional Query /
                            Details</label>
                        <!-- Added: id="lead_message", focus:ring-[#1b6e35]/20 -->
                        <textarea name="lead_message" id="lead_message" rows="3"
                            placeholder="Please write details (e.g. Loan amount, income source, etc.)" required
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all resize-none"></textarea>
                    </div>

                    <!-- IMPROVED: bg-[#2ba351] and shadow changed to #1b6e35 for contrast check -->
                    <button type="submit" id="loanSubmitBtn"
                        class="w-full bg-[#1b6e35] hover:bg-[#1f7035] text-white py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-[#1b6e35]/10 mt-2">
                        Submit Application
                    </button>
                </form>
            </div>

        </div>
    </div>
    @php
        $page = $allMeta->get('partner');
    @endphp
    @if($page)
        @include('frontend.partials.page_descriptions', ['model' => $page])
    @endif
    @include('partials.recaptcha')

    <script>
        function applyForLoan(bankName) {
            const displayInput = document.getElementById('display_preferred_bank');
            const subjectInput = document.getElementById('loanSubjectInput');
            const formInput = document.getElementById('lead_name');

            if (displayInput && subjectInput) {
                displayInput.value = bankName;
                subjectInput.value = "Home Loan Inquiry for: " + bankName;

                // সাইডবার ফর্মটিতে অটো ফোকাস করে স্ক্রল করার স্ক্রিপ্ট
                displayInput.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                if (formInput) formInput.focus();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('loanForm');

            if (form) {
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const submitBtn = document.getElementById('loanSubmitBtn');
                    const alertBox = document.getElementById('loanAlert');

                    submitBtn.disabled = true;
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1" aria-hidden="true"></i> Submitting...';

                    alertBox.classList.add('hidden');
                    alertBox.textContent = '';

                    try {
                        // reCAPTCHA token নিন
                        const token = await window.getRecaptchaToken('loan_inquiry');

                        if (!token) {
                            throw new Error('Empty reCAPTCHA token');
                        }

                        document.getElementById('loanRecaptchaToken').value = token;

                        const formData = new FormData(form);

                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const rawText = await response.text();
                        console.log("=== Raw Loan Server Response ===", rawText);

                        const result = JSON.parse(rawText);

                        if (response.ok && result.success) {
                            alertBox.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'border-red-200');
                            alertBox.classList.add('bg-green-50', 'text-[#1b6e35]', 'border-green-200');
                            alertBox.textContent = "Your loan inquiry has been submitted successfully. Our partner bank representative will contact you soon.";

                            form.reset();
                            document.getElementById('display_preferred_bank').value = "General / Not Selected";
                        } else {
                            let errorMsg = result.message || 'Something went wrong.';
                            if (result.errors) {
                                errorMsg = Object.values(result.errors).map(err => err[0]).join('<br>');
                            }

                            alertBox.classList.remove('hidden', 'bg-green-50', 'text-[#1b6e35]', 'border-green-200');
                            alertBox.classList.add('bg-red-50', 'text-red-700', 'border-red-200');
                            alertBox.innerHTML = errorMsg;
                        }
                    } catch (error) {
                        console.error('=== Loan Inquiry Error ===', error);
                        alertBox.classList.remove('hidden', 'bg-green-50', 'text-[#1b6e35]', 'border-green-200');
                        alertBox.classList.add('bg-red-50', 'text-red-700', 'border-red-200');
                        alertBox.textContent = 'Verification failed or connection error. Please try again.';
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                    }
                });
            }
        });
    </script>
@endsection