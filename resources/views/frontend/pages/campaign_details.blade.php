@extends('layouts.front')

@section('meta')
    @php
        $preloadBannerImage = $campaign->img_path ? asset('storage/' . $campaign->img_path) : asset('assets/images/placeholder.webp');
    @endphp
    <link rel="preload" as="image" href="{{ $preloadBannerImage }}" fetchpriority="high">
    @include('frontend.partials.meta', [
        'pageKey' => 'campaign',
        'title' => ($campaign->meta_title ?? $campaign->title) . ' | Bhaiya Mela',
        'description' => $campaign->meta_description ?? $campaign->short ?? Str::limit(strip_tags($campaign->description), 150),
        'keywords' => $campaign->meta_keywords ?? 'campaign, bhaiya mela, real estate expo',
        'image' => $preloadBannerImage
    ])
@endsection

@php
    $start = $campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date) : null;
    $end = $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date) : null;
    $now = now();

    $isOngoing = ($start && $end && $now->between($start, $end)) || (!$end && $start && $now->gte($start));
    $isUpcoming = $start && $now->lt($start);
    $isCompleted = $end && $now->gt($end);

    $bnDigits = ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯'];
    $bnMonths = [
        'January' => 'জানুয়ারি',
        'February' => 'ফেব্রুয়ারি',
        'March' => 'মার্চ',
        'April' => 'এপ্রিল',
        'May' => 'মে',
        'June' => 'জুন',
        'July' => 'জুলাই',
        'August' => 'আগস্ট',
        'September' => 'সেপ্টেম্বর',
        'October' => 'অক্টোবর',
        'November' => 'নভেম্বর',
        'December' => 'ডিসেম্বর',
    ];
    $formatBnTime = function($date) use ($bnDigits) {
        if (!$date) return '';
        $c = \Carbon\Carbon::parse($date);
        $h24 = (int)$c->format('G');
        $period = 'রাত';
        if ($h24 >= 4 && $h24 < 6) {
            $period = 'ভোর';
        } elseif ($h24 >= 6 && $h24 < 12) {
            $period = 'সকাল';
        } elseif ($h24 >= 12 && $h24 < 15) {
            $period = 'দুপুর';
        } elseif ($h24 >= 15 && $h24 < 18) {
            $period = 'বিকাল';
        } elseif ($h24 >= 18 && $h24 < 20) {
            $period = 'সন্ধ্যা';
        }

        $timeStr = $c->format('h:i');
        $bnTimeStr = strtr($timeStr, $bnDigits);
        return "{$period} {$bnTimeStr} টা";
    };

    $formatBnDate = function($date) use ($bnDigits, $bnMonths) {
        if (!$date) return '';
        $c = \Carbon\Carbon::parse($date);
        $d = strtr($c->format('d'), $bnDigits);
        $m = $bnMonths[$c->format('F')] ?? $c->format('M');
        $y = strtr($c->format('Y'), $bnDigits);
        return "{$d} {$m}, {$y}";
    };

    $formatBnDateTime = function($date) use ($formatBnDate, $formatBnTime) {
        if (!$date) return '';
        $datePart = $formatBnDate($date);
        $timePart = $formatBnTime($date);
        return "{$datePart} ({$timePart})";
    };

    $bannerImage = $campaign->img_path ? asset('storage/' . $campaign->img_path) : asset('assets/images/placeholder.webp');
    $projCount = $projects->count();
    $bnProjCount = strtr((string)$projCount, $bnDigits);
@endphp

