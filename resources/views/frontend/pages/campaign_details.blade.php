@extends('layouts.front')

@section('meta')
    @include('frontend.partials.meta', [
        'pageKey' => 'campaign',
        'title' => ($campaign->meta_title ?? $campaign->title) . ' | Bhaiya Mela',
        'description' => $campaign->meta_description ?? $campaign->short ?? Str::limit(strip_tags($campaign->description), 150),
        'keywords' => $campaign->meta_keywords ?? 'campaign, bhaiya mela, real estate expo',
        'image' => $campaign->img_path ? asset('storage/' . $campaign->img_path) : asset('assets/images/placeholder.webp')
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
    $formatBnDate = function($date) use ($bnDigits, $bnMonths) {
        if (!$date) return '';
        $c = \Carbon\Carbon::parse($date);
        $d = strtr($c->format('d'), $bnDigits);
        $m = $bnMonths[$c->format('F')] ?? $c->format('M');
        $y = strtr($c->format('Y'), $bnDigits);
        return "{$d} {$m}, {$y}";
    };

    $bannerImage = $campaign->img_path ? asset('storage/' . $campaign->img_path) : asset('assets/images/placeholder.webp');
    $projCount = $projects->count();
    $bnProjCount = strtr((string)$projCount, $bnDigits);
@endphp

@section('content')

    <!-- BREADCRUMB BAR -->
    <section class="border-b border-gray-100 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-2 text-sm py-3 text-gray-600">
                <a href="{{ url('/') }}" class="hover:text-[#2c4294] transition-colors">হোম</a>
                <span class="text-gray-300">›</span>
                <a href="{{ url('/') }}#campaign-section" class="hover:text-[#2c4294] transition-colors">ক্যাম্পেইনসমূহ</a>
                <span class="text-gray-300">›</span>
                <span class="text-gray-900 font-semibold truncate">{{ $campaign->title }}</span>
            </div>
        </div>
    </section>

    <!-- HERO / BANNER SECTION -->
    <section class="relative overflow-hidden py-10 md:py-16 bg-gray-50 text-gray-900">
        <!-- Campaign thumbnail as blurred background with reduced opacity -->
        <img src="{{ $bannerImage }}" alt=""
            class="absolute inset-0 w-full h-full object-cover pointer-events-none select-none"
            style="filter: blur(8px); opacity: 0.50; transform: scale(1.08);">
        <!-- Light overlay so text remains readable -->
        <div class="absolute inset-0" style="background: rgba(249,250,251,0.55);"></div>

        <div class="relative z-10 container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-6 md:gap-10 items-center lg:items-center max-w-6xl mx-auto">

                <!-- Poster / Thumbnail -->
                <div class="w-full sm:w-72 md:w-80 flex-shrink-0">
                    <div class="relative aspect-[16/11] sm:aspect-[4/3] rounded-2xl overflow-hidden shadow-xl border-2 border-gray-200 bg-gray-100 group">
                        <img src="{{ $bannerImage }}" alt="{{ $campaign->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />

                        <!-- Status Badge on Poster -->
                        <div class="absolute top-3 left-3 z-20">
                            @if($isOngoing)
                                <span class="inline-flex items-center gap-1.5 bg-emerald-600/95 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> চলমান
                                </span>
                            @elseif($isUpcoming)
                                <span class="inline-flex items-center gap-1.5 bg-amber-600/95 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                    <i class="fa-regular fa-clock text-xs"></i> আসন্ন
                                </span>
                            @elseif($isCompleted)
                                <span class="inline-flex items-center gap-1.5 bg-gray-700/95 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                    <i class="fa-solid fa-check text-xs"></i> সম্পন্ন
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Side Details -->
                <div class="flex-1 text-center lg:text-left space-y-4">
                    <div class="inline-flex items-center gap-2 bg-[#2c4294] text-white px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm">
                        <i class="fa-solid fa-bullhorn text-xs"></i> স্পেশাল ক্যাম্পেইন
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
                                <span>{{ $formatBnDate($start) }} - {{ $formatBnDate($end) }}</span>
                            </div>
                        @endif

                        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-gray-700 shadow-sm">
                            <i class="fa-solid fa-city text-[#2c4294]"></i>
                            <span>{{ $bnProjCount }} টি প্রজেক্ট অন্তর্ভুক্ত</span>
                        </div>
                    </div>

                    <!-- Upcoming Countdown Timer Box (If upcoming) -->
                    @if($isUpcoming && $start)
                        <div class="pt-2">
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
                                <span class="text-xs font-bold text-[#2c4294] uppercase tracking-wider block">বিস্তারিত তথ্য</span>
                                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mt-1">ক্যাম্পেইনের বিবরণ</h2>
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

                {{-- 2. PROJECTS INCLUDED IN THIS CAMPAIGN (As requested: description-এর পরে প্রজেক্ট কার্ড শো হবে) --}}
                <div id="section-projects" class="bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4 mb-6">
                        <div>
                            <span class="text-xs font-bold text-[#2c4294] uppercase tracking-wider block">বিশেষ অফার ও সুযোগ</span>
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mt-1">
                                ক্যাম্পেইনের অন্তর্ভুক্ত প্রজেক্টসমূহ
                            </h2>
                        </div>
                        <span class="bg-[#2c4294]/10 text-[#2c4294] px-3.5 py-1.5 rounded-full text-xs font-extrabold w-fit">
                            মোট {{ $bnProjCount }} টি প্রজেক্ট
                        </span>
                    </div>

                    @if($projects->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($projects as $project)
                                <div>
                                    @include('frontend.partials.project_card', ['project' => $project, 'section' => 'campaign-details'])
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-12 text-center text-gray-500 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                            <i class="fa-solid fa-city text-3xl text-gray-400 mb-2"></i>
                            <p class="font-medium text-sm">এই ক্যাম্পেইনে এখনও কোনো প্রজেক্ট যুক্ত করা হয়নি।</p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- ════════════════════════════════════════
                 RIGHT COLUMN (4 Columns - Sticky Sidebar)
            ════════════════════════════════════════ --}}
            <div class="lg:col-span-4 flex flex-col gap-6 sticky top-24">

                <!-- 1. Campaign Summary Card -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-900 text-base border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-[#2c4294]"></i>
                        <span>ক্যাম্পেইন তথ্য</span>
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
                                <span class="text-gray-900 font-bold text-right">{{ $formatBnDate($start) }}</span>
                            </div>
                        @endif

                        @if($end)
                            <div class="flex justify-between items-start py-1 border-b border-gray-50">
                                <span class="text-gray-500 font-medium">সমাপ্তির সময়:</span>
                                <span class="text-gray-900 font-bold text-right">{{ $formatBnDate($end) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500 font-medium">অন্তর্ভুক্ত প্রজেক্ট:</span>
                            <span class="text-[#2c4294] font-extrabold text-right">{{ $bnProjCount }} টি</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Lead / Inquiry Contact Form -->
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-900 text-base border-b border-gray-100 pb-3 mb-2 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane text-[#2c4294]"></i>
                        <span>ক্যাম্পেইন সংক্রান্ত তথ্য চান?</span>
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">আমাদের প্রতিনিধির সাথে যোগাযোগ করতে নিচের ফর্মটি পূরণ করুন:</p>

                    {{-- Success Message --}}
                    @if(session('campaign_inquiry_success'))
                        <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 mb-4 text-sm">
                            <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 flex-shrink-0"></i>
                            <span>{{ session('campaign_inquiry_success') }}</span>
                        </div>
                    @endif

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

                    <form action="{{ route('campaign.inquiry') }}" method="POST" class="flex flex-col gap-3">
                        @csrf
                        <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                        <input type="text" name="lead_name" placeholder="আপনার নাম লিখুন" required
                            value="{{ old('lead_name', auth()->check() ? auth()->user()->name : '') }}"
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 @error('lead_name') border-red-400 @enderror">

                        <input type="tel" name="lead_phone" placeholder="ফোন নম্বর লিখুন" required
                            value="{{ old('lead_phone', auth()->check() ? auth()->user()->phone : '') }}"
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 @error('lead_phone') border-red-400 @enderror">

                        <input type="email" name="lead_email" placeholder="ইমেইল (ঐচ্ছিক)"
                            value="{{ old('lead_email', auth()->check() ? auth()->user()->email : '') }}"
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">

                        {{-- Optional: Project selector --}}
                        @if($projects->isNotEmpty())
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1.5">কোন প্রজেক্ট সম্পর্কে জানতে চান? <span class="text-gray-400 font-normal">(ঐচ্ছিক)</span></label>
                                <select name="project_id"
                                    class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 cursor-pointer">
                                    <option value="">— প্রজেক্ট নির্বাচন করুন —</option>
                                    @foreach($projects as $proj)
                                        <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>{{ $proj->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <textarea name="lead_message" rows="3" placeholder="আপনার বার্তা লিখুন..."
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 resize-none">{{ old('lead_message', $campaign->title . ' সম্পর্কে বিস্তারিত জানতে চাই।') }}</textarea>

                        <button type="submit"
                            class="w-full bg-[#2c4294] hover:bg-[#152960] text-white py-3 rounded-xl font-bold text-sm transition-all shadow-md active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            মেসেজ পাঠান
                        </button>
                    </form>
                </div>


                <!-- 3. Other Campaigns -->
                @if($otherCampaigns->isNotEmpty())
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                        <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wider mb-3">অন্যান্য ক্যাম্পেইন</h3>
                        <div class="space-y-3">
                            @foreach($otherCampaigns as $oc)
                                <a href="{{ route('campaign.details', $oc->slug) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition-all group">
                                    <div class="w-14 h-14 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                        <img src="{{ $oc->img_path ? asset('storage/' . $oc->img_path) : asset('assets/images/placeholder.webp') }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform" alt="{{ $oc->title }}">
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-[#2c4294] transition-colors">{{ $oc->title }}</h4>
                                        <span class="text-xs text-gray-500 block truncate">{{ $oc->location ?? 'স্পেশাল ক্যাম্পেইন' }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

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
                    el.innerHTML = '<span class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">ক্যাম্পেইন শুরু হয়েছে!</span>';
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                el.innerHTML = `
                    <div class="flex flex-col items-center bg-black/75 backdrop-blur-md border border-white/20 rounded-md px-1.5 py-1 min-w-[34px] shadow-md">
                        <span class="text-xs sm:text-sm font-extrabold text-white leading-none font-mono">${toBn(days)}</span>
                        <span class="text-[8px] text-gray-300 font-medium mt-0.5">দিন</span>
                    </div>
                    <span class="text-amber-400 font-bold text-xs -mt-1 animate-pulse">:</span>
                    <div class="flex flex-col items-center bg-black/75 backdrop-blur-md border border-white/20 rounded-md px-1.5 py-1 min-w-[34px] shadow-md">
                        <span class="text-xs sm:text-sm font-extrabold text-white leading-none font-mono">${toBn(pad(hours))}</span>
                        <span class="text-[8px] text-gray-300 font-medium mt-0.5">ঘণ্টা</span>
                    </div>
                    <span class="text-amber-400 font-bold text-xs -mt-1 animate-pulse">:</span>
                    <div class="flex flex-col items-center bg-black/75 backdrop-blur-md border border-white/20 rounded-md px-1.5 py-1 min-w-[34px] shadow-md">
                        <span class="text-xs sm:text-sm font-extrabold text-white leading-none font-mono">${toBn(pad(minutes))}</span>
                        <span class="text-[8px] text-gray-300 font-medium mt-0.5">মি.</span>
                    </div>
                    <span class="text-amber-400 font-bold text-xs -mt-1 animate-pulse">:</span>
                    <div class="flex flex-col items-center bg-amber-500/90 backdrop-blur-md border border-amber-300/40 rounded-md px-1.5 py-1 min-w-[34px] shadow-md">
                        <span class="text-xs sm:text-sm font-extrabold text-white leading-none font-mono">${toBn(pad(seconds))}</span>
                        <span class="text-[8px] text-amber-100 font-medium mt-0.5">সে.</span>
                    </div>
                `;
            });
        }

        update();
        setInterval(update, 1000);
    });
</script>
@endpush
