@extends('layouts.front')

@section('title', 'Our Exclusive Events | Bhaiya Hotels | Bhaiya Group')

@section('content')
<style>
    .slant-badge {
        clip-path: polygon(0 0, 90% 0, 100% 100%, 0% 100%);
    }


    .hotelSwiper .swiper-pagination-bullet {
        width: 6px;
        height: 6px;
        background: #4b5563 !important;
        opacity: 0.6;
        margin: 0 6px !important;
        transition: all 0.3s ease;
    }

    .hotelSwiper .swiper-pagination-bullet-active {
        background: #2563eb !important;
        opacity: 1;
        position: relative;
        outline: 2px solid #2563eb;
        outline-offset: 3px;
        transform: scale(1.1);
    }

    .hotelSwiper {
        height: auto !important;
    }

    .swiper-wrapper {
        height: inherit !important;
        padding-bottom: 50px
    }

    .project-description-content ul {
        list-style-type: disc !important;
        margin-left: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }

    .project-description-content li {
        margin-bottom: 0.5rem !important;
    }

    .project-description-content h2 {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        color: #111 !important;
        margin-bottom: 1.25rem !important;
        border-left: 3px solid #224194;
        padding-left: 1rem;
    }

    table {
        width: 100%;
    }

    table th,
    table td {
        border: 1px solid black !important;
        padding: 3px 5px;
    }

    ul {
        list-style: disc;
        padding-left: 20px;
    }

    ol {
        list-style: decimal;
        padding-left: 20px;
    }

    .blog-content h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .blog-content h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .blog-content h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .blog-content h4 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .blog-content h5 {
        font-size: 18px;
        font-weight: 600;
    }

    .blog-content h6 {
        font-size: 16px;
        font-weight: 600;
    }

    .blog-content p {
        margin-bottom: 16px;
        line-height: 1.8;
    }

    .blog-content ul {
        list-style: disc;
        padding-left: 24px;
        margin-bottom: 16px;
    }

    .blog-content ol {
        list-style: decimal;
        padding-left: 24px;
        margin-bottom: 16px;
    }

    .blog-content li {
        margin-bottom: 0;
    }

    .blog-content strong {
        font-weight: 700;
    }

    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 16px 0;
    }

    .blog-content h1,
    .blog-content h2,
    .blog-content h3,
    .blog-content h4,
    .blog-content h5 {
        margin: 0;
        margin-top: 10px;
    }

    p {
        margin: 0;
    }

    .blog-content * {
        font-family: "Trebuchet MS", "sans-serif" !important;
        color: black;
    }

    .blog-content h2,
    .blog-content h3 {
        font-weight: 400;
        font-size: 20px;
        line-height: 26px;
        margin-bottom: 5px;
    }

    .blog-content p {
        font-size: 15px;
        line-height: 23px;
        text-align: justify;
        color: #333333fa;
    }
</style>


<!-- ১. Hero Section (Olive Background) -->
<section class="  " style="border: 1px solid gainsboro;margin-bottom: 20px;">
    <div class="container mx-auto px-6 ">
        <!-- Image Overlap using Negative Margin -->
        <div class="flex   gap-2 text-base  px-4 py-2  border-gray-100">
            <a href="{{ url('/') }}" class="text-gray-600 hover:text-black">
                Home
            </a>

            <span class="text-gray-300">›</span>

            <a href="{{ route('web.project') ?? '#' }}" class="text-gray-600 hover:text-black">
                {{ $project->category->title ?? 'Projects' }}
            </a>

            <span class="text-gray-300">›</span>

            <span class="text-black font-semibold">
                {{ $project->title }}
            </span>

        </div>
    </div>
</section>