@section('content')

    <!-- BREADCRUMB BAR -->
    <section style="border: 1px solid gainsboro;">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-2 text-base py-2 border-gray-100">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-black">
                    Home
                </a>
                <span class="text-gray-300">›</span>
                <a href="{{ url('/') }}#campaign-section" class="text-gray-600 hover:text-black">
                    Events
                </a>
                <span class="text-gray-300">›</span>
                <span class="text-black font-semibold truncate">
                    {{ $campaign->title }}
                </span>
            </div>
        </div>
    </section>

    <!-- HERO / BANNER SECTION -->
    <section class="relative overflow-hidden py-10 md:py-16 bg-gray-50 text-gray-900">
        <!-- Campaign thumbnail as blurred background with reduced opacity -->
        <img src="{{ $bannerImage }}" alt="" aria-hidden="true" loading="lazy" decoding="async"
            class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none"
            style="filter: blur(8px); opacity: 0.50; transform: scale(1.08);">
        <!-- Light overlay so text remains readable -->
        <div class="absolute inset-0" style="background: rgba(249,250,251,0.55);"></div>

        <div class="relative z-10 container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-6 md:gap-10 items-center lg:items-center max-w-6xl mx-auto">

                <!-- Poster / Thumbnail -->
                <div class="w-full sm:w-72 md:w-80 flex-shrink-0">
                    <div class="relative rounded-2xl overflow-hidden shadow-xl border-2 border-gray-200 bg-gray-100 group">
                        <img src="{{ $bannerImage }}" alt="{{ $campaign->title }}"
                            width="320" height="240" fetchpriority="high" decoding="async"
                            class="w-auto h-full object-cover group-hover:scale-105 transition-transform duration-500" />

                        <!-- Status Badge on Poster -->
                        <div class="absolute top-3 left-3 z-20">
                            @if($isOngoing)
                                <span class="inline-flex items-center gap-1.5 bg-[#2c4294] text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> Ongoing
                                </span>
                            @elseif($isUpcoming)
                                <span class="inline-flex items-center gap-1.5 bg-[#2c4294] text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                    <i class="fa-regular fa-clock text-xs"></i> Upcoming
                                </span>
                            @elseif($isCompleted)
                                <span class="inline-flex items-center gap-1.5 bg-[#2c4294] text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                    <i class="fa-solid fa-check text-xs"></i> Completed
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Side Details -->
                <div class="flex-1 text-center lg:text-left space-y-4">
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2.5">
                        <div class="inline-flex items-center gap-2 bg-[#2c4294] text-white px-3.5 py-1 rounded-full text-xs font-bold tracking-wider shadow-sm">
                            <i class="fa-solid fa-bullhorn text-xs"></i> Event
                        </div>

                        @if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('campaign.edit')))
                            <a href="{{ route('admin.contents.edit', ['campaign', $campaign->id]) }}"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 bg-amber-700 hover:bg-amber-800 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm transition-all hover:shadow">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit event</span>
                            </a>
                        @endif
                    </div>

                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight text-gray-900">
                        {{ $campaign->title }}
                    </h1>

                    @if($campaign->short)
                        <p class="text-gray-600 text-sm md:text-base max-w-2xl font-medium">
                            {{ $campaign->short }}
                        </p>
                    @endif

                    <!-- Event Info Badges -->
                    <div class="flex flex-wrap gap-2.5 sm:gap-3 justify-center lg:justify-start pt-2">
                        @if($campaign->location)
                            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-gray-700 shadow-sm">
                                <i class="fa-solid fa-location-dot text-red-500"></i>
                                <span>{{ $campaign->location }}</span>
                            </div>
                        @endif

                        @if($start)
                            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-gray-700 shadow-sm">
                                <i class="fa-regular fa-calendar-days text-amber-500"></i>
                                <span>
                                    {{ $formatBnDateTime($start) }}
                                    @if($end)
                                        - {{ $formatBnDateTime($end) }}
                                    @endif
                                </span>
                            </div>
                        @endif

                        @if($campaign->description_1)
                            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-gray-700 shadow-sm">
                                <i class="fa-solid fa-store text-indigo-600"></i>
                                <span>Stall No: <strong class="text-gray-900">{{ $campaign->description_1 }}</strong></span>
                            </div>
                        @endif

                        @if($campaign->description_2)
                            <div class="flex items-center gap-2 bg-amber-50 border border-amber-200 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-amber-900 shadow-sm font-semibold">
                                <i class="fa-solid fa-tags text-amber-600"></i>
                                <span>{{ $campaign->description_2 }}</span>
                            </div>
                        @endif

                        @if($campaign->description_3)
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $campaign->description_3) }}"
                               class="flex items-center gap-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-emerald-800 shadow-sm transition-colors font-semibold">
                                <i class="fa-solid fa-phone-volume text-emerald-600"></i>
                                <span>Hotline: {{ $campaign->description_3 }}</span>
                            </a>
                        @endif

                        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-gray-700 shadow-sm">
                            <i class="fa-solid fa-city text-[#2c4294]"></i>
                            <span>{{ $projCount }} Projects Included</span>
                        </div>
                    </div>

                    <!-- Upcoming Countdown Timer Box (If upcoming) -->
                    @if($isUpcoming && $start)
                        <div class="pt-2 flex flex-col sm:flex-row items-center lg:items-start gap-3">
                            <div class="inline-flex flex-col sm:flex-row items-center gap-3 bg-white border border-amber-300 rounded-2xl p-3 px-4 shadow-md">
                                <div class="inline-flex items-center gap-1.5 text-amber-600 text-xs font-bold uppercase tracking-wider">
                                    <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                                    শুরু হতে বাকি:
                                </div>
                                <div class="campaign-countdown flex items-center gap-1.5"
                                    data-target-date="{{ $start->toIso8601String() }}">
                                    <!-- Dynamic Live Boxes -->
                                </div>
                            </div>

                            <!-- Ask Me Question Button (Mobile popup trigger / visible on all or mobile) -->
                            <button type="button" onclick="openCampaignInquiryModal()"
                                class="lg:hidden inline-flex items-center justify-center gap-2 bg-[#2c4294] hover:bg-[#1a285a] text-white px-5 py-3 rounded-2xl font-bold text-sm shadow-md transition-all active:scale-95 cursor-pointer w-full sm:w-auto">
                                <i class="fa-solid fa-comment-dots text-base"></i>
                                <span>Ask Me Question</span>
                            </button>
                        </div>
                    @else
                        <!-- Ask Me Question Button for Ongoing/Completed or general campaigns on mobile -->
                        <div class="pt-2 lg:hidden">
                            <button type="button" onclick="openCampaignInquiryModal()"
                                class="inline-flex items-center justify-center gap-2 bg-[#2c4294] hover:bg-[#1a285a] text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-md transition-all active:scale-95 cursor-pointer w-full sm:w-auto">
                                <i class="fa-solid fa-comment-dots text-base"></i>
                                <span>Ask Me Question</span>
                            </button>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </section>

    <!-- MAIN 12-COLUMN CONTENT SECTION (Following details.blade.php layout) -->
    <div class="container py-8 md:py-12 mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- ════════════════════════════════════════
                 LEFT COLUMN (8 Columns)
            ════════════════════════════════════════ --}}
            <div class="lg:col-span-8 flex flex-col gap-8">

                {{-- 1. DESCRIPTION SECTION (Added by user in editor) --}}
                @if($campaign->description)
                    <div id="section-description" class="bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-5">
                            <div>
                                <span class="text-xs font-bold text-[#2c4294] uppercase tracking-wider block">Detailed Information</span>
                                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mt-1">Event Details</h2>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-[#2c4294]/10 flex items-center justify-center text-[#2c4294]">
                                <i class="fa-solid fa-file-lines text-lg"></i>
                            </div>
                        </div>

                        <div class="prose max-w-none text-gray-700 leading-relaxed text-base">
                            {!! $campaign->description !!}
                        </div>
                    </div>
                @endif

                {{-- 2. PROJECTS INCLUDED IN THIS CAMPAIGN (কোনো প্রজেক্ট না থাকলে সেকশন হাইড থাকবে) --}}
                @if($projects->isNotEmpty())
                    <div id="section-projects" class="bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4 mb-6">
                            <div>
                                <span class="text-xs font-bold text-[#2c4294] uppercase tracking-wider block">Special Offers & Opportunities</span>
                                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mt-1">
                                    Projects Included in this Event
                                </h2>
                            </div>
                            <span class="bg-[#2c4294]/10 text-[#2c4294] px-3.5 py-1.5 rounded-full text-xs font-extrabold w-fit">
                                Total {{ $projCount }} Projects
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($projects as $project)
                                <div>
                                    @include('frontend.partials.project_card', ['project' => $project, 'section' => 'campaign-details'])
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- ════════════════════════════════════════
                 RIGHT COLUMN (4 Columns - Sticky Sidebar)
            ════════════════════════════════════════ --}}
            <div class="lg:col-span-4 flex flex-col gap-6 sticky top-24">

                <!-- 1. Campaign Summary Card -->
                {{-- <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-900 text-base border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-[#2c4294]"></i>
                        <span>ইভেন্ট তথ্য</span>
                    </h3>

                    <div class="space-y-3.5 text-sm">
                        <div class="flex justify-between items-center py-1 border-b border-gray-50">
                            <span class="text-gray-500 font-medium">বর্তমান অবস্থা:</span>
                            <div>
                                @if($isOngoing)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2.5 py-0.5 rounded-full font-bold text-xs border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span> চলমান
                                    </span>
                                @elseif($isUpcoming)
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2.5 py-0.5 rounded-full font-bold text-xs border border-amber-200">
                                        <i class="fa-regular fa-clock text-[10px]"></i> আসন্ন
                                    </span>
                                @elseif($isCompleted)
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2.5 py-0.5 rounded-full font-bold text-xs">
                                        <i class="fa-solid fa-check text-[10px]"></i> সম্পন্ন
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($campaign->location)
                            <div class="flex justify-between items-start py-1 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">ভেন্যু / স্থান:</span>
                                <span class="text-gray-900 font-bold text-right">{{ $campaign->location }}</span>
                            </div>
                        @endif

                        @if($start)
                            <div class="flex justify-between items-start py-1 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">শুরুর সময়:</span>
                                <span class="text-gray-900 font-bold text-right">{{ $formatBnDateTime($start) }}</span>
                            </div>
                        @endif

                        @if($end)
                            <div class="flex justify-between items-start py-1 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">সমাপ্তির সময়:</span>
                                <span class="text-gray-900 font-bold text-right">{{ $formatBnDateTime($end) }}</span>
                            </div>
                        @endif

                        @if($campaign->description_1)
                            <div class="flex justify-between items-start py-1 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">স্টল নম্বর:</span>
                                <span class="text-gray-900 font-bold text-right">{{ $campaign->description_1 }}</span>
                            </div>
                        @endif

                        @if($campaign->description_2)
                            <div class="flex justify-between items-start py-1 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">বিশেষ অফার:</span>
                                <span class="text-amber-700 font-bold text-right bg-amber-50 px-2 py-0.5 rounded text-xs border border-amber-200">{{ $campaign->description_2 }}</span>
                            </div>
                        @endif

                        @if($campaign->description_3)
                            <div class="flex justify-between items-center py-1 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">যোগাযোগ / হেল্পলাইন:</span>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $campaign->description_3) }}"
                                   class="text-[#2c4294] hover:text-blue-800 font-bold text-right inline-flex items-center gap-1">
                                    <i class="fa-solid fa-phone text-xs"></i>
                                    <span>{{ $campaign->description_3 }}</span>
                                </a>
                            </div>
                        @endif

                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500 font-medium">অন্তর্ভুক্ত প্রজেক্ট:</span>
                            <span class="text-[#2c4294] font-extrabold text-right">{{ $bnProjCount }} টি</span>
                        </div>
                    </div>
                </div> --}}

                <!-- 2. Lead / Inquiry Contact Form (Hidden on mobile, visible on desktop sidebar) -->
                <div id="campaignInquiryCard" class="hidden lg:block relative bg-white p-6 rounded-2xl border border-gray-100 shadow-sm overflow-hidden scroll-mt-24">
                    <h3 class="font-bold text-gray-900 text-base border-b border-gray-100 pb-3 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-[#2c4294]"></i>
                        <span>Want Info About This Event?</span>
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">Fill out the form below to get in touch with our representative:</p>

                    {{-- Error Message --}}
                    @if(session('campaign_inquiry_error'))
                        <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 mb-4 text-sm">
                            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 flex-shrink-0"></i>
                            <span>{{ session('campaign_inquiry_error') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-4 text-xs">
                            @foreach($errors->all() as $err)
                                <p>• {{ $err }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form id="campaignInquiryForm" action="{{ route('campaign.inquiry') }}" method="POST" class="flex flex-col gap-3">
                        @csrf
                        <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                        <input type="hidden" name="recaptcha_token" id="campaign_recaptcha_token">

                        <div>
                            <label for="lead_name" class="sr-only">Your Name</label>
                            <input type="text" id="lead_name" name="lead_name" placeholder="Enter your name" required
                                aria-label="Your Name"
                                value="{{ old('lead_name', auth()->check() ? auth()->user()->name : '') }}"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 @error('lead_name') border-red-400 @enderror">
                        </div>

                        <div>
                            <label for="lead_phone" class="sr-only">Phone Number</label>
                            <input type="tel" id="lead_phone" name="lead_phone" placeholder="Enter phone number" required
                                aria-label="Phone Number"
                                value="{{ old('lead_phone', auth()->check() ? auth()->user()->phone : '') }}"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 @error('lead_phone') border-red-400 @enderror">
                        </div>

                        <div>
                            <label for="lead_email" class="sr-only">Email (Optional)</label>
                            <input type="email" id="lead_email" name="lead_email" placeholder="Email (Optional)"
                                aria-label="Email (Optional)"
                                value="{{ old('lead_email', auth()->check() ? auth()->user()->email : '') }}"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">
                        </div>

                        {{-- Optional: Project selector --}}
                        @if($projects->isNotEmpty())
                            <div>
                                <label for="inquiry_project_id" class="block text-xs font-semibold text-gray-500 mb-1.5">Which project are you inquiring about? <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <select id="inquiry_project_id" name="project_id"
                                    aria-label="Select a Project"
                                    class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 cursor-pointer">
                                    <option value="">— Select a Project —</option>
                                    @foreach($projects as $proj)
                                        <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label for="lead_message" class="sr-only">Your Message</label>
                            <textarea id="lead_message" name="lead_message" rows="3" placeholder="Write your message..."
                                aria-label="Your Message"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 resize-none">{{ old('lead_message', 'I would like to know more about ' . $campaign->title . '.') }}</textarea>
                        </div>

                        <button type="button" id="campaignInquirySubmitBtn"
                            aria-label="Send Message"
                            class="w-full bg-[#2c4294] hover:bg-[#152960] text-white py-3 rounded-xl font-bold text-sm transition-all shadow-md active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane text-xs" aria-hidden="true"></i>
                            <span>Send Message</span>
                        </button>

                        <div class="text-[11px] text-gray-500 text-center leading-relaxed mt-2.5 flex items-center justify-center gap-1 flex-wrap">
                            <i class="fa-solid fa-shield-halved text-[#2c4294] text-[11px]"></i>
                            <span>Protected by reCAPTCHA — Google</span>
                            <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" class="text-[#2c4294] underline hover:text-[#152960]">Privacy Policy</a>
                            <span>and</span>
                            <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer" class="text-[#2c4294] underline hover:text-[#152960]">Terms</a>
                        </div>
                    </form>
                </div>


                <!-- 3. Other Campaigns -->
                @if($otherCampaigns->isNotEmpty())
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wider mb-3">Other Events</h3>
                        <div class="space-y-3">
                            @foreach($otherCampaigns as $oc)
                                <a href="{{ route('campaign.details', $oc->slug) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition-all group">
                                    <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        <img src="{{ $oc->img_path ? asset('storage/' . $oc->img_path) : asset('assets/images/placeholder.webp') }}"
                                            width="56" height="56" loading="lazy" decoding="async"
                                            class="w-auto h-full object-cover group-hover:scale-105 transition-transform" alt="{{ $oc->title }}">
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-[#2c4294] transition-colors">{{ $oc->title }}</h4>
                                        <span class="text-xs text-gray-500 block truncate">{{ $oc->location ?? 'Special Event' }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

    <!-- Mobile Inquiry Popup Modal (Triggered by Ask Me Question button on mobile) -->
    <div id="mobileCampaignInquiryModal"
        class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300 opacity-0 pointer-events-none hidden"
        role="dialog"
        aria-modal="true"
        aria-labelledby="mobileInquiryModalTitle">

        <div id="mobileCampaignInquiryModalContent"
            class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 sm:p-7 transform transition-all duration-300 scale-95 opacity-0">

            <!-- Close Modal Button -->
            <button type="button"
                onclick="closeCampaignInquiryModal()"
                class="absolute top-4 right-4 w-9 h-9 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors cursor-pointer"
                aria-label="Close dialog">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Modal Header -->
            <div class="mb-4 pr-8">
                <div class="inline-flex items-center gap-2 text-[#2c4294] font-bold text-xs uppercase tracking-wider mb-1">
                    <i class="fa-solid fa-comments"></i>
                    <span>Inquiry & Contact</span>
                </div>
                <h3 id="mobileInquiryModalTitle" class="text-xl font-extrabold text-gray-900 leading-tight">
                    Ask Me Question
                </h3>
                <p class="text-xs text-gray-500 mt-1">Fill out the details to get in touch with our representative:</p>
            </div>

            <!-- Mobile Inquiry Form -->
            <form id="mobileCampaignInquiryForm" action="{{ route('campaign.inquiry') }}" method="POST" class="flex flex-col gap-3">
                @csrf
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                <input type="hidden" name="recaptcha_token" id="mobile_campaign_recaptcha_token">

                <div>
                    <label for="mob_lead_name" class="block text-xs font-bold text-gray-600 mb-1">Your Name <span class="text-red-500">*</span></label>
                    <input type="text" id="mob_lead_name" name="lead_name" placeholder="e.g. John Doe" required
                        value="{{ old('lead_name', auth()->check() ? auth()->user()->name : '') }}"
                        class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">
                </div>

                <div>
                    <label for="mob_lead_phone" class="block text-xs font-bold text-gray-600 mb-1">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" id="mob_lead_phone" name="lead_phone" placeholder="e.g. 017XXXXXXXX" required
                        value="{{ old('lead_phone', auth()->check() ? auth()->user()->phone : '') }}"
                        class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">
                </div>

                <div>
                    <label for="mob_lead_email" class="block text-xs font-bold text-gray-600 mb-1">Email <span class="text-gray-400 font-normal">(Optional)</span></label>
                    <input type="email" id="mob_lead_email" name="lead_email" placeholder="example@mail.com"
                        value="{{ old('lead_email', auth()->check() ? auth()->user()->email : '') }}"
                        class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">
                </div>

                @if($projects->isNotEmpty())
                    <div>
                        <label for="mob_inquiry_project_id" class="block text-xs font-bold text-gray-600 mb-1">Which project are you inquiring about? <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <select id="mob_inquiry_project_id" name="project_id"
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 cursor-pointer">
                            <option value="">— Select a Project —</option>
                            @foreach($projects as $proj)
                                <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->title }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label for="mob_lead_message" class="block text-xs font-bold text-gray-600 mb-1">Your Message</label>
                    <textarea id="mob_lead_message" name="lead_message" rows="3" placeholder="Write your question or message..."
                        class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 resize-none">{{ old('lead_message', 'I would like to know more about ' . $campaign->title . '.') }}</textarea>
                </div>

                <button type="button" id="mobileCampaignInquirySubmitBtn"
                    class="w-full bg-[#2c4294] hover:bg-[#152960] text-white py-3.5 rounded-xl font-bold text-sm transition-all shadow-md active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2 mt-2">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    <span>Send Message</span>
                </button>

                <div class="text-[11px] text-gray-500 text-center leading-relaxed mt-1 flex items-center justify-center gap-1 flex-wrap">
                    <i class="fa-solid fa-shield-halved text-[#2c4294] text-[11px]"></i>
                    <span>Protected by reCAPTCHA — Google</span>
                    <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" class="text-[#2c4294] underline hover:text-[#152960]">Privacy Policy</a>
                    <span>and</span>
                    <a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer" class="text-[#2c4294] underline hover:text-[#152960]">Terms</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Common Global Success Alert Modal (Works for both mobile popup and desktop sidebar submissions) -->
    <x-modal-alert
        id="campaignInquirySuccessModal"
        type="success"
        title="Thank you for your interest!"
        sessionKey="campaign_inquiry_success"
        buttonText="Okay"
    />

@endsection

@push('scripts')
<script>
    // Live countdown script for hero banner
    document.addEventListener('DOMContentLoaded', function () {
        const countdownElements = document.querySelectorAll('.campaign-countdown');
        if (!countdownElements.length) return;

        const bnDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        const toBn = (num) => String(num).replace(/[0-9]/g, d => bnDigits[d]);
        const pad = (n) => (n < 10 ? '0' + n : String(n));

        function update() {
            const now = new Date().getTime();

            countdownElements.forEach(el => {
                const targetStr = el.getAttribute('data-target-date');
                if (!targetStr) return;

                const target = new Date(targetStr).getTime();
                const diff = target - now;

                if (diff <= 0) {
                    el.innerHTML = '<span class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">ইভেন্ট শুরু হয়েছে!</span>';
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                el.innerHTML = `
                    <div class="inline-flex items-center justify-center gap-1 bg-black/75 backdrop-blur-md border border-white/20 rounded-md px-2 py-1 shadow-md">
                        <span class="text-[14px] font-extrabold text-white leading-tight font-mono">${toBn(days)}</span>
                        <span class="text-[12px] text-gray-300 font-medium leading-tight">দিন</span>
                    </div>
                    <span class="text-white/70 font-bold text-xs self-center leading-none animate-pulse">:</span>
                    <div class="inline-flex items-center justify-center gap-1 bg-black/75 backdrop-blur-md border border-white/20 rounded-md px-2 py-1 shadow-md">
                        <span class="text-[14px] font-extrabold text-white leading-tight font-mono">${toBn(pad(hours))}</span>
                        <span class="text-[12px] text-gray-300 font-medium leading-tight">ঘণ্টা</span>
                    </div>
                    <span class="text-white/70 font-bold text-xs self-center leading-none animate-pulse">:</span>
                    <div class="inline-flex items-center justify-center gap-1 bg-black/75 backdrop-blur-md border border-white/20 rounded-md px-2 py-1 shadow-md">
                        <span class="text-[14px] font-extrabold text-white leading-tight font-mono">${toBn(pad(minutes))}</span>
                        <span class="text-[12px] text-gray-300 font-medium leading-tight">মি.</span>
                    </div>
                    <span class="text-white/70 font-bold text-xs self-center leading-none animate-pulse">:</span>
                    <div class="inline-flex items-center justify-center gap-1 bg-black/75 backdrop-blur-md border border-white/20 rounded-md px-2 py-1 shadow-md">
                        <span class="text-[14px] font-extrabold text-white leading-tight font-mono">${toBn(pad(seconds))}</span>
                        <span class="text-[12px] text-gray-300 font-medium leading-tight">সে.</span>
                    </div>
                `;
            });
        }

        update();
        setInterval(update, 1000);
    });
</script>

{{-- Google reCAPTCHA v3 --}}
@include('partials.recaptcha')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('campaignInquirySubmitBtn');
        const form = document.getElementById('campaignInquiryForm');

        // Modal Open / Close Logic for Mobile
        window.openCampaignInquiryModal = function () {
            const modal = document.getElementById('mobileCampaignInquiryModal');
            const content = document.getElementById('mobileCampaignInquiryModalContent');
            if (!modal || !content) return;

            modal.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');
            document.body.classList.add('overflow-hidden');

            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        };

        window.closeCampaignInquiryModal = function () {
            const modal = document.getElementById('mobileCampaignInquiryModal');
            const content = document.getElementById('mobileCampaignInquiryModalContent');
            if (!modal || !content) return;

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');
            document.body.classList.remove('overflow-hidden');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        };

        // Close on backdrop click
        const mobModal = document.getElementById('mobileCampaignInquiryModal');
        if (mobModal) {
            mobModal.addEventListener('click', function (e) {
                if (e.target === mobModal) {
                    closeCampaignInquiryModal();
                }
            });
        }

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeCampaignInquiryModal();
            }
        });

        // Desktop Submit Handler
        if (btn && form) {
            btn.addEventListener('click', async function (e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Please wait...</span>';

                try {
                    const token = await window.getRecaptchaToken('campaign_inquiry');
                    const tokenInput = document.getElementById('campaign_recaptcha_token');
                    if (tokenInput) {
                        tokenInput.value = token;
                    }
                    form.submit();
                } catch (error) {
                    console.error('reCAPTCHA error:', error);
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    alert('Verification failed. Please try again.');
                }
            });
        }

        // Mobile Modal Form Submit Handler
        const mobBtn = document.getElementById('mobileCampaignInquirySubmitBtn');
        const mobForm = document.getElementById('mobileCampaignInquiryForm');

        if (mobBtn && mobForm) {
            mobBtn.addEventListener('click', async function (e) {
                e.preventDefault();

                if (!mobForm.checkValidity()) {
                    mobForm.reportValidity();
                    return;
                }

                const originalHtml = mobBtn.innerHTML;
                mobBtn.disabled = true;
                mobBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-xs"></i> <span>Please wait...</span>';

                try {
                    const token = await window.getRecaptchaToken('campaign_inquiry');
                    const tokenInput = document.getElementById('mobile_campaign_recaptcha_token');
                    if (tokenInput) {
                        tokenInput.value = token;
                    }
                    mobForm.submit();
                } catch (error) {
                    console.error('reCAPTCHA error:', error);
                    mobBtn.disabled = false;
                    mobBtn.innerHTML = originalHtml;
                    alert('Verification failed. Please try again.');
                }
            });
        }

        @if($errors->any() || session('campaign_inquiry_error'))
            if (window.innerWidth < 1024) {
                openCampaignInquiryModal();
            } else {
                const inquiryCard = document.getElementById('campaignInquiryCard');
                if (inquiryCard) {
                    inquiryCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        @endif
    });
</script>

{{-- Project Compare Scripts (Localstorage, drawer & toggle) --}}
@include('frontend.partials.project_scripts')
@endpush
