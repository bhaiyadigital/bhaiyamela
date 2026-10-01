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
                    হোম
                </a>

                <span class="text-gray-300">›</span>

                <a href="{{ route('web.project') ?? '#' }}" class="text-gray-600 hover:text-black">
                    প্রপার্টিজ
                </a>

                @if($project->category)
                <span class="text-gray-300">›</span>
                
                <a href="{{ route('web.project.category', $project->category->slug) }}" class="text-gray-600 hover:text-black">
                    {{ $project->category->title }}
                </a>
                @endif

                <span class="text-gray-300">›</span>

                <span class="text-black font-semibold truncate">
                    {{ $project->slug    }}
                </span>

            </div>
        </div>
    </section>
    <div class="container py-6 mx-auto px-4">


        <div class="mb-6">
            <h1 class="text-xl md:text-3xl font-extrabold text-gray-900 leading-tight flex flex-wrap items-center gap-3">
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
                            class="w-full h-full object-contain transition-all duration-300" alt="{{ $project->title }}">
                    </div>
                    @if(!empty($images) && count($images) > 1)
                        <div class="relative mt-3">
                            <div class="flex gap-2 overflow-x-auto py-2 scrollbar-hide" id="thumbnailSlider">
                                @foreach($images as $index => $path)
                                    <button type="button" onclick="switchFeaturedImage('{{ asset('storage/' . $path) }}', this)"
                                        class="thumbnail-btn w-20 h-14 rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-[#2c4294]' : 'border-transparent' }} flex-shrink-0 focus:outline-none transition-all">
                                        <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover" alt="Thumb">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>


                {{--
                @if(!empty($featuresList) || (!empty($extraFeatures) && is_array($extraFeatures)) || $project->description || !empty($floorPlans) || !empty($locationViews) || !empty($project->video_path) || $isValidEmbed)
                    <div class="sticky top-0 bg-white z-30 py-4 pl-3 flex flex-wrap gap-3 border-b border-gray-100 shadow-sm">

                        @if(!empty($featuresList))
                            <a href="#section-overview"
                                class="tab-link px-4 py-2.5 bg-[#2c4294] text-white rounded-xl text-xs md:text-base font-extrabold border border-[#2c4294] shadow-sm shadow-[#2c4294]/10 hover:opacity-95 transition-all whitespace-nowrap">
                                ওভারভিউ
                            </a>
                        @endif

                        @if(!empty($extraFeatures) && is_array($extraFeatures))
                            <a href="#section-features"
                                class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                                বৈশিষ্ট্য
                            </a>
                        @endif

                        @if($project->description)
                            <a href="#section-description"
                                class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                                বিবরণ
                            </a>
                        @endif

                        @if(!empty($floorPlans))
                            <a href="#section-floor-plan"
                                class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                                ফ্লোর প্ল্যান
                            </a>
                        @endif

                        @if(!empty($locationViews))
                            <a href="#section-location-view"
                                class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                                লোকেশন ভিউ
                            </a>
                        @endif

                        @if(!empty($project->video_path))
                            <a href="#section-video"
                                class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                                ভিডিও
                            </a>
                        @endif

                        @if($isValidEmbed)
                            <a href="#section-map-view"
                                class="tab-link px-4 py-2.5 bg-gray-50 text-gray-600 rounded-xl text-xs md:text-base font-extrabold border border-gray-100 hover:bg-gray-100 hover:text-gray-900 transition-all whitespace-nowrap">
                                ম্যাপ ভিউ
                            </a>
                        @endif

                    </div>
                @endif
                --}}


                @if(!empty($featuresList))
                    <div id="section-overview"
                        class="scroll-mt-16 bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)]">
                        <div class="flex flex-wrap justify-between items-center gap-4 mb-5 border-b border-gray-100 pb-5">
                            <div>
                                <span class="text-xl font-black text-[#2c4294]">{{ $project->short }}</span>
                                <span class="text-xs text-gray-600 block mt-1">/
                                    {{ $project->category->title ?? 'Apartment/Flats for Sale' }}</span>
                            </div>
                            <div class="text-base text-gray-600 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-gray-600"></i>
                                <span
                                    class="font-medium text-gray-700">{{ $project->destination->title ?? $project->location ?? 'Dhaka' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 text-center pt-2 mb-5">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-bed text-xl text-gray-600 mb-2"></i>
                                <span class="text-base font-bold text-gray-800">{{ $bedrooms }} বেড</span>
                            </div>
                            <div class="flex flex-col items-center border-x border-gray-100">
                                <i class="fa-solid fa-bath text-xl text-gray-600 mb-2"></i>
                                <span class="text-base font-bold text-gray-800">{{ $baths }} বাথ</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-ruler-combined text-xl text-gray-600 mb-2"></i>
                                <span class="text-base font-bold text-gray-800">{{ $size }}</span>
                            </div>
                        </div>

                        @if(!empty($featuresList))
                            <div class="border-t border-gray-100 pt-5">

                                <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2"> প্রপার্টি ওভারভিউ
                                </p>
                                <div class="w-16 h-1 bg-[#2c4294] mb-5"></div>

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
                                                <span class="text-gray-600 font-medium flex items-center gap-1.5">
                                                    <i class="fa-solid fa-chevron-right text-[10px] text-gray-600"></i>
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
                @endif


                {{-- ── 2. FEATURES SECTION ── --}}
                @if(!empty($extraFeatures) && is_array($extraFeatures))
                    <div id="section-features"
                        class="scroll-mt-16 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm">

                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2"> প্রপার্টির বৈশিষ্ট্য
                        </p>
                        <div class="w-16 h-1 bg-[#2c4294] mb-5"></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-y-3 gap-x-4">
                            @foreach($extraFeatures as $featureItem)
                                @if(!empty($featureItem))
                                    <div class="flex items-center gap-2.5 text-base text-gray-700">
                                        <i class="fa-solid fa-circle-check text-[#2c4294] text-base leading-none"></i>
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

                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2">প্রপার্টির বিবরণ
                        </p>
                        <div class="w-16 h-1 bg-[#2c4294] mb-5"></div>
                        <div class="prose max-w-none text-gray-600 text-base leading-relaxed space-y-4">
                            <div>{!! $project->description !!}</div>
                        </div>
                    </div>
                @endif

                {{-- @if(!empty($project->virtual_tour))
                <div id="section-virtual-tour"
                    class="scroll-mt-16 bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm mb-6">
                    <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2">৩৬০° ভার্চুয়াল ট্যুর</p>
                    <div class="w-16 h-1 bg-[#2c4294] mb-5"></div>

                    <div id="panorama-viewer" class="w-full rounded-xl overflow-hidden shadow-lg bg-gray-900"
                        style="aspect-ratio: 16/9;"></div>
                </div>
                @endif --}}

                @if(!empty($floorPlans) && count($floorPlans) > 0)
                    <div id="section-floor-plan" class="bg-white p-6 md:p-8 rounded-2xl border border-gray-100 shadow-sm mb-6">
                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2">ফ্লোর প্ল্যান</p>
                        <div class="w-16 h-1 bg-[#2c4294] mb-5"></div>

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
                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2">লোকেশন ভিউ</p>
                        <div class="w-16 h-1 bg-[#2c4294] mb-5"></div>

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
                                class="text-white hover:text-[#2c4294] text-lg focus:outline-none transition-all"
                                title="Zoom In">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </button>
                            <button type="button" id="zoomOutBtn"
                                class="text-white hover:text-[#2c4294] text-lg focus:outline-none transition-all"
                                title="Zoom Out">
                                <i class="fa-solid fa-magnifying-glass-minus"></i>
                            </button>
                            <button type="button" id="zoomResetBtn"
                                class="text-white hover:text-[#2c4294] text-lg focus:outline-none transition-all"
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
                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2">প্রপার্টি ভিডিও</p>
                        <div class="w-16 h-1 bg-[#2c4294] mb-5"></div>

                        <div class="relative w-full rounded-xl overflow-hidden shadow-lg bg-black group"
                            style="aspect-ratio: 16/9;">

                            <div id="videoOverlay"
                                class="absolute inset-0 bg-black/40 z-10 flex flex-col justify-center items-center cursor-pointer transition-all duration-300 group-hover:bg-black/50"
                                onclick="playProjectVideo()">
                                <div
                                    class="w-16 h-16 md:w-20 md:h-20 bg-[#2c4294] hover:bg-[#1a285a] text-white rounded-full flex items-center justify-center shadow-lg shadow-[#2c4294]/30 transition-all duration-300 hover:scale-110 active:scale-95">
                                    <i class="fa-solid fa-play text-xl md:text-xl ml-1"></i>
                                </div>
                                <span
                                    class="text-white text-xs font-bold uppercase tracking-wider mt-4 drop-shadow-md tracking-widest">ভিডিও
                                    ট্যুর দেখুন</span>
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
                        <p class="font-bold text-gray-800 text-lg uppercase tracking-wider mb-2"> ম্যাপ ভিউ
                        </p>
                        <div class="w-16 h-1 bg-[#2c4294] mb-5"></div>
                        <div class="rounded-xl overflow-hidden shadow-sm border border-gray-100" style="height: 350px;">
                            <iframe src="{{ $mapUrl }}" title="Google Maps showing property location" width="100%" height="100%"
                                style="border:0;" allowfullscreen="" loading="lazy">
                            </iframe>
                        </div>
                    </div>
                @endif

            </div>


            {{--Right Cols--}}
            <div class="lg:col-span-4 flex flex-col gap-6 sticky top-28">

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h2 class="text-center font-bold text-gray-600 text-sm md:text-base border-b border-gray-100 pb-3 mb-4 tracking-wider uppercase">
                        {{ $project->category?->short ?: ($project->title . ' এর জন্য যোগাযোগ করুন') }}
                    </h2>



                        <div id="leadAlert" class="hidden p-3 mb-3 text-sm rounded-xl text-center font-medium"></div>
                        <form id="leadForm" action="{{ route('contact.owner') }}" method="POST" class="flex flex-col gap-3">
                            @csrf
                            <input type="hidden" name="company_id" value="{{ $project->company_id }}">
                            <input type="hidden" name="subject" value="Inquiry for property: {{ $project->title }}">



                            <input type="text" name="lead_name" placeholder="আপনার নাম লিখুন" required
                                aria-label="Enter Your Name"
                                value="{{ auth()->check() ? auth()->user()->name : old('lead_name') }}"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">

                            <input type="email" name="lead_email" placeholder="আপনার ইমেইল লিখুন"
                                aria-label="Enter Your E-mail"
                                value="{{ auth()->check() ? auth()->user()->email : old('lead_email') }}"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">

                            <input type="tel" name="lead_phone" placeholder="আপনার ফোন নম্বর লিখুন" required
                                aria-label="Enter Your Phone"
                                value="{{ auth()->check() ? auth()->user()->phone : old('lead_phone') }}"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">

                            <div class="flex gap-2">
                                <input type="text" name="lead_job_title" placeholder="পদের নাম" aria-label="Job Title"
                                    class="w-1/2 bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">
                                <input type="text" name="lead_company" placeholder="কোম্পানি" aria-label="Company"
                                    class="w-1/2 bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">
                            </div>

                            <input type="text" name="lead_budget" placeholder="আনুমানিক বাজেট" aria-label="Estimated Budget"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">

                            <select name="lead_investment_time" aria-label="When Planning to Invest"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700">
                                <option value="" disabled selected>কবে বিনিয়োগের পরিকল্পনা করছেন?</option>
                                <option value="Within 15 Days">১৫ দিনের মধ্যে</option>
                                <option value="Within 1 Month">১ মাসের মধ্যে</option>
                                <option value="Within 1-3 Months">১-৩ মাসের মধ্যে</option>
                                <option value="After 3 Months">৩ মাসের পর</option>
                            </select>

                            <textarea name="lead_message" rows="3" placeholder="আপনার মেসেজ লিখুন" required
                                aria-label="Please type your message"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-3 rounded-xl text-sm outline-none focus:ring-2 focus:ring-[#2c4294]/20 transition-all text-gray-700 resize-none">{{ old('lead_message') }}</textarea>

                            <button type="submit" id="leadSubmitBtn"
                                class="cursor-pointer w-full bg-[#2c4294] hover:bg-[#1a285a] text-white py-3 rounded-xl font-bold text-lg transition-all active:scale-[0.98]">
                                মেসেজ পাঠান
                            </button>
                        </form>
                        @if($project->company)
                            <div class="flex justify-between items-center text-xs mt-4 px-1">
                                <span class="text-gray-600">শর্তাবলী প্রযোজ্য</span>

                                <a href="{{ route('web.project', ['company' => $project->company->slug]) }}"
                                    class="font-extrabold text-[#2c4294] hover:underline flex items-center gap-1">
                                    আরও প্রপার্টি... <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"
                                        aria-hidden="true"></i>
                                </a>
                            </div>
                        @endif
                </div>

                <!-- খ. Similar Projects (একই ক্যাটাগরি/লোকেশন) -->
                @if($similarProjects->isNotEmpty())
                    <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                        <h2 class="text-sm font-bold text-gray-600 border-b border-gray-100 pb-3 mb-4 uppercase tracking-wider">
                            একই ধরনের প্রপার্টি
                        </h2>
                        <div class="flex flex-col gap-4">
                            @foreach($similarProjects as $simProject)
                                @php
                                    $simThumb = $simProject->main_image ?? 'assets/images/placeholder.jpg';
                                @endphp
                                <a href="{{ route('project.details', $simProject->slug) }}" class="flex items-center gap-3 group">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $simThumb) }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                            alt="Thumb">
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-lg font-bold text-gray-800 line-clamp-1 group-hover:text-[#2c4294] transition-colors">
                                            {{ $simProject->title }}
                                        </span>
                                        <span class="text-sm font-extrabold text-[#2c4294] mt-0.5">{{ $simProject->short }}</span>
                                        <span class="text-xs text-gray-600 mt-0.5 truncate">
                                            <i
                                                class="fa-solid fa-location-dot me-1"></i>{{ $simProject->destination->title ?? $simProject->location ?? 'Dhaka' }}
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
                        <h2 class="text-sm font-bold text-gray-600 border-b border-gray-100 pb-3 mb-4 uppercase tracking-wider">
                            সম্প্রতি দেখেছেন
                        </h2>
                        <div class="flex flex-col gap-4">
                            @foreach($recentProjects as $recProject)
                                @php
                                    $recThumb = $recProject->main_image ?? 'assets/images/placeholder.jpg';
                                @endphp
                                <a href="{{ route('project.details', $recProject->slug) }}" class="flex items-center gap-3 group">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                                        <img src="{{ asset('storage/' . $recThumb) }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                            alt="Thumb">
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-lg font-bold text-gray-800 line-clamp-1 group-hover:text-[#2c4294] transition-colors">
                                            {{ $recProject->title }}
                                        </span>
                                        <span class="text-sm font-extrabold text-[#2c4294] mt-0.5">{{ $recProject->short }}</span>
                                        <span class="text-xs text-gray-600 mt-0.5 truncate">
                                            <i
                                                class="fa-solid fa-location-dot me-1"></i>{{ $recProject->destination->title ?? $recProject->location ?? 'Dhaka' }}
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
        function showCustomToast(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const bgClass = type === 'success' ? 'bg-[#2c4294]' : 'bg-red-500';
            const icon = type === 'success' ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-exclamation"></i>';

            const toastHtml = `
                                        <div id="${toastId}" class="fixed top-28 right-5 z-[9999] flex items-center gap-3 ${bgClass} text-white px-5 py-3 rounded-xl shadow-2xl transition-all duration-300 transform -translate-y-10 opacity-0">
                                            <span class="text-lg">${icon}</span>
                                            <span class="font-medium text-sm whitespace-pre-line">${message}</span>
                                        </div>
                                    `;

            document.body.insertAdjacentHTML('beforeend', toastHtml);
            const toastEl = document.getElementById(toastId);

            setTimeout(() => {
                toastEl.classList.remove('-translate-y-10', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toastEl.classList.add('-translate-y-10', 'opacity-0');
                setTimeout(() => toastEl.remove(), 300);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('leadForm');

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
                        if (typeof fbq !== 'undefined') fbq('track', 'Lead');
                        showMessageModal(result.message || "আপনার মেসেজটি সফলভাবে পাঠানো হয়েছে।", "success");
                        form.reset();
                    } else {
                        let errorMsg = result.message || "কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।";
                        if (result.errors) {
                            errorMsg = Object.values(result.errors).map(err => err[0]).join("\n");
                        }
                        showMessageModal(errorMsg, "error");
                    }
                } catch (error) {
                    console.error("Lead form error:", error);
                    showMessageModal("কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।", "error");
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
                    link.classList.remove('bg-[#2c4294]', 'text-white', 'border-[#2c4294]', 'shadow-sm', 'shadow-[#2c4294]/10');
                    link.classList.add('bg-gray-50', 'text-gray-600', 'border-gray-100');

                    if (link.getAttribute('href') === `#${currentSectionId}`) {
                        link.classList.add('bg-[#2c4294]', 'text-white', 'border-[#2c4294]', 'shadow-sm', 'shadow-[#2c4294]/10');
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
                b.classList.remove('border-[#2c4294]');
                b.classList.add('border-transparent');
            });

            btn.classList.add('border-[#2c4294]');
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
                player.removeAttribute('controls');
            }
        };

        function showMessageModal(message, type = 'success') {
            const modal = document.getElementById('messageModal');
            const content = document.getElementById('messageModalContent');
            const iconBg = document.getElementById('messageModalIconBg');
            const icon = document.getElementById('messageModalIcon');
            const title = document.getElementById('messageModalTitle');
            
            document.getElementById('messageModalText').textContent = message;
            
            if(type === 'success') {
                iconBg.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-green-100';
                icon.className = 'fa-solid fa-check text-4xl text-green-500';
                title.textContent = 'সফল হয়েছে!';
            } else {
                iconBg.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-red-100';
                icon.className = 'fa-solid fa-xmark text-4xl text-red-500';
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

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-all" onclick="closeMessageModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center transform scale-95 opacity-0 transition-all duration-300 mx-4" id="messageModalContent">
            <div id="messageModalIconBg" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i id="messageModalIcon" class="fa-solid text-4xl"></i>
            </div>
            <h2 id="messageModalTitle" class="text-2xl font-bold text-gray-800 mb-2"></h2>
            <p id="messageModalText" class="text-gray-600 mb-6 font-medium text-base"></p>
            <button type="button" onclick="closeMessageModal()" class="cursor-pointer w-full bg-[#2c4294] text-white rounded-xl py-3.5 font-bold text-base hover:bg-[#1a285a] shadow-md hover:shadow-lg transition-all active:scale-95">
                ঠিক আছে
            </button>
        </div>
    </div>
@endsection
