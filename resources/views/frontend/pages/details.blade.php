@extends('layouts.front')
@section('meta')
    @include('frontend.partials.meta', [
        'pageKey' => 'project',
        'title' => $project->meta_title ?? $project->title,
        'description' => $project->meta_description ?? $project->title,
        'keywords' => $project->meta_keywords,
        'image' => asset($project->img_path)
    ])
@endsection
@section('content')
    <section class=" " style="border: 1px solid gainsboro;margin-bottom: 20px;">
        <div class="container mx-auto px-4">
            <!-- Image Overlap using Negative Margin -->
            <div class="flex gap-2 text-base py-2 border-gray-100">

                <a href="{{ url('/') }}" class="text-gray-600 hover:text-black">
                    Home
                </a>

                <span class="text-gray-300">›</span>

                <a href="{{ route('web.project') ?? '#' }}" class="text-gray-600 hover:text-black">
                    Properties
                </a>

                <span class="text-gray-300">›</span>

                <span class="text-black font-semibold truncate">
                    {{ $project->slug    }}
                </span>

            </div>
        </div>
    </section>
    <div class="container py-6 mx-auto px-4">


        <div class="mb-6">
            <h1 class="text-xl md:text-xl font-extrabold text-gray-900 leading-tight flex flex-wrap items-center gap-3">
                {{ $project->title }}

            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">



            <div class="lg:col-span-8 flex flex-col gap-6 relative">

                @php
                    $images = $project->img_paths ?? [];
                    $firstImage = $project->img_path ?? 'assets/images/placeholder.webp';

                    $featuresList = $project->features ?? [];
                    $bedrooms = '00';
                    $baths = '00';
                    $size = '00';
                    foreach ($featuresList as $f) {
                        if (is_array($f) && isset($f['key'])) {
                            $keyLower = strtolower($f['key']);
                            if ($keyLower == 'bedrooms')
                                $bedrooms = $f['value'];
                            if ($keyLower == 'baths')
                                $baths = $f['value'];
                            if (str_contains($keyLower, 'size'))
                                $size = $f['value'];
                        }
                    }

                    $extraFeatures = $project->extra ?? [];
                    $floorPlans = is_array($project->description_1) ? $project->description_1 : [];
                    $locationViews = is_array($project->description_2) ? $project->description_2 : [];
                    $allGalleryImages = array_merge($floorPlans, $locationViews);
                    $mapUrl = $project->url;
                    $isValidEmbed = !empty($mapUrl) && (str_contains($mapUrl, 'google.com/maps/embed') || str_contains($mapUrl, '/maps/embed'));
                @endphp

                <div>
                    <div class="relative w-full rounded-2xl overflow-hidden shadow-sm bg-gray-50"
                        style="aspect-ratio: 16/10;">
                        <img id="mainFeaturedImage" src="{{ asset('storage/' . $firstImage) }}"
                            class="w-full h-full object-cover transition-all duration-300" alt="{{ $project->title }}">
                    </div>
                    @if(!empty($images) && count($images) > 1)
                        <div class="relative mt-3">
                            <div class="flex gap-2 overflow-x-auto py-2 scrollbar-hide" id="thumbnailSlider">
                                @foreach($images as $index => $path)
                                    <button type="button" onclick="switchFeaturedImage('{{ asset('storage/' . $path) }}', this)"
                                        class="thumbnail-btn w-20 h-14 rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-[#2ba351]' : 'border-transparent' }} flex-shrink-0 focus:outline-none transition-all">
                                        <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover" alt="Thumb">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>


                <div class="sticky top-0 bg-white z-30 py-4 pl-3 flex flex-wrap gap-3 border-b border-gray-100 shadow-sm">

                    <a href="#section-overview"
                        class="tab-link px-4 py-2.5 bg-[#1b6e35] text-white rounded-xl text-xs md:text-base font-extrabold border border-[#1b6e35] shadow-sm shadow-[#1b6e35]/10 hover:opacity-95 transition-all whitespace-nowrap">
                        Overview
                    </a>

                    @if(!empty($extraFeatures) && is_array($extraFeatures))
                        <a href="#section-features"
                            class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                            Features
                        </a>
                    @endif

                    @if($project->description)
                        <a href="#section-description"
                            class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                            Description
                        </a>
                    @endif

                    @if(!empty($project->virtual_tour))
                        <a href="#section-virtual-tour"
                            class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">360°
                            Tour</a>
                    @endif

                    @if(!empty($floorPlans))
                        <a href="#section-floor-plan"
                            class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                            Floor Plan
                        </a>
                    @endif

                    @if(!empty($locationViews))
                        <a href="#section-location-view"
                            class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                            Location View
                        </a>
                    @endif

                    @if(!empty($project->video_path))
                        <a href="#section-video"
                            class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                            Video
                        </a>
                    @endif

                    @if($isValidEmbed)
                        <a href="#section-map-view"
                            class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                            Map View
                        </a>
                    @endif

                </div>


                <div id="section-overview"
                    class="scroll-mt-16 bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)]">
                    <div class="flex flex-wrap justify-between items-center gap-4 mb-5 border-b border-gray-100 pb-5">
                        <div>
                            <span class="text-xl font-black text-[#2ba351]">{{ $project->short }}</span>
                            <span class="text-xs text-gray-500 block mt-1">/
                                {{ $project->category->title ?? 'Apartment/Flats for Sale' }}</span>
                        </div>
                        <div class="text-base text-gray-500 flex items-center gap-1.5">
                            <i class="fa-solid fa-location-dot text-gray-400"></i>
                            <span class="font-medium text-gray-700">{{ $project->location }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center pt-2 mb-5">
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-bed text-xl text-gray-400 mb-2"></i>
                            <span class="text-base font-bold text-gray-800">{{ $bedrooms }} Beds</span>
                        </div>
                        <div class="flex flex-col items-center border-x border-gray-100">
                            <i class="fa-solid fa-bath text-xl text-gray-400 mb-2"></i>
                            <span class="text-base font-bold text-gray-800">{{ $baths }} Baths</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <i class="fa-solid fa-ruler-combined text-xl text-gray-400 mb-2"></i>
                            <span class="text-base font-bold text-gray-800">{{ $size }}</span>
                        </div>
                    </div>

                    @if(!empty($featuresList))
                        <div class="border-t border-gray-100 pt-5">

                            <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2"> Property Overview
                            </p>
                            <div class="w-16 h-1 bg-[#2ba351] mb-5"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                                @foreach($featuresList as $key => $val)
                                    @php
                                        if (is_numeric($key) && !is_array($val)) {
                                            $fKey = $val;
                                            $fVal = '';
                                        } else {
                                            $fKey = is_array($val) ? ($val['key'] ?? '') : $key;
                                            $fVal = is_array($val) ? ($val['value'] ?? '') : $val;
                                        }
                                    @endphp
                                    @if($fKey)
                                        <div class="flex justify-between border-b border-gray-50 py-2.5 text-base">
                                            <span class="text-gray-500 font-medium flex items-center gap-1.5">
                                                <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                                                {{ $fKey }}
                                            </span>
                                            <span class="font-bold text-gray-800">{{ $fVal }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>


                {{-- ── 2. FEATURES SECTION ── --}}
                @if(!empty($extraFeatures) && is_array($extraFeatures))
                    <div id="section-features"
                        class="scroll-mt-16 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">

                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2"> Property Features
                        </p>
                        <div class="w-16 h-1 bg-[#2ba351] mb-5"></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-4">
                            @foreach($extraFeatures as $featureItem)
                                @if(!empty($featureItem))
                                    <div class="flex items-center gap-2.5 text-base text-gray-700">
                                        <i class="fa-solid fa-circle-check text-[#2ba351] text-base leading-none"></i>
                                        <span class="font-medium text-gray-800">{{ $featureItem }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif


                {{-- ── 3. DESCRIPTION SECTION ── --}}
                @if($project->description)
                    <div id="section-description"
                        class="scroll-mt-16 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">

                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2">Property Description
                        </p>
                        <div class="w-16 h-1 bg-[#2ba351] mb-5"></div>
                        <div class="prose max-w-none text-gray-600 text-base leading-relaxed space-y-4">
                            <div>{!! $project->description !!}</div>
                        </div>
                    </div>
                @endif

                @if(!empty($project->virtual_tour))
                    <div id="section-virtual-tour"
                        class="scroll-mt-16 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm mb-6">
                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2">360° Virtual Tour</p>
                        <div class="w-16 h-1 bg-[#2ba351] mb-5"></div>

                        <div id="panorama-viewer" class="w-full rounded-xl overflow-hidden shadow-lg bg-gray-900"
                            style="aspect-ratio: 16/9;"></div>
                    </div>
                @endif

                @if(!empty($floorPlans) && count($floorPlans) > 0)
                    <div id="section-floor-plan" class="bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm mb-6">
                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2">FLOOR PLAN</p>
                        <div class="w-16 h-1 bg-[#2ba351] mb-5"></div>

                        <div class="flex flex-wrap gap-4">
                            @foreach($floorPlans as $index => $path)
                                @if(!empty($path))
                                    <!-- data-lightbox-index কাস্টম এট্রিবিউট যুক্ত করা হয়েছে -->
                                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 cursor-pointer transition-transform hover:scale-[1.02] shadow-sm"
                                        data-lightbox-index="{{ $index }}">
                                        <img src="{{ asset('storage/' . $path) }}"
                                            class="w-full h-full object-cover pointer-events-none" alt="Floor Plan">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($locationViews) && count($locationViews) > 0)
                    <div id="section-location-view"
                        class="bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm mb-6">
                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2">LOCATION VIEW</p>
                        <div class="w-16 h-1 bg-[#2ba351] mb-5"></div>

                        <div class="flex flex-wrap gap-4">
                            @foreach($locationViews as $index => $path)
                                @if(!empty($path))
                                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 cursor-pointer transition-transform hover:scale-[1.02] shadow-sm"
                                        data-lightbox-index="{{ $index + count($floorPlans) }}">
                                        <img src="{{ asset('storage/' . $path) }}"
                                            class="w-full h-full object-cover pointer-events-none" alt="Location View">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif



                <div id="lightboxModal"
                    class="hidden fixed inset-0 z-[9999] flex flex-col justify-between items-center p-6 backdrop-blur-md bg-black/95 transition-all duration-300">

                    <div class="w-full flex justify-between items-center text-white max-w-7xl">
                        <span id="lightboxIndexText" class="text-xs font-bold uppercase tracking-wider opacity-70">Image 1
                            of 5</span>

                        <div class="flex items-center gap-4 bg-white/10 px-4 py-2 rounded-full border border-white/10">
                            <button type="button" id="zoomInBtn"
                                class="text-white hover:text-[#2ba351] text-lg focus:outline-none transition-all"
                                title="Zoom In">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </button>
                            <button type="button" id="zoomOutBtn"
                                class="text-white hover:text-[#2ba351] text-lg focus:outline-none transition-all"
                                title="Zoom Out">
                                <i class="fa-solid fa-magnifying-glass-minus"></i>
                            </button>
                            <button type="button" id="zoomResetBtn"
                                class="text-white hover:text-[#2ba351] text-lg focus:outline-none transition-all"
                                title="Reset Zoom">
                                <i class="fa-solid fa-compress"></i>
                            </button>
                        </div>

                        <button type="button" id="closeLightboxBtn"
                            class="text-white hover:text-red-500 text-xl focus:outline-none transition-all">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div
                        class="relative w-full flex-grow flex items-center justify-center my-4 max-w-5xl overflow-hidden select-none">
                        <button type="button" id="prevLightboxBtn"
                            class="absolute left-0 md:left-4 text-white/50 hover:text-white text-xl md:text-5xl focus:outline-none transition-all z-[10000]">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>

                        <div
                            class="max-h-[60vh] max-w-[85vw] flex items-center justify-center rounded-xl overflow-hidden bg-black/20 shadow-2xl border border-white/10 relative">
                            <img id="lightboxMainImage" src=""
                                class="max-h-[60vh] max-w-full object-contain transition-transform duration-200 select-none pointer-events-none"
                                style="transform-origin: center; transition: transform 0.15s ease-out;"
                                alt="Active Gallery Image">
                        </div>

                        <button type="button" id="nextLightboxBtn"
                            class="absolute right-0 md:right-4 text-white/50 hover:text-white text-xl md:text-5xl focus:outline-none transition-all z-[10000]">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="w-full max-w-4xl overflow-x-auto pb-2 scrollbar-hide flex gap-2 justify-center"
                        id="lightboxThumbnailsContainer">
                        @foreach($allGalleryImages as $index => $path)
                            @if(!empty($path))
                                <button type="button" id="lightbox-thumb-{{ $index }}"
                                    class="lightbox-thumb-btn w-16 h-12 md:w-20 md:h-14 rounded-lg overflow-hidden border-2 border-transparent flex-shrink-0 focus:outline-none transition-all">
                                    <img src="{{ asset('storage/' . $path) }}"
                                        class="w-full h-full object-cover pointer-events-none" alt="Lightbox Thumb">
                                </button>
                            @endif
                        @endforeach
                    </div>

                </div>
                {{-- ── 6. Video Section ── --}}

                @if(!empty($project->video_path))
                    <div id="section-video"
                        class="scroll-mt-16 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm mb-6">
                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2">PROPERTY VIDEO</p>
                        <div class="w-16 h-1 bg-[#2ba351] mb-5"></div>

                        <div class="relative w-full rounded-xl overflow-hidden shadow-lg bg-black group"
                            style="aspect-ratio: 16/9;">

                            <div id="videoOverlay"
                                class="absolute inset-0 bg-black/40 z-10 flex flex-col justify-center items-center cursor-pointer transition-all duration-300 group-hover:bg-black/50"
                                onclick="playProjectVideo()">
                                <div
                                    class="w-16 h-16 md:w-20 md:h-20 bg-[#2ba351] hover:bg-[#1f7035] text-white rounded-full flex items-center justify-center shadow-lg shadow-[#2ba351]/30 transition-all duration-300 hover:scale-110 active:scale-95">
                                    <i class="fa-solid fa-play text-xl md:text-xl ml-1"></i>
                                </div>
                                <span
                                    class="text-white text-xs font-bold uppercase tracking-wider mt-4 drop-shadow-md tracking-widest">Watch
                                    Video Tour</span>
                            </div>

                            <!-- মেইন HTML5 ভিডিও ট্যাগ (অটোমেটিক মিউটেড প্রিভিউ রাখা হয়নি, ক্লিক করলেই প্লে হবে) -->
                            <video id="projectVideoPlayer" src="{{ asset('storage/' . $project->video_path) }}"
                                class="w-full h-full object-cover" preload="metadata" onplay="hideVideoOverlay()"
                                onpause="showVideoOverlay()" onended="showVideoOverlay()">
                            </video>
                        </div>
                    </div>
                @endif

                {{-- ── 7. MAP VIEW SECTION ── --}}
                @if($isValidEmbed)
                    <div id="section-map-view"
                        class="scroll-mt-16 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2"> Map View
                        </p>
                        <div class="w-16 h-1 bg-[#2ba351] mb-5"></div>
                        <div class="rounded-xl overflow-hidden shadow-sm border border-gray-100" style="height: 350px;">
                            <iframe src="{{ $mapUrl }}" title="Google Maps showing property location" width="100%" height="100%"
                                style="border:0;" allowfullscreen="" loading="lazy">
                            </iframe>
                        </div>
                    </div>
                @endif

            </div>


            {{--Right Cols--}}
            <div class="lg:col-span-4 flex flex-col gap-6 sticky top-28 max-h-[calc(100vh-120px)] overflow-y-auto pb-4 scrollbar-hide">

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <p
                        class="text-center font-bold text-gray-600 text-xs border-b border-gray-100 pb-3 mb-4 tracking-wider uppercase">
                        Property Owner Details
                    </p>

                    @if($project->company)
                        <div class="text-center mb-5">
                            @if($project->company->company_logo)
                                <div
                                    class="w-20 h-20 mx-auto rounded-full overflow-hidden border border-gray-200 p-1 mb-3 bg-white">
                                    <img src="{{ asset('storage/' . $project->company->company_logo) }}"
                                        class="w-full h-full object-contain rounded-full" alt="Owner Logo">
                                </div>
                            @endif
                            <h2 class="font-extrabold text-gray-800 text-base leading-tight mb-1">
                                {{ $project->company->company_name }}
                            </h2>

                            <span class="text-[11px] text-gray-600 block mb-3">Property ID : {{ 250000 + $project->id }}</span>

                            @if($project->company->phone)
                                <div class="mt-2">
                                    <span id="revealedPhoneNum"
                                        class="block text-gray-800 font-extrabold text-base mb-2 tracking-wide hidden">
                                        <i class="bi bi-telephone-fill text-[#1b6e35] me-1"></i>{{ $project->company->phone }}
                                    </span>

                                    <button type="button" id="phoneToggleBtn" onclick="revealPhone()"
                                        class="w-full bg-[#1b6e35] hover:bg-[#1f7035] text-white py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 transition-all shadow-md shadow-[#1b6e35]/10">
                                        <i class="bi bi-phone-vibrate"></i>
                                        Click to show phone number
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- ইমেইল বা মেসেজ লিড ফর্ম -->
                    <div class="border-t border-gray-100 pt-5">
                        <p class="text-xs font-bold text-[#1b6e35] text-center mb-4 uppercase tracking-wider">
                            Send Message to Property Owner
                        </p>

                        <div id="leadAlert" class="hidden p-3 mb-3 text-xs rounded-xl text-center font-medium"></div>
                        <form id="leadForm" action="{{ route('contact.owner') }}" method="POST" class="flex flex-col gap-3">
                            @csrf
                            <input type="hidden" name="company_id" value="{{ $project->company_id }}">
                            <input type="hidden" name="subject" value="Inquiry for property: {{ $project->title }}">

                            <div id="lead-alert-message" class="hidden p-3 rounded-xl text-center font-medium text-sm">
                            </div>

                            <input type="text" name="lead_name" placeholder="Enter Your Name" required
                                aria-label="Enter Your Name"
                                value="{{ auth()->check() ? auth()->user()->name : old('lead_name') }}"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-700">

                            <input type="email" name="lead_email" placeholder="Enter Your E-mail"
                                aria-label="Enter Your E-mail"
                                value="{{ auth()->check() ? auth()->user()->email : old('lead_email') }}"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-700">

                            <div class="flex gap-2">
                                <select name="country_code" aria-label="Country Code"
                                    class="bg-gray-50 border border-gray-200 px-2 py-3 rounded-xl text-xs outline-none w-20 text-gray-600">
                                    <option value="+880">🇧🇩 +880</option>
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="+44">🇬🇧 +44</option>
                                </select>
                                <input type="tel" name="lead_phone" placeholder="Enter Your Phone" required
                                    aria-label="Enter Your Phone"
                                    value="{{ auth()->check() ? auth()->user()->phone : old('lead_phone') }}"
                                    class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-700">
                            </div>

                            <div class="flex gap-2">
                                <input type="text" name="lead_job_title" placeholder="Job Title"
                                    aria-label="Job Title"
                                    class="w-1/2 bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-700">
                                <input type="text" name="lead_company" placeholder="Company"
                                    aria-label="Company"
                                    class="w-1/2 bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-700">
                            </div>

                            <input type="text" name="lead_budget" placeholder="Estimated Budget"
                                aria-label="Estimated Budget"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-700">

                            <select name="lead_investment_time" aria-label="When Planning to Invest"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-700">
                                <option value="" disabled selected>When Planning to Invest?</option>
                                <option value="Within 15 Days">Within 15 Days</option>
                                <option value="Within 1 Month">Within 1 Month</option>
                                <option value="Within 1-3 Months">Within 1-3 Months</option>
                                <option value="After 3 Months">After 3 Months</option>
                            </select>

                            <textarea name="lead_message" rows="3" placeholder="Please type your message" required
                                aria-label="Please type your message"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-700 resize-none">{{ old('lead_message') }}</textarea>

                            <button type="submit" id="leadSubmitBtn"
                                class="w-full bg-[#1b6e35] hover:bg-[#1f7035] text-white py-3 rounded-xl font-bold text-base transition-all active:scale-[0.98]">
                                Send Message
                            </button>
                        </form>
                        @if($project->company)
                            <div class="flex justify-between items-center text-[11px] mt-4 px-1">
                                <span class="text-gray-600">T&C Apply</span>

                                <a href="{{ route('web.project', ['company' => $project->company->slug]) }}"
                                    class="font-extrabold text-[#1b6e35] hover:underline flex items-center gap-1">
                                    More properties... <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"
                                        aria-hidden="true"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>


                <!-- খ. ব্যানার অ্যাড / সাইডবার রিলেটেড লিংক ইমেজ -->
                <!-- খ. Similar Projects (একই ক্যাটাগরি/লোকেশন) -->
                @if($similarProjects->isNotEmpty())
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                        <h6 class="text-xs font-bold text-gray-500 border-b border-gray-100 pb-3 mb-4 uppercase tracking-wider">
                            Similar Properties
                        </h6>
                        <div class="flex flex-col gap-4">
                            @foreach($similarProjects as $simProject)
                                @php
                                    $simImages = $simProject->img_paths;
                                    $simThumb = !empty($simImages) ? $simImages[0] : 'assets/images/placeholder.jpg';
                                @endphp
                                <a href="{{ route('project.details', $simProject->slug) }}" class="flex items-center gap-3 group">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $simThumb) }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                            alt="Thumb">
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-base font-bold text-gray-800 line-clamp-1 group-hover:text-[#2ba351] transition-colors">
                                            {{ $simProject->title }}
                                        </span>
                                        <span class="text-xs font-extrabold text-[#2ba351] mt-0.5">{{ $simProject->short }}</span>
                                        <span class="text-[10px] text-gray-400 mt-0.5 truncate">
                                            <i class="fa-solid fa-location-dot me-1"></i>{{ $simProject->location }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- গ. Recently Viewed (সম্প্রতি ভিজিট করা প্রজেক্ট) -->
                @if($recentProjects->isNotEmpty())
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                        <h6 class="text-xs font-bold text-gray-500 border-b border-gray-100 pb-3 mb-4 uppercase tracking-wider">
                            Recently Viewed
                        </h6>
                        <div class="flex flex-col gap-4">
                            @foreach($recentProjects as $recProject)
                                @php
                                    $recImages = $recProject->img_paths;
                                    $recThumb = !empty($recImages) ? $recImages[0] : 'assets/images/placeholder.jpg';
                                @endphp
                                <a href="{{ route('project.details', $recProject->slug) }}" class="flex items-center gap-3 group">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $recThumb) }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                            alt="Thumb">
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-base font-bold text-gray-800 line-clamp-1 group-hover:text-[#2ba351] transition-colors">
                                            {{ $recProject->title }}
                                        </span>
                                        <span class="text-xs font-extrabold text-[#2ba351] mt-0.5">{{ $recProject->short }}</span>
                                        <span class="text-[10px] text-gray-400 mt-0.5 truncate">
                                            <i class="fa-solid fa-location-dot me-1"></i>{{ $recProject->location }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
    @include('partials.recaptcha')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css" />
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('leadForm');
            const alertMessage = document.getElementById('lead-alert-message');

            if (!form) {
                console.error('Lead form not found');
                return;
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                const submitBtn = form.querySelector('#leadSubmitBtn');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Please wait...';
                alertMessage.classList.add('hidden');

                try {
                    const token = await window.getRecaptchaToken('contact_owner');

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
                        alertMessage.textContent = result.message || '✓ Your message has been sent successfully!';

                        form.reset(); // ফর্ম খালি করে দিন
                    } else {
                        let errorMsg = result.message || 'Something went wrong. Please try again.';

                        if (result.errors) {
                            errorMsg = Object.values(result.errors).map(err => err[0]).join(' | ');
                        }

                        alertMessage.classList.remove('hidden', 'bg-green-100', 'text-green-700');
                        alertMessage.classList.add('bg-red-100', 'text-red-700');
                        alertMessage.innerHTML = errorMsg;
                    }
                } catch (error) {
                    console.error('Lead form error:', error);
                    alertMessage.classList.remove('hidden', 'bg-green-100', 'text-green-700');
                    alertMessage.classList.add('bg-red-100', 'text-red-700');
                    alertMessage.textContent = 'An error occurred. Please try again.';
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        });
    </script>
    <script>
        // ── Sticky Tab ScrollSpy & Smooth Scrolling (Button Style Update) ──
        document.addEventListener('DOMContentLoaded', function () {
            pannellum.viewer('panorama-viewer', {
                "type": "equirectangular",
                "panorama": "{{ asset('storage/' . $project->virtual_tour) }}",
                "autoLoad": true,
                "autoRotate": -2,
                "showControls": true,
                "compass": false
            });
            const sections = document.querySelectorAll('.scroll-mt-16');
            const tabLinks = document.querySelectorAll('.tab-link');

            tabLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetSection = document.querySelector(targetId);

                    if (targetSection) {
                        window.scrollTo({
                            top: targetSection.offsetTop - 75,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            window.addEventListener('scroll', function () {
                let currentSectionId = "";
                const scrollPosition = window.scrollY || window.pageYOffset;

                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (scrollPosition >= (sectionTop - 140)) {
                        currentSectionId = section.getAttribute('id');
                    }
                });

                tabLinks.forEach(link => {
                    link.classList.remove('bg-[#2ba351]', 'text-white', 'border-[#2ba351]', 'shadow-sm', 'shadow-[#2ba351]/10');
                    link.classList.add('bg-gray-50', 'text-gray-600', 'border-gray-100');

                    if (link.getAttribute('href') === `#${currentSectionId}`) {
                        link.classList.add('bg-[#2ba351]', 'text-white', 'border-[#2ba351]', 'shadow-sm', 'shadow-[#2ba351]/10');
                        link.classList.remove('bg-gray-50', 'text-gray-600', 'border-gray-100');
                    }
                });
            });
        });

        function switchFeaturedImage(src, btn) {
            const featuredImage = document.getElementById('mainFeaturedImage');
            if (featuredImage) {
                featuredImage.style.opacity = '0.3';
                setTimeout(() => {
                    featuredImage.src = src;
                    featuredImage.style.opacity = '1';
                }, 150);
            }

            document.querySelectorAll('.thumbnail-btn').forEach(b => {
                b.classList.remove('border-[#2ba351]');
                b.classList.add('border-transparent');
            });

            btn.classList.add('border-[#2ba351]');
            btn.classList.remove('border-transparent');
        }

        function revealPhone() {
            const phoneSpan = document.getElementById('revealedPhoneNum');
            const toggleBtn = document.getElementById('phoneToggleBtn');

            if (phoneSpan && toggleBtn) {
                phoneSpan.classList.remove('hidden');
                toggleBtn.classList.add('hidden');
            }
        }

        // Helper function to format numbers cleanly (e.g., 1,64,000)
        function formatCurrency(number) {
            return Math.round(number).toLocaleString('en-IN');
        }

        window.playProjectVideo = function () {
            const player = document.getElementById('projectVideoPlayer');
            const overlay = document.getElementById('videoOverlay');

            if (player && overlay) {
                player.play();
                player.setAttribute('controls', 'true');
                overlay.classList.add('hidden');
            }
        };

        window.hideVideoOverlay = function () {
            const overlay = document.getElementById('videoOverlay');
            if (overlay) overlay.classList.add('hidden');
        };

        window.showVideoOverlay = function () {
            const overlay = document.getElementById('videoOverlay');
            const player = document.getElementById('projectVideoPlayer');

            if (overlay && player) {
                overlay.classList.remove('hidden');
                player.removeAttribute('controls'); // পজ বা এন্ড হলে নেটিভ কন্ট্রোল হাইড হবে যেন প্লে বাটনটি সুন্দর দেখায়
            }
        };

    </script>
@endsection