<!-- HERO SECTION -->
<section class="bg-white py-6 md:py-12 ">
    <div class="container mx-auto px-4 md:px-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- LEFT: Video -->
            <div class="lg:col-span-8 flex flex-col">
                <!-- Header tab: sits ABOVE the video, flush left -->
                <div class="flex items-stretch h-9 w-fit mb-0">
                    <div
                        class="slant-badge bg-[#DFE8FF] pl-4 pr-10 flex items-center gap-2 border border-gray-200 border-b-0 border-l-0">
                        <div class="border border-gray-200 border-b-0 px-1 flex items-center gap-1.5">
                            <div class="w-12 h-12 flex items-center justify-center">
                                <svg width="80" height="80" viewBox="0 0 56 56" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M20.9974 38.5H45.5C51.2986 38.5 56 33.7986 56 28C56 22.2014 51.2986 17.5 45.5 17.5H20.9974C18.7282 15.7928 15.9647 14.8713 13.125 14.875C5.88087 14.875 0 20.7559 0 28C0 35.2441 5.88087 41.125 13.125 41.125C16.0772 41.125 18.8037 40.1476 20.9974 38.5Z"
                                        fill="#F51E1E" />

                                    <path
                                        d="M13.125 39.375C19.4072 39.375 24.5 34.2822 24.5 28C24.5 21.7178 19.4072 16.625 13.125 16.625C6.84276 16.625 1.75 21.7178 1.75 28C1.75 34.2822 6.84276 39.375 13.125 39.375Z"
                                        fill="white" />

                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M10.066 21.1191C9.93294 21.042 9.78196 21.0014 9.62821 21.0011C9.47446 21.0008 9.32334 21.041 9.19002 21.1176C9.0567 21.1942 8.94587 21.3045 8.86864 21.4374C8.79141 21.5704 8.75049 21.7213 8.75001 21.8751V34.1251C8.74942 34.2792 8.78966 34.4308 8.86664 34.5644C8.94361 34.698 9.05458 34.8088 9.18826 34.8856C9.32195 34.9624 9.47357 35.0024 9.62774 35.0016C9.78191 35.0008 9.93312 34.9592 10.066 34.8811L20.566 28.7561C20.6982 28.679 20.8079 28.5687 20.8841 28.436C20.9603 28.3034 21.0005 28.1531 21.0005 28.0001C21.0005 27.8471 20.9603 27.6968 20.8841 27.5641C20.8079 27.4314 20.6982 27.3211 20.566 27.2441L10.066 21.1191Z"
                                        fill="#F51E1E" />

                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M47.25 27.125V25.375H49C49.2321 25.375 49.4546 25.2828 49.6187 25.1187C49.7828 24.9546 49.875 24.7321 49.875 24.5C49.875 24.2679 49.7828 24.0454 49.6187 23.8813C49.4546 23.7172 49.2321 23.625 49 23.625H46.375C46.1429 23.625 45.9204 23.7172 45.7563 23.8813C45.5922 24.0454 45.5 24.2679 45.5 24.5V31.5C45.5 31.7321 45.5922 31.9546 45.7563 32.1187C45.9204 32.2828 46.1429 32.375 46.375 32.375H49C49.2321 32.375 49.4546 32.2828 49.6187 32.1187C49.7828 31.9546 49.875 31.7321 49.875 31.5C49.875 31.2679 49.7828 31.0454 49.6187 30.8813C49.4546 30.7172 49.2321 30.625 49 30.625H47.25V28.875H49C49.2321 28.875 49.4546 28.7828 49.6187 28.6187C49.7828 28.4546 49.875 28.2321 49.875 28C49.875 27.7679 49.7828 27.5454 49.6187 27.3813C49.4546 27.2172 49.2321 27.125 49 27.125H47.25ZM42.0262 24.2874L41.125 27.8924L40.2238 24.2874C40.166 24.0637 40.0222 23.872 39.8237 23.7539C39.6251 23.6359 39.3879 23.6012 39.1639 23.6573C38.9398 23.7134 38.7471 23.8559 38.6276 24.0536C38.5082 24.2513 38.4717 24.4882 38.5262 24.7126L40.2762 31.7126C40.3237 31.9018 40.4329 32.0698 40.5867 32.1897C40.7405 32.3097 40.9299 32.3749 41.125 32.3749C41.3201 32.3749 41.5095 32.3097 41.6633 32.1897C41.8171 32.0698 41.9263 31.9018 41.9738 31.7126L43.7238 24.7126C43.7783 24.4882 43.7418 24.2513 43.6224 24.0536C43.5029 23.8559 43.3102 23.7134 43.0861 23.6573C42.8621 23.6012 42.6249 23.6359 42.4263 23.7539C42.2278 23.872 42.084 24.0637 42.0262 24.2874ZM36.75 31.5V24.5C36.75 24.2679 36.6578 24.0454 36.4937 23.8813C36.3296 23.7172 36.1071 23.625 35.875 23.625C35.6429 23.625 35.4204 23.7172 35.2563 23.8813C35.0922 24.0454 35 24.2679 35 24.5V31.5C35 31.7321 35.0922 31.9546 35.2563 32.1187C35.4204 32.2828 35.6429 32.375 35.875 32.375C36.1071 32.375 36.3296 32.2828 36.4937 32.1187C36.6578 31.9546 36.75 31.7321 36.75 31.5ZM28.875 24.5V31.5C28.875 31.7321 28.9672 31.9546 29.1313 32.1187C29.2954 32.2828 29.5179 32.375 29.75 32.375H32.375C32.6071 32.375 32.8296 32.2828 32.9937 32.1187C33.1578 31.9546 33.25 31.7321 33.25 31.5C33.25 31.2679 33.1578 31.0454 32.9937 30.8813C32.8296 30.7172 32.6071 30.625 32.375 30.625H30.625V24.5C30.625 24.2679 30.5328 24.0454 30.3687 23.8813C30.2046 23.7172 29.9821 23.625 29.75 23.625C29.5179 23.625 29.2954 23.7172 29.1313 23.8813C28.9672 24.0454 28.875 24.2679 28.875 24.5Z"
                                        fill="white" />
                                </svg>
                            </div>
                        </div>
                        <span class="text-[#174BD4] text-base font-medium whitespace-nowrap">{{ $project->title }}</span>
                    </div>
                </div>

                <!-- Video Thumbnail Container -->
                <div class="relative w-full overflow-hidden shadow-2xl group cursor-pointer bg-black"
                    id="details-video-wrapper" onclick="playDetailsInlineVideo()"
                    style="aspect-ratio: 16/9.4; margin-top: -1px;">

                    @php
                    $images = json_decode($project->img_paths);
                    $firstImage =
                    is_array($images) && count($images) > 0 ? $images[0] : 'assets/images/placeholder.jpg';
                    @endphp


                    <!-- <div id="details-thumb" class="absolute inset-0 z-20 hidden">
                                                                <img src="{{ asset($firstImage) }}" alt="Salt Bay"
                                                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />

                                                                <div class="absolute inset-0 bg-black/15 group-hover:bg-black/25 transition-all duration-300">
                                                                </div>


                                                                <div class="absolute inset-0 flex items-center justify-center">
                                                                    <div
                                                                        class="w-16 h-16 md:w-24 md:h-24 bg-white/90 rounded-full flex items-center justify-center shadow-[0_0_50px_rgba(0,0,0,0.3)] group-hover:scale-110 transition-all duration-500">
                                                                        <i class="fa-solid fa-play text-[#2c4294] text-xl md:text-xl ml-1"></i>
                                                                    </div>
                                                                </div>
                                                            </div> -->

                    <!-- ২. ইউটিউব আইফ্রেম: এটি শুরুতে লুকানো (hidden) থাকবে -->
                    <!-- <iframe id="details-youtube-iframe" class="w-full h-full   z-10"
                                                                src="https://www.youtube.com/embed/S2O8uWvM4-I?si=yv71p8zNnF4fSAnT" title="YouTube video player"
                                                                frameborder="0"
                                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                                                            </iframe> -->
                    <video controls class="w-full h-full"
                        src="https://www.shutterstock.com/shutterstock/videos/3807749569/preview/stock-footage-wide-cctv-shot-of-surveillance-camera-observing-group-of-scam-call-center-employees-in-office.mp4"></video>
                </div>
            </div>

            <!-- RIGHT: Interest Form -->
            <div class="lg:col-span-4 md:mt-8">
                @auth
                <div class="mb-4 p-4 bg-white rounded-lg border-2 border-[#2C4798] shadow-sm">
                    <a href="{{ route('content.edit', ['type' => 'project', 'id' => $project->id]) }}" target="_blank"
                        class="w-full bg-[#2C4798] hover:bg-[#1a368a] text-white py-3 rounded-md font-bold text-base flex items-center justify-center gap-2 transition-all shadow-md active:scale-95">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Edit This Project
                    </a>
                </div>
                @endauth
                <div class="bg-[#DFE8FF] p-7 md:p-8 rounded-lg border border-blue-50">
                    <h2 class="text-xl md:text-xl font-bold text-gray-800 leading-snug mb-6">
                        I am interested in this project.
                    </h2>

                    @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-base">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <input type="text" name="name" placeholder="Name (required)" required
                            class="w-full bg-white px-5 py-3.5 text-base outline-none border border-transparent placeholder:text-gray-400 shadow-sm focus:ring-2 focus:ring-[#2c4294]/20 transition-all"
                            value="{{ old('name') }}" />

                        <input type="email" name="email" placeholder="Email Address (required)" required
                            class="w-full bg-white px-5 py-3.5 text-base outline-none border border-transparent placeholder:text-gray-400 shadow-sm focus:ring-2 focus:ring-[#2c4294]/20 transition-all"
                            value="{{ old('email') }}" />

                        <input type="tel" name="phone" placeholder="Phone Number (optional)"
                            class="w-full bg-white px-5 py-3.5 text-base outline-none border border-transparent placeholder:text-gray-400 shadow-sm focus:ring-2 focus:ring-[#2c4294]/20 transition-all"
                            value="{{ old('phone') }}" />

                        <input type="text" name="designation" placeholder="Designation (optional)"
                            class="w-full bg-white px-5 py-3.5 text-base outline-none border border-transparent placeholder:text-gray-400 shadow-sm focus:ring-2 focus:ring-[#2c4294]/20 transition-all"
                            value="{{ old('designation') }}" />

                        <textarea name="message" placeholder="What do you want to know? (optional)" rows="4"
                            class="w-full bg-white px-5 py-3.5 text-base outline-none border border-transparent placeholder:text-gray-400 shadow-sm focus:ring-2 focus:ring-[#2c4294]/20 transition-all resize-none">{{ old('message') }}</textarea>

                        <button type="submit"
                            class="cursor-pointer w-full bg-[#2C4798] hover:bg-[#1e2d6b] text-white py-4 rounded-xl font-bold text-base flex items-center justify-center gap-3 transition-all">
                            <i class="fa-solid fa-paper-plane text-base"></i>
                            Submit Now
                        </button>
                    </form>

                    @if (session('success'))
                    <p class="mt-4 text-green-600 text-base font-medium text-center">
                        {{ session('success') }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@php
$sliderImages = json_decode($project->img_paths) ?? [];
@endphp

@if (count($sliderImages) > 0)
<section class="hidden bg-[#f2f6ff] py-12 md:pb-6 md:py-16 overflow-hidden">
    <div class="container mx-auto px-4 md:px-10">
        <!-- Header Info -->
        <div class="mb-10">
            <h2 class="text-[28px] md:text-xl font-bold mb-3 text-gray-900">
                {{ $project->title ?? 'Featured Projects' }}
            </h2>
            <div class="space-y-1 text-gray-600">
                <div class="flex items-center gap-2 text-base md:text-base">
                    <i class="fa-solid fa-location-dot text-blue-600"></i>
                    <span>{{ $project->location ?? 'Location Name' }}</span>
                </div>
                <div class="flex items-center gap-2 text-base md:text-base">
                    <i class="fa-solid fa-vector-square text-blue-600"></i>
                    <span>{{ $project->name ?? 'Project Detail' }}</span>
                </div>
            </div>
        </div>

        <!-- Slider Wrapper -->
        <div class="relative">
            <!-- Navigation Buttons -->
            <div
                class="custom-prev absolute left-4 top-[40%] -translate-y-1/2 z-20 flex w-10 h-10 bg-black/30 hover:bg-black/50 text-white rounded-full transition-all items-center justify-center cursor-pointer shadow-md">
                <i class="fa-solid fa-arrow-left text-base"></i>
            </div>

            <div
                class="custom-next absolute right-4 top-[40%] -translate-y-1/2 z-20 flex w-10 h-10 bg-white hover:bg-gray-100 text-black rounded-full transition-all items-center justify-center shadow-lg cursor-pointer">
                <i class="fa-solid fa-arrow-right text-base"></i>
            </div>

            <div class="swiper hotelSwiper">
                <div class="swiper-wrapper">
                    @foreach ($sliderImages as $img)
                    <div class="swiper-slide">
                        <a href="{{ asset($img) }}" class="glightbox block relative group overflow-hidden">
                            <img src="{{ asset($img) }}"
                                class="w-full aspect-square object-cover transition-transform duration-700 group-hover:scale-110 shadow-sm"
                                alt="{{ $project->title }}" />

                            <div
                                class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <i class="fa-solid fa-expand text-white text-xl"></i>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="swiper-pagination !bottom-2"></div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- PROJECT DETAILS SECTION -->
<section class="bg-white py-16 md:py-24">
    <div class="container mx-auto px-6 md:px-10">
        <!-- Main Grid Container -->
        <div class="grid grid-cols-1 lg:grid-cols-12 items-stretch">
            <!-- LEFT COLUMN (7/12) -->
            <div class="lg:col-span-7 lg:border-r border-gray-200 flex flex-col">
                <div class="lg:pr-16 pb-14">
                    <!-- 1. Project At a Glance -->
                    @php
                    $extraData = array_filter(json_decode($project->extra, true) ?? []);
                    $features = json_decode($project->features, true) ?? [];

                    $allFeatureIcons = \App\Models\Content::whereIn('type', ['project_glance', 'features'])
                    ->where('status', 1)
                    ->get();
                    @endphp

                    @if (count($extraData) > 0)
                    <div class="mb-14">
                        <div class="border-l-[3px] border-[#224194] pl-4 mb-8">
                            <h2 class="text-xl font-bold text-gray-900">
                                Project At a Glance
                            </h2>
                        </div>
                        <div class="grid grid-cols-2 gap-y-6">
                            @foreach ($extraData as $key => $value)
                            @php
                            $info = $allFeatureIcons
                            ->where('type', 'project_glance')
                            ->where('title', $key)
                            ->first();
                            @endphp
                            <div class="flex items-center gap-4 text-[#3F3F3F] font-medium">
                                <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                                    @if ($info && $info->img_path)
                                    <img src="{{ asset($info->img_path) }}"
                                        class="w-full h-full object-contain" alt="{{ $key }}">
                                    @else
                                    <i
                                        class="{{ $info->name ?? 'fa-solid fa-circle-check' }} text-[#224194] text-lg text-center"></i>
                                    @endif
                                </div>
                                <span class="text-base">{{ $key }}: {{ $value }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif


                    @if (count($features) > 0)
                    <div class="mb-14">
                        <div class="border-l-[3px] border-[#224194] pl-4 mb-8">
                            <h2 class="text-xl font-bold text-gray-900">
                                Project Features
                            </h2>
                        </div>
                        <div class="grid grid-cols-2 gap-y-6">
                            @foreach ($features as $title)
                            @php
                            $info = $allFeatureIcons
                            ->where('type', 'features')
                            ->where('title', $title)
                            ->first();
                            @endphp
                            <div class="flex items-center gap-4 text-[#3F3F3F] font-medium">
                                <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                                    @if ($info && $info->img_path)
                                    <img src="{{ asset($info->img_path) }}"
                                        class="w-full h-full object-contain" alt="{{ $title }}">
                                    @else
                                    <i
                                        class="{{ $info->name ?? 'fa-solid fa-circle-check' }} text-[#224194] text-lg text-center"></i>
                                    @endif
                                </div>
                                <span class="text-base">{{ $title }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- 3. Dynamic Description Tabs & PDF Section -->
                    <div class="mb-14">
                        @php
                        $titles = json_decode($project->body_titles, true) ?? [];
                        $bodyFields = ['body', 'body_2', 'body_3', 'body_4'];
                        $tabGroups = [];
                        $currentGroupId = null;

                        foreach ($bodyFields as $field) {
                        $text = $project->$field;
                        if (!empty(strip_tags($text))) {
                        if (!empty($titles[$field])) {
                        $currentGroupId = $field;
                        $tabGroups[$currentGroupId] = [
                        'label' => $titles[$field],
                        'contents' => [$text],
                        ];
                        } else {
                        if ($currentGroupId === null) {
                        $currentGroupId = 'default_desc';
                        $tabGroups[$currentGroupId] = [
                        'label' => 'Description',
                        'contents' => [],
                        ];
                        }
                        $tabGroups[$currentGroupId]['contents'][] = $text;
                        }
                        }
                        }
                        @endphp

                        @if (count($tabGroups) > 0)
                        {{-- <div class="border-l-[3px] border-[#224194] pl-4 mb-8">
                                    <h2 class="text-xl font-bold text-gray-900">
                                        Description
                                    </h2>
                                </div> --}}
                        <div class="flex flex-wrap gap-2 mb-6 items-center">
                            @foreach ($tabGroups as $id => $group)
                            <button onclick="toggleDescriptionTab('{{ $id }}')"
                                id="desc-btn-{{ $id }}"
                                class="desc-tab-btn px-6 py-2.5 rounded-md font-bold text-base uppercase transition-all duration-300
            {{ $loop->first ? 'bg-[#224194] text-white shadow-md' : 'bg-white border border-[#224194] text-gray-600' }}">
                                {{ $group['label'] }}
                            </button>
                            @endforeach

                            @if ($project->img_path)
                            <button onclick="toggleDescriptionTab('pdf-viewer')" id="desc-btn-pdf-viewer"
                                class="desc-tab-btn px-6 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-md font-bold text-base uppercase hover:bg-[#224194] transition-all">
                                <i class="fa-solid fa-file-pdf mr-1"></i> View Brochure
                            </button>
                            @endif
                        </div>

                        <div class="bg-white  overflow-hidden">
                            @foreach ($tabGroups as $id => $group)
                            <div id="desc-pane-{{ $id }}"
                                class="blog-content desc-tab-pane {{ $loop->first ? '' : 'hidden' }}">
                                {!! htmlspecialchars_decode(implode('
                                <hr class="my-8 border-gray-100 mt-4 mb-4">', $group['contents'])) !!}
                            </div>
                            @endforeach

                            @if ($project->img_path)
                            <div id="desc-pane-pdf-viewer" class="desc-tab-pane hidden">
                                <div
                                    class="relative w-full h-[70vh] md:h-[80vh] lg:h-[800px] bg-gray-50 border border-gray-100 rounded-xl overflow-hidden shadow-inner">

                                    <iframe src="{{ asset($project->img_path) }}#toolbar=1&navpanes=0"
                                        class="absolute inset-0 w-full h-full border-none"
                                        style="width: 100%; height: 100%;" type="application/pdf">

                                        <div
                                            class="flex flex-col items-center justify-center h-full p-6 text-center">
                                            <i class="fa-solid fa-file-pdf text-4xl text-red-500 mb-2"></i>
                                            <p class="text-gray-600 mb-4 text-base">PDF viewing is not supported
                                                on your browser.</p>
                                            <a href="{{ asset($project->img_path) }}" download
                                                class="bg-[#224194] text-white px-5 py-2 rounded-md font-bold text-base">
                                                Download PDF to View
                                            </a>
                                        </div>
                                    </iframe>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>


                </div>

                <!-- 3. Download Section -->
                @if ($project->url)
                <div class="border-t border-gray-200 pt-12 lg:pr-16 flex-grow">
                    <div class="border-l-[3px] border-[#224194] pl-4 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 leading-tight">
                            Download for more detailed information.
                        </h2>
                    </div>
                    <a href="{{ $project->url }}" target="_blank"
                        class="inline-flex items-center gap-3 bg-[#224194] text-white px-8 py-3.5 rounded-lg font-bold text-base hover:opacity-90 transition">
                        <i class="fa-solid fa-cloud-arrow-down text-lg"></i>
                        Download Now
                    </a>
                </div>
                @endif
            </div>

            <!-- RIGHT COLUMN (5/12) -->
            <div class="lg:col-span-5 lg:pl-16 pt-16 lg:pt-0">
                <h2 class="text-xl font-bold text-gray-900 mb-8">
                    Related Projects
                </h2>

                <div class="divide-y divide-gray-100 border-b border-gray-100">
                    @forelse($relatedProjects as $rp)
                    @php
                    $rpImages = json_decode($rp->img_paths);
                    $rpThumb =
                    is_array($rpImages) && count($rpImages) > 0
                    ? $rpImages[0]
                    : 'assets/images/placeholder.jpg';
                    @endphp

                    <!-- Project Card - Clickable -->
                    <a href="{{ route('project.details', $rp->name) }}" aria-label="View project details"
                        class="flex items-start gap-5 py-6 first:pt-0 group cursor-pointer">
                        <!-- Thumbnail -->
                        <div class="w-32 h-24 flex-shrink-0 overflow-hidden rounded-md bg-gray-50 border">
                            <img src="{{ asset($rpThumb) }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                alt="{{ $rp->title }}" />
                        </div>

                        <!-- Content -->
                        <div class="space-y-1.5">
                            <h4
                                class="font-bold text-gray-900 text-lg leading-snug group-hover:text-[#224194] transition-colors">
                                {{ Str::limit($rp->title, 45) }}
                            </h4>
                            <div class="flex items-center gap-2 text-gray-500 text-base">
                                <i class="fa-solid fa-location-dot"></i>
                                <span>{{ $rp->location ?? 'Location' }}</span>
                            </div>
                            <div class="inline-flex items-center gap-2 text-[#224194] font-bold text-base mt-1">
                                View Details
                                <i
                                    class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                    @empty
                    <p class="py-10 text-gray-400">No related projects found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
<script>
    function playDetailsInlineVideo() {
        const thumb = document.getElementById('details-thumb');
        const iframe = document.getElementById('details-youtube-iframe');

        if (iframe) {
            thumb.style.display = 'none';

            iframe.classList.remove('hidden');

            let currentSrc = iframe.src;
            if (currentSrc.indexOf('autoplay=1') === -1) {
                iframe.src += (currentSrc.indexOf('?') === -1 ? '?' : '&') + 'autoplay=1';
            }

            fetch('/increment-video-view/{{ $project->id }}')
                .then(response => response.json())
                .catch(err => console.error('Error updating views:', err));
        }
    }
</script>
<script>
    function toggleDescriptionTab(tabId) {
        document.querySelectorAll('.desc-tab-pane').forEach(el => el.classList.add('hidden'));

        document.querySelectorAll('.desc-tab-btn').forEach(btn => {
            btn.classList.remove('bg-[#224194]', 'text-white', 'shadow-md');
            btn.classList.add('bg-white', 'border', 'text-gray-600');
        });

        document.getElementById('desc-pane-' + tabId).classList.remove('hidden');

        let activeBtn = document.getElementById('desc-btn-' + tabId);
        activeBtn.classList.remove('bg-white', 'border', 'text-gray-600');
        activeBtn.classList.add('bg-[#224194]', 'text-white', 'shadow-md');
    }
</script>
