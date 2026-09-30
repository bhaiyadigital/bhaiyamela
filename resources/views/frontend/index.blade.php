@extends('layouts.front')
@section('meta')
    @include('frontend.partials.meta', ['pageKey' => 'home'])
@endsection
@section('content')
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .project-card {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .swiper-wrapper {
            height: inherit !important;
            padding-bottom: 0px;
        }


        @media (max-width: 1023px) {
            .common-mobile-swiper {
                overflow: hidden !important;
                width: 100% !important;
                padding-bottom: 20px !important;
            }

            .common-mobile-swiper .swiper-wrapper {
                display: flex !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                gap: 16px !important;
                scroll-snap-type: x mandatory;
                scrollbar-width: none;
                /* Firefox */
                -ms-overflow-style: none;
                /* IE and Edge */
                padding-bottom: 10px !important;
            }

            .common-mobile-swiper .swiper-wrapper::-webkit-scrollbar {
                display: none;
            }

            .common-mobile-swiper .swiper-slide {
                flex: 0 0 85% !important;
                scroll-snap-align: center;
                min-width: 0;
            }
        }

        @media (min-width: 1024px) {
            .common-mobile-swiper .swiper-wrapper {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 24px !important;
                transform: none !important;
                width: 100% !important;
                height: inherit !important;
            }

            .common-mobile-swiper .swiper-slide {
                width: 100% !important;
                margin-right: 0 !important;
            }

            .common-mobile-swiper {
                overflow: visible !important;
            }
        }

        .swiper-pagination-bullet {
            width: 32px !important;
            height: 32px !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            background: transparent !important;
            opacity: 1 !important;
            margin: 0 2px !important;
            cursor: pointer;
        }

        .swiper-pagination-bullet::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 9999px;
            background-color: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }


        .swiper-pagination-bullet-active::before {
            background-color: #eab308 !important;
            transform: scale(1.1);
        }


        .swiper-pagination-bullet:hover::before {
            background-color: rgba(255, 255, 255, 1);
        }
    </style>
    <section class="w-full overflow-x-hidden">

        <div class="relative w-full overflow-hidden bg-transparent">

            @foreach($hero as $index => $slide)
                @php
                    $redirectUrl = $slide->company_id
                        ? route('web.project', ['company' => $slide->company?->slug])
                        : route('web.project');
                @endphp

                <a href="{{ $redirectUrl }}"
                    class="hero-slide w-full transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100 z-10 relative block' : 'opacity-0 z-0 absolute inset-0' }}"
                    data-index="{{ $index }}">
                    <img src="{{ asset($slide->img_path ? 'storage/' . $slide->img_path : 'assets/images/landing-hero.jpg') }}"
                        class="w-full h-auto object-contain" alt="Hero Slide {{ $index + 1 }}" />
                </a>
            @endforeach

            @if(count($hero) > 1)
                <div
                    class="absolute bottom-2 md:bottom-5 left-1/2 -translate-x-1/2 z-20 flex gap-0 md:gap-1 bg-black/30 px-2 py-1 md:py-1.5 rounded-full backdrop-blur-sm items-center justify-center">
                    @foreach($hero as $index => $slide)
                        <button type="button" onclick="goToSlide({{ $index }})" aria-label="Go to slide {{ $index + 1 }}"
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                            class="w-6 h-6 md:w-8 md:h-8 flex items-center justify-center transition-all duration-300 focus:outline-none"
                            data-index="{{ $index }}">

                            <span
                                class="hero-dot w-2 h-2 md:w-2.5 md:h-2.5 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-[#2c4294] scale-110' : 'bg-white/50 hover:bg-white' }}"></span>

                        </button>
                    @endforeach
                </div>
            @endif

        </div>





    </section>
    {{-- <!-- 2. Floating Tabs Box -->
    <div class="container mx-auto px-4 md:px-10 -mt-2 md:-mt-4 relative z-20">
        <div
            class="bg-white rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.15)] p-5 md:p-8 border border-gray-100 max-w-6xl mx-auto">

            <form action="{{ route('web.project') }}" method="GET"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                @csrf

                <!-- Category Select -->
                <div class="flex flex-col gap-1.5">
                    <!-- IMPROVED: for updated to "home-category-select" and text color is text-gray-600 -->
                    <label for="home-category-select"
                        class="text-xs font-bold text-gray-600 uppercase tracking-wider pl-1">What are you looking
                        for?</label>
                    <div class="relative">
                        <select id="home-category-select" name="category"
                            class="w-full bg-gray-50 border border-gray-100 px-4 py-3.5 rounded-2xl text-xs md:text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#224194]/20 transition-all appearance-none cursor-pointer">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Location Modal Trigger (Custom Dropdown Div) -->
                <div class="flex flex-col gap-1.5">
                    <!-- IMPROVED: text-gray-500 changed to text-gray-600 -->
                    <label class="text-xs font-bold text-gray-600 uppercase pl-1">Where? (Location)</label>
                    <!-- IMPROVED: text-gray-500 changed to text-gray-600 for contrast compliance -->
                    <div onclick="openLocationModal()"
                        class="w-full bg-gray-50 border border-gray-100 px-5 py-4 rounded-2xl text-xs md:text-base font-bold text-gray-600 flex justify-between items-center cursor-pointer select-none"
                        style="height: 44px;">
                        <span>Select Location...</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400" aria-hidden="true"></i>
                    </div>
                </div>

                <!-- Company Select -->
                <div class="flex flex-col gap-1.5">
                    <!-- IMPROVED: for updated to "filter_company_select", and text-gray-500 to text-gray-600 -->
                    <label for="filter-company" class="text-xs font-bold text-gray-600 uppercase pl-1">Developer /
                        Company</label>
                    <!-- Added: id="filter-company" to link with the label above -->
                    <select id="filter-company" name="company"
                        class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all text-gray-600">
                        <option value="">All Companies</option>
                        @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company')==$company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-[#2c4294] hover:bg-[#152960] text-white py-3.5 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition-all shadow-lg shadow-[#2c4294]/10 active:scale-[0.98] cursor-pointer">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        Search Projects
                    </button>
                </div>

            </form>

        </div>
    </div> --}}


    <!-- PROJECTS GRID SECTION -->
    <section class="py-4 md:py-10">
        <div class="container mx-auto px-4">
            <div class="border-b border-gray-200">

                <!-- Section Header Area -->
                <div
                    class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-2 border-b border-gray-100 pb-2">
                    <!-- Title Content -->
                    <div class="text-left">
                        <span
                            class="inline-flex items-center gap-1.5 bg-[#000678]/10 border border-[#000678]/20 text-[#000678] px-3 py-1 rounded-full text-xs font-bold mb-1.5">
                            <i class="fa-solid fa-fire text-xs"></i> ফিচার্ড
                        </span>
                        <h2 class="text-2xl md:text-3xl font-bold text-[#111111]">
                            আপনার পছন্দের প্রপার্টি বেছে নিন
                        </h2>
                    </div>

                    <!-- View All Link -->
                    <div class="hidden lg:block mb-1">
                        <a href="{{ route('web.project') }}"
                            class="inline-flex items-center gap-1.5 text-[#2c4294] font-bold text-sm hover:underline transition-all group">
                            সবগুলো দেখুন
                            <i
                                class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="flex justify-start overflow-x-auto pb-4 stall-filter-wrapper">
                    <div class="flex gap-8  whitespace-nowrap ">
                        <button data-filter="all" aria-pressed="true"
                            class="cursor-pointer filter-btn pb-3 text-gray-900 font-bold text-base md:text-base border-b-2 border-[#2c4294] active-filter transition-all duration-300">
                            সবগুলো
                        </button>
                        @foreach ($categories as $cat)
                            <button data-filter="{{ $cat->id }}" aria-pressed="false"
                                class="cursor-pointer filter-btn pb-3 text-gray-700 font-bold text-base md:text-base border-b-2 border-transparent hover:text-gray-900 transition-all duration-300">
                                {{ $cat->title }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cards Grid / Slider -->
            <div class="swiper common-mobile-swiper lg:!overflow-hidden mt-0 md:mt-6">
                <div id="project-container" class="swiper-wrapper">
                    @forelse($projects as $project)
                        <div class="swiper-slide h-auto">
                            @include('frontend.partials.project_card', ['project' => $project])

                        </div>
                    @empty
                        <!-- IMPROVED: Changed text-gray-400 to text-gray-600 for contrast compliance -->
                        <p class="col-span-full text-center text-gray-600 py-10">কোন প্রকল্প পাওয়া যায়নি।</p>
                    @endforelse
                </div>

                <!-- Swiper Pagination (Mobile only) -->
                <div class="swiper-pagination !static mt-10 lg:hidden"></div>
            </div>

            <!-- View All Button (Mobile: Bottom) -->
            <div class="mt-0 md:mt-12 flex justify-center">
                <a href="{{ route('web.project') }}"
                    class="inline-flex items-center gap-3 px-10 py-4 bg-[#2c4294] text-white rounded-2xl font-bold shadow-lg shadow-blue-900/20 active:scale-95 transition-all">
                    সবগুলো প্রপার্টি দেখুন
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </section>`

    <!-- LIVE PROJECTS SECTION -->
    {{-- <section class=" py-12 md:py-20">
        <div class="container mx-auto px-6 md:px-10">
            <!-- Section Header Area -->
            <div class="flex flex-col lg:flex-row justify-between items-start gap-10 mb-12">

                <!-- Left Side: Subtitle, Title & Filter Tabs -->
                <div class="flex-1 w-full">
                    <span class="text-[#2c4294] text-xs md:text-base font-bold uppercase tracking-[0.2em] block mb-3">
                        Moments at the
                    </span>
                    <h2 class="text-xl md:text-[32px] font-semibold text-[#111111] leading-tight mb-8">
                        Watch Our Live Projects
                    </h2>

                    <!-- ── Filter Tabs: Luxury Outline ── -->
                    <div class="flex justify-start overflow-x-auto pb-4 no-scrollbar">
                        <div class="flex gap-3 md:gap-4 whitespace-nowrap">
                            <button data-filter="all"
                                class="cursor-pointer filter-btn px-8 py-2.5 rounded-lg border-2 border-[#2c4294] bg-[#2c4294] text-white font-bold text-base uppercase tracking-wider transition-all shadow-md active-filter">
                                All
                            </button>

                            @foreach ($categories as $cat)
                            <button data-filter="{{ $cat->id }}"
                                class="cursor-pointer filter-btn px-8 py-2.5 rounded-lg border-2 border-gray-200 text-gray-500 font-bold text-base uppercase tracking-wider transition-all hover:border-[#2c4294] hover:text-[#2c4294] bg-white">
                                {{ $cat->title }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Side: Description & Desktop Button -->
                <div class="lg:max-w-md lg:text-right flex flex-col lg:items-end justify-between self-stretch">
                    <p class="text-gray-600 text-base md:text-base leading-relaxed mb-8">
                        Explore our beautifully designed spaces, from sun-drenched pools to tranquil spa retreats, every
                        corner is designed for your ultimate relaxation.
                    </p>

                    <!-- Desktop View All Button -->
                    <div class="hidden lg:block mt-auto">
                        <a href="{{ route('web.project') }}"
                            class="inline-flex items-center gap-3 px-8 py-3 border-2 border-[#2c4294] text-[#2c4294] rounded-xl font-bold text-base hover:bg-[#2c4294] hover:text-white transition-all duration-300 group">
                            View All Projects
                            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cards Slider (Swiper) -->
            <div class="swiper common-mobile-swiper lg:!overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach ($liveProject as $project)
                    <div class="swiper-slide h-auto">
                        <a href="{{ route('project.details', $project->slug) }}" data-category="{{ $project->parent_id }}"
                            data-section="live"
                            class="project-card bg-white rounded-[2.5rem] h-full flex flex-col overflow-hidden shadow-[0_15px_40px_-15px_rgba(0,0,0,0.1)] border border-gray-100 group transition-all duration-500 hover:shadow-2xl">

                            <!-- Video/Thumbnail Area -->
                            <div class="relative aspect-[4/3] overflow-hidden bg-black cursor-pointer"
                                onclick="event.preventDefault(); event.stopPropagation(); playGridVideo('{{ $project->id }}')">
                                <div id="thumb-container-{{ $project->id }}" class="absolute inset-0 z-20">
                                    @php
                                    $images = $project->img_paths;
                                    $firstImage = !empty($images) ? $images[0] : 'assets/images/placeholder.jpg';
                                    @endphp
                                    <img src="{{ asset('storage/' . $firstImage) }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                        alt="{{ $project->title }}" />
                                    <div
                                        class="absolute top-5 left-5 bg-red-600 text-white px-4 py-1.5 rounded-full flex items-center gap-2 shadow-lg z-10">
                                        <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span><span
                                            class="text-[10px] font-bold uppercase tracking-widest">Live</span>
                                    </div>
                                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-white/80 rounded-full flex items-center justify-center shadow-2xl backdrop-blur-sm group-hover:scale-110 transition-all duration-500">
                                            <i class="fa-solid fa-play text-[#2c4294] text-xl ml-1"></i>
                                        </div>
                                    </div>
                                </div>
                                @if ($project->video_path)
                                <video id="video-{{ $project->id }}" class="w-full h-full object-cover hidden z-10"
                                    controls>
                                    <source src="{{ asset('storage/' . $project->video_path) }}" type="video/mp4">
                                </video>
                                @endif
                            </div>

                            <div class="p-8">
                                <h3 class="text-xl md:text-xl font-bold text-[#111111] mb-3 line-clamp-2">
                                    {{ $project->title }}
                                </h3>
                                <div class="flex items-center gap-2 text-gray-500 text-base mb-6"><i
                                        class="fa-solid fa-location-dot"></i><span>{{ $project->location ?? 'Location'
                                        }}</span>
                                </div>
                                <div class="h-[1px] w-full bg-gray-100 mb-6"></div>
                                <div class="flex items-center gap-2 text-gray-700 font-bold"><i
                                        class="fa-regular fa-eye"></i><span>{{ number_format($project->views) }}
                                        Watching</span></div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <!-- Pagination Mobile -->
                <div class="swiper-pagination !static mt-10 lg:hidden"></div>
            </div>

            <!-- Mobile View All Button -->
            <div class="flex justify-center mt-12 lg:hidden">
                <a href="{{ route('web.project') }}"
                    class="w-full sm:w-auto text-center px-10 py-4 bg-[#2c4294] text-white rounded-2xl font-bold shadow-lg active:scale-95 transition-all">
                    View All Projects
                </a>
            </div>
        </div>
    </section> --}}


    {{-- <section class=" py-12 md:py-20">
        <div class="container mx-auto px-6 md:px-10">
            <!-- 3. Section Title -->
            <div class="text-center mb-8">
                <h2 class="text-lg md:text-xl font-extrabold text-[#2c4294] uppercase tracking-wider">
                    Our Platinum Members
                </h2>
                <div class="w-24 h-1 bg-[#2c4294] mx-auto mt-2"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 mb-8">
                @forelse($companies as $company)
                @php
                $logo = $company->company_logo ? 'storage/' . $company->company_logo : 'assets/images/placeholder.jpg';
                @endphp

                <!-- Company Card link filters project list by selected developer id -->
                <a href="{{ route('web.project', ['company' => $company->slug]) }}"
                    class="bg-white border border-gray-100 rounded-2xl p-5 flex flex-col items-center justify-between shadow-[0_10px_30px_rgba(0,0,0,0.01)] hover:shadow-lg hover:border-gray-200 transition-all duration-300 aspect-square text-center group">

                    <!-- Logo Container with uniform sizing -->
                    <div class="w-full flex-grow flex items-center justify-center p-2 mb-4 h-28">
                        <img src="{{ asset($logo) }}"
                            class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-105"
                            alt="{{ $company->company_name }}">
                    </div>

                    <!-- Company Name with green accent -->
                    <div class="w-full">
                        <h3
                            class="text-xs md:text-base font-extrabold text-[#2c4294] group-hover:underline leading-tight line-clamp-2">
                            {{ $company->company_name }}
                        </h3>
                    </div>

                </a>
                @empty
                <div class="col-span-full text-center py-10 text-gray-400">
                    <p class="font-semibold text-base">No premium developers found at the moment.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section> --}}

    <!-- 4. Companies Responsive Grid (6 Columns on Desktop) -->


    <!-- EXPLORE BLOG SECTION -->
    @if($blogs->count() > 0)
        <section class="py-4 md:py-10">
            <div class="container mx-auto px-4">
                <div
                    class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-6 gap-2 border-b border-gray-200 pb-2">
                    <div class="text-left">
                        <span
                            class="inline-flex items-center gap-1.5 bg-[#000678]/10 border border-[#000678]/20 text-[#000678] px-3 py-1 rounded-full text-xs font-bold mb-1.5">
                            <i class="fa-solid fa-book-open text-xs"></i> আমাদের জার্নাল থেকে
                        </span>
                        <h2 class="text-2xl md:text-3xl font-bold text-[#111111] mb-1">
                            আমাদের ব্লগ এক্সপ্লোর করুন</span>
                        </h2>
                        <p class="text-gray-500 text-sm md:text-base max-w-xl">
                            আমাদের রিসোর্টের দৃশ্য ও শব্দে নিজেকে হারিয়ে ফেলুন। একটি ভার্চুয়াল ট্যুর নিন
                        </p>
                    </div>

                    <div class="hidden lg:block mb-1 whitespace-nowrap">
                        <a href="{{ route('web.blog') }}" aria-label="Visit our blog"
                            class="inline-flex items-center gap-1.5 text-[#2c4294] font-bold text-sm hover:underline transition-all group">
                            আরও দেখুন
                            <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>

                <!-- Blog Cards Grid -->
                <div class="swiper common-mobile-swiper lg:!overflow-hidden">
                    <div class="swiper-wrapper">
                        @forelse($blogs as $blog)
                            <div class="swiper-slide h-auto">
                                <a href="{{ route('web.blog.details', $blog->slug) }}" aria-label="View blog post"
                                    class="bg-white h-full flex flex-col rounded-xl overflow-hidden shadow-[0_15px_40px_-15px_rgba(0,0,0,0.05)] border border-gray-100 group transition-all duration-300 hover:shadow-2xl">

                                    <!-- Image Area -->
                                    <div class="relative aspect-[16/10] overflow-hidden">
                                        <img src="{{ asset('storage/' . $blog->img_path ?? '') }}"
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                            alt="{{ $blog->title ?? '' }}" />
                                    </div>

                                    <!-- Card Content Body -->
                                    <div class="p-5 md:p-6 pb-4">
                                        <div class="flex justify-between items-center mb-4">
                                            <span
                                                class="bg-[#f3f4f6] text-gray-700 px-3 py-1 rounded md:rounded-md text-[10px] font-bold uppercase tracking-wider">
                                                {{ $blog->project->title ?? 'Bhaiya Group' }}
                                            </span>
                                            <div class="flex items-center gap-1.5 text-gray-700 text-xs font-semibold">
                                                <i class="fa-regular fa-clock text-xs"></i>
                                                <span>
                                                    @php
                                                        $wordCount = str_word_count(strip_tags($blog->body));
                                                        $minutes = ceil($wordCount / 200);
                                                    @endphp
                                                    {{ $minutes == 0 ? 1 : $minutes }} মিনিট পড়ার সময়
                                                </span>
                                            </div>
                                        </div>

                                        <h3
                                            class="text-base md:text-lg font-bold text-gray-900 mb-4 leading-tight group-hover:text-[#2c4294] transition line-clamp-2">
                                            {{ $blog->title ?? '' }}
                                        </h3>

                                        <span class="inline-flex items-center gap-1.5 text-[#2c4294] font-extrabold text-sm">
                                            আর্টিকেল পড়ুন
                                            <i
                                                class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                                        </span>
                                    </div>

                                    <!-- Card Footer -->
                                    <div
                                        class="px-5 md:px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-3 mt-auto">
                                        <div class="flex items-center gap-2 overflow-hidden">
                                            <img src="{{ asset($blog->company?->company_logo ? 'storage/' . $blog->company->company_logo : 'assets/images/logo.png') }}"
                                                class="w-8 h-8 md:w-10 md:h-10 rounded-full object-cover shadow-sm" alt="Author" />
                                            <div class="leading-tight overflow-hidden">
                                                <h4 class="text-xs md:text-sm font-bold text-gray-900 truncate">
                                                    {{ $blog->user->name ?? 'Admin' }}
                                                </h4>
                                                <p class="text-[10px] md:text-xs font-medium text-gray-500 truncate">
                                                    {{ $blog->company->company_name ?? 'Bhaiya Group' }}
                                                </p>
                                            </div>
                                        </div>
                                        @if($blog->start_date)
                                            <div
                                                class="flex items-center gap-1.5 text-gray-500 text-[10px] md:text-xs font-semibold whitespace-nowrap">
                                                <i class="fa-regular fa-calendar-days text-[10px] md:text-xs text-gray-400"></i>
                                                <span>{{ $blog->start_date->format('M d, Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @empty
                            <p class="col-span-full text-center text-gray-400">কোন ব্লগ পোস্ট পাওয়া যায়নি।</p>
                        @endforelse

                    </div>
                </div>
                <div class="flex justify-center mt-0 lg:hidden">
                    <a href="{{ route('web.blog') }}" aria-label="Visit our blog"
                        class="inline-flex items-center gap-3 px-8 py-2.5 border border-[#2c4294] text-[#2c4294] rounded-lg font-bold hover:bg-[#2c4294] hover:text-white transition-all duration-300 group">
                        আরও দেখুন
                        <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </section>
        <!-- Global Tailwind CSS Dynamic Reusable Confirmation Modal -->
        <div id="confirmModal"
            class="hidden fixed inset-0 z-[10000] flex items-center justify-center p-4 backdrop-blur-sm bg-black/60 transition-all duration-300 select-none">

            <!-- Modal Card Container -->
            <div
                class="bg-white rounded-3xl p-6 border border-gray-100 max-w-sm w-full text-center shadow-2xl scale-95 opacity-0 transition-all duration-300">

                <!-- Dynamic Warning/Status Icon Container -->
                <div id="confirmModalIcon"
                    class="w-16 h-16 bg-red-50 text-red-500 rounded-full mx-auto flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <!-- Dynamic Header Title -->
                <h4 id="confirmModalTitle" class="font-extrabold text-gray-800 text-lg mb-2">আপনি কি নিশ্চিত?</h4>

                <!-- Dynamic Description Text -->
                <p id="confirmModalText" class="text-gray-500 text-xs leading-relaxed mb-6">
                    আপনি কি সত্যিই এই কাজটি করতে চান?
                </p>

                <!-- Action Buttons (Cancel & Dynamic Confirm) -->
                <div class="flex gap-3">
                    <button type="button" onclick="closeConfirmModal()"
                        class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all">
                        বাতিল
                    </button>
                    <button type="button" id="confirmModalBtn" onclick="executeConfirmAction()"
                        class="w-1/2 bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-red-500/10">
                        হ্যাঁ, মুছে ফেলুন
                    </button>
                </div>

            </div>
        </div>
        @php
            $page = $allMeta->get('home');
        @endphp
        @if($page)
            @include('frontend.partials.page_descriptions', ['model' => $page])
        @endif
        @include('frontend.partials.location_modal')

    @endif
    <div class="w-full bg-[#2c4294] py-6 px-4 shadow-md select-none">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8">

            <div class="flex-shrink-0 text-white text-xl md:text-4xl">
                <i class="fa-solid fa-envelope-circle-check" aria-hidden="true"></i>
            </div>

            <p
                class="text-white text-base md:text-lg lg:text-xl font-extrabold uppercase tracking-wider text-center md:text-left">
                আপনার ইনবক্সে ম্যাচিং প্রপার্টি পান
            </p>

            <div class="flex-shrink-0">
                <!-- IMPROVED: hover:text-[#2ba351] changed to hover:text-[#2c4294] -->
                <a href="{{ route('requirements.create') }}"
                    class="inline-block border border-white hover:bg-white hover:text-[#2c4294] text-white px-5 py-2.5 rounded-lg text-[10px] md:text-xs font-black uppercase tracking-wider transition-all">
                    আপনার চাহিদা আমাদের জানান
                </a>
            </div>

        </div>
    </div>
@endsection
@push('scripts')

    @include('frontend.partials.project_scripts')
    <!-- Select2 JS CDN (Place before initialization script) -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {

            // [FIXED] Force search box to always show even for few items (minimumResultsForSearch: 0)
            $('#home-category-select, #home-company-select').select2({
                width: '100%',
                minimumResultsForSearch: 0
            });

            // Sidebar filter selects
            $('#filter-company, #filter-category, #filter-destination').select2({
                width: '100%',
                minimumResultsForSearch: 0
            });

            // Mobile filter selects
            $('#mob-filter-company, #mob-filter-category, #mob-filter-destination').select2({
                width: '100%',
                minimumResultsForSearch: 0
            });

        });
    </script>
    <script>
        // ── Hero Banner Slider Auto-Play & Navigation (Failsafe Script) ──
        let currentSlideIndex = 0;
        let slideInterval;

        function showHeroSlide(index) {
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');

            if (slides.length === 0) return;

            if (index >= slides.length) index = 0;
            if (index < 0) index = slides.length - 1;

            currentSlideIndex = index;

            slides.forEach((slide, idx) => {
                if (idx === currentSlideIndex) {
                    slide.classList.remove('opacity-0', 'z-0');
                    slide.classList.add('opacity-100', 'z-10');
                } else {
                    slide.classList.remove('opacity-100', 'z-10');
                    slide.classList.add('opacity-0', 'z-0');
                }
            });

            dots.forEach((dot, idx) => {
                if (idx === currentSlideIndex) {
                    dot.classList.remove('bg-white/50');
                    dot.classList.add('bg-[#2c4294]', 'scale-110');
                } else {
                    dot.classList.remove('bg-[#2c4294]', 'scale-110');
                    dot.classList.add('bg-white/50');
                }
            });
        }

        function nextHeroSlide() {
            showHeroSlide(currentSlideIndex + 1);
        }

        window.goToSlide = function (index) {
            clearInterval(slideInterval);
            showHeroSlide(index);
            startHeroAutoplay();
        };

        function startHeroAutoplay() {
            const slides = document.querySelectorAll('.hero-slide');
            if (slides.length > 1) {
                slideInterval = setInterval(nextHeroSlide, 3000); // ৩ সেকেন্ড পরপর
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            startHeroAutoplay();
        });

        function handleVideoPlay(vidId, thumbId, infoId) {
            const video = document.getElementById(vidId);
            const thumb = document.getElementById(thumbId);
            const info = infoId ? document.getElementById(infoId) : null;

            if (video) {
                thumb.style.display = 'none';
                if (info) info.style.display = 'none';

                video.classList.remove('hidden');

                const playPromise = video.play();

                if (playPromise !== undefined) {
                    playPromise.then(_ => {
                        console.log("Video started playing");
                    }).catch(error => {
                        console.error("Video play failed:", error);
                        video.controls = true;
                    });
                }
            }
        }


        function playGridVideo(projectId) {
            const thumbContainer = document.getElementById('thumb-container-' + projectId);
            const videoElement = document.getElementById('video-' + projectId);

            if (videoElement) {
                thumbContainer.style.display = 'none';
                videoElement.classList.remove('hidden');
                videoElement.play();

                fetch('/increment-video-view/' + projectId)
                    .then(response => response.json())
                    .then(data => {
                        console.log('View count updated successfully');
                    })
                    .catch(error => console.error('Error updating views:', error));
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const allCards = document.querySelectorAll('.project-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const filterValue = button.getAttribute('data-filter');

                    const isLiveFilter = button.closest('section').querySelector('h2').innerText
                        .includes('Live');
                    const targetSection = isLiveFilter ? 'live' : 'grid';

                    const sectionButtons = button.parentElement.querySelectorAll('.filter-btn');
                    sectionButtons.forEach(btn => {
                        if (targetSection === 'grid') {
                            btn.classList.remove('border-[#2c4294]', 'text-gray-900');
                            btn.classList.add('border-transparent', 'text-gray-700');
                        } else {
                            btn.classList.remove('bg-[#2c4294]', 'text-white',
                                'border-[#2c4294]', 'shadow-md');
                            btn.classList.add('border-gray-200', 'text-gray-500',
                                'bg-white');
                        }
                    });

                    if (targetSection === 'grid') {
                        button.classList.add('border-[#2c4294]', 'text-gray-900');
                        button.classList.remove('border-transparent', 'text-gray-700');
                    } else {
                        button.classList.add('bg-[#2c4294]', 'text-white', 'border-[#2c4294]',
                            'shadow-md');
                        button.classList.remove('border-gray-200', 'text-gray-500', 'bg-white');
                    }

                    let shownCount = 0;
                    const limit = (targetSection === 'live') ? 3 : 6;

                    allCards.forEach(card => {
                        const cardSection = card.getAttribute('data-section');

                        if (cardSection !== targetSection) return;

                        const categoryId = card.getAttribute('data-category');
                        const slideWrapper = card.closest('.swiper-slide');
                        const isMatch = (filterValue === 'all' || categoryId ==
                            filterValue);

                        if (isMatch && shownCount < limit) {
                            slideWrapper.style.display = 'block';
                            setTimeout(() => slideWrapper.style.opacity = '1', 10);
                            shownCount++;
                        } else {
                            slideWrapper.style.opacity = '0';
                            slideWrapper.style.display = 'none';
                        }
                    });

                    document.querySelectorAll('.swiper').forEach(s => {
                        if (s.swiper) s.swiper.update();
                    });
                });
            });
        });
        document.addEventListener('click', async function (e) {
            const favBtn = e.target.closest('.btn-favorite-toggle');
            if (favBtn) {
                e.preventDefault();
                const projectId = favBtn.dataset.id;
                const icon = favBtn.querySelector('i');

                try {
                    const response = await fetch("{{ route('projects.favorite.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            project_id: projectId
                        })
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        if (result.status === 'added') {
                            icon.className = 'fa-solid fa-heart text-red-500 text-xs pointer-events-none';
                            favBtn.setAttribute('title', 'Remove from Favorites');
                        } else {
                            icon.className = 'fa-regular fa-heart text-gray-700 text-xs pointer-events-none';
                            favBtn.setAttribute('title', 'Add to Favorites');
                        }
                    } else {
                        if (response.status === 401) {
                            // ⚠️ [NEW] Trigger the gorgeous green login prompt modal instead of browser alert
                            showConfirmModal({
                                title: 'Login Required',
                                text: 'Please login to save this property to your favorites list.',
                                btnText: 'Login Now',
                                btnClass: 'bg-[#2ba351] hover:bg-[#1a285a] shadow-[#2ba351]/10',
                                iconClass: 'bg-green-50 text-[#2ba351]',
                                iconHtml: '<i class="fa-solid fa-circle-user"></i>',
                                callback: function () {
                                    // Redirect to login page preserving the current URL as redirect query param
                                    const currentUrl = encodeURIComponent(window.location.href);
                                    window.location.href = "{{ route('company.login') }}?redirect=" + currentUrl;
                                }
                            });
                        } else {
                            alert(result.message || 'Something went wrong.');
                        }
                    }
                } catch (error) {
                    console.error('Error toggling favorite:', error);
                    alert('Connection error. Please try again.');
                }
            }
        });
    </script>
@endpush