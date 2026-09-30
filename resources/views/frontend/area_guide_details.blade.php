@extends('layouts.front')
@section('meta')
@include('frontend.partials.meta', ['pageKey' => 'partner'])
@endsection
@section('content')
<section class="" style="border: 1px solid gainsboro; margin-bottom: 20px;">
    <div class="container mx-auto px-6 md:px-10">
        <!-- Image Overlap using Negative Margin (Added items-center and min-w-0) -->
        <div class="flex items-center gap-2 text-base px-4 py-2 border-gray-100 min-w-0">
            <!-- Added flex-shrink-0 -->
            <a href="{{ url('/') }}" class="text-gray-600 hover:text-black flex-shrink-0">
                Home
            </a>

            <!-- Added flex-shrink-0 -->
            <span class="text-gray-300 flex-shrink-0">›</span>

            <!-- Added flex-shrink-0 -->
            <a href="{{ route('area-guides.index') ?? '#' }}" class="text-gray-600 hover:text-black flex-shrink-0">
                Area Guide
            </a>

            <!-- Added flex-shrink-0 -->
            <span class="text-gray-300 flex-shrink-0">›</span>

            <!-- IMPROVED: Added truncate, min-w-0, and title attribute -->
            <span class="text-black font-semibold truncate min-w-0" title="{{ $guide->title }}">
                {{ $guide->slug }}
            </span>

        </div>
    </div>
</section>
<div class="container mx-auto py-8 px-4 md:px-8 max-w-7xl">
    <!-- 2. Main Two-Column Layout (8-Grid Content vs 4-Grid Sidebar) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- ── Left: Blog Article & Gallery (8 Columns) ── --}}
        <div class="lg:col-span-8 flex flex-col gap-6">

            <!-- Main Content Area -->
            <div class="bg-white p-6 md:p-8 rounded-xl border border-gray-100 shadow-sm flex flex-col gap-6">
                <!-- Large Featured Banner -->
                <div class="relative w-full rounded-2xl overflow-hidden shadow-sm bg-gray-50 mb-2" style="aspect-ratio: 16/9;">
                    <img src="{{ asset('storage/' . $guide->img_path) }}" class="w-full h-full object-cover" alt="Area Banner">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 md:left-8">
                        <span class="text-yellow-400 text-xs font-bold uppercase tracking-widest block mb-1">Area Insights</span>
                        <h1 class="text-white font-black text-xl md:text-xl lg:text-4xl uppercase tracking-wider">{{ $guide->title }} Area Guide</h1>
                    </div>
                </div>

                <!-- Tagline -->
                @if($guide->short)
                <!-- IMPROVED: border-[#2ba351] changed to border-[#2c4294] -->
                <p class="text-gray-500 text-base md:text-base font-semibold italic border-l-4 border-[#2c4294] pl-4 leading-relaxed">
                    "{{ $guide->short }}"
                </p>
                @endif
                @php
                $featuresList = $guide->features ?? [];
                @endphp

                @if(!empty($featuresList))
                <div class="bg-white p-6 md:p-8 rounded-xl border border-gray-100 shadow-sm">
                    <!-- IMPROVED: Swapped h5 for h2 to satisfy heading sequence, added aria-hidden="true" to icon -->
                    <h2 class="font-bold text-gray-800 mb-6 border-b pb-2 text-base uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-gray-400" aria-hidden="true"></i>
                        Area Key Facts &amp; Insights
                    </h2>

                    <!-- Bento Grid Layout for Highlighting Sights/Facts -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 select-none">
                        @foreach($featuresList as $key => $val)
                        @php
                        // Decode features safely (supports both old associative and new sequential array format)
                        if(is_numeric($key) && !is_array($val)) {
                        $fKey = $val; $fVal = '';
                        } else {
                        $fKey = is_array($val) ? ($val['key'] ?? '') : $key;
                        $fVal = is_array($val) ? ($val['value'] ?? '') : $val;
                        }

                        if (empty($fKey)) continue;

                        // Dynamic Icon and Theme colors mapping based on key names
                        $keyLower = strtolower($fKey);
                        $iconClass = 'fa-solid fa-circle-check text-gray-500';
                        $bgClass = 'bg-gray-50';

                        if (str_contains($keyLower, 'price')) {
                        $iconClass = 'fa-solid fa-tags text-emerald-500';
                        $bgClass = 'bg-emerald-50';
                        } elseif (str_contains($keyLower, 'school') || str_contains($keyLower, 'education') || str_contains($keyLower, 'institute')) {
                        $iconClass = 'fa-solid fa-graduation-cap text-blue-500';
                        $bgClass = 'bg-blue-50';
                        } elseif (str_contains($keyLower, 'hospital') || str_contains($keyLower, 'medical')) {
                        $iconClass = 'fa-solid fa-hospital text-red-500';
                        $bgClass = 'bg-red-50';
                        } elseif (str_contains($keyLower, 'metro') || str_contains($keyLower, 'transport')) {
                        $iconClass = 'fa-solid fa-train text-indigo-500';
                        $bgClass = 'bg-indigo-50';
                        } elseif (str_contains($keyLower, 'status') || str_contains($keyLower, 'development')) {
                        $iconClass = 'fa-solid fa-chart-line text-amber-500';
                        $bgClass = 'bg-amber-50';
                        }
                        @endphp

                        <!-- Individual Highlight Bento Card -->
                        <div class="p-4 rounded-2xl border border-gray-100 flex flex-col justify-between shadow-[0_5px_15px_rgba(0,0,0,0.01)] hover:shadow-md transition-all duration-300">
                            <!-- Floating Custom Color Icon -->
                            <div class="w-10 h-10 {{ $bgClass }} rounded-xl flex items-center justify-center text-lg mb-3">
                                <i class="{{ $iconClass }}"></i>
                            </div>

                            <!-- Label and Value -->
                            <div class="flex flex-col min-w-0">
                                <!-- IMPROVED: text-gray-400 changed to text-gray-600 for optimal contrast pass -->
                                <span class="text-gray-600 text-[10px] md:text-xs font-bold uppercase tracking-wider truncate" title="{{ $fKey }}">{{ $fKey }}</span>
                                <span class="text-base md:text-base font-black text-gray-800 mt-1 truncate" title="{{ $fVal }}">{{ $fVal }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <!-- 3 Descriptions Unified Layout -->
                <div class="flex flex-col gap-4">
                    <!-- Description 1 -->
                    @if($guide->description)
                    <div class="prose max-w-none text-gray-700 text-base md:text-base leading-relaxed">
                        {!! $guide->description !!}
                    </div>
                    @endif

                    <!-- Description 2 -->
                    @if(!empty($guide->description_1) && !is_array($guide->description_1))
                    <div class="prose max-w-none text-gray-700 text-base md:text-base leading-relaxed prose-p:mt-0 prose-headings:mt-0">
                        {!! $guide->description_1 !!}
                    </div>
                    @endif

                    <!-- Description 3 -->
                    @if(!empty($guide->description_2) && !is_array($guide->description_2))
                    <div class="prose max-w-none text-gray-700 text-base md:text-base leading-relaxed prose-p:mt-0 prose-headings:mt-0">
                        {!! $guide->description_2 !!}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Area Gallery Images Card -->
            @if(!empty($guide->img_paths) && is_array($guide->img_paths))
            <div class="bg-white p-6 md:p-8 rounded-xl border border-gray-100 shadow-sm">
                <!-- IMPROVED: h5 changed to h2, added aria-hidden="true" to icon -->
                <h2 class="font-bold text-gray-800 mb-4 border-b pb-2 text-base uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-images text-gray-400" aria-hidden="true"></i>
                    {{ $guide->title }} Gallery / Sights
                </h2>
                <div class="flex flex-wrap gap-4">
                    @foreach($guide->img_paths as $index => $path)
                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 cursor-pointer transition-transform hover:scale-[1.02] shadow-sm"
                        onclick="openLightbox({{ $index }})">
                        <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover" alt="Sight Thumb">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- ── Right: Sidebar Related Properties (4 Columns) ── --}}
        <div class="lg:col-span-4 flex flex-col gap-6 sticky top-24">

            <!-- Properties in this specific area card -->
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                <!-- IMPROVED: h6 changed to p, text-gray-500 changed to text-gray-600 for contrast compliance -->
                <p class="text-xs font-bold text-gray-600 border-b border-gray-100 pb-3 mb-4 uppercase tracking-wider">
                    Properties in {{ $guide->title }}
                </p>

                @if($relatedProjects->isEmpty())
                <div class="text-center py-8 text-gray-600">
                    <i class="fa-solid fa-building-circle-exclamation text-xl mb-2 block" aria-hidden="true"></i>
                    <p class="text-xs font-semibold">No active projects listed in this area yet.</p>
                </div>
                @else
                <div class="flex flex-col gap-4">
                    @foreach($relatedProjects as $relProject)
                    @php
                    $relImages = $relProject->img_paths;
                    $relThumb = !empty($relImages) ? $relImages[0] : 'assets/images/placeholder.jpg';
                    @endphp
                    <a href="{{ route('project.details', $relProject->slug) }}" class="flex items-center gap-3 group">
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                            <img src="{{ asset('storage/' . $relThumb) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" alt="Thumb">
                        </div>
                        <div class="flex flex-col min-w-0">
                            <!-- IMPROVED: group-hover:text-[#2ba351] changed to group-hover:text-[#2c4294] -->
                            <span class="text-base font-bold text-gray-800 line-clamp-1 group-hover:text-[#2c4294] transition-colors">
                                {{ $relProject->title }}
                            </span>
                            <!-- IMPROVED: text-[#2ba351] changed to text-[#2c4294] -->
                            <span class="text-xs font-extrabold text-[#2c4294] mt-0.5">{{ $relProject->short }}</span>
                            <!-- IMPROVED: text-gray-400 changed to text-gray-600 and added aria-hidden="true" -->
                            <span class="text-[10px] text-gray-600 mt-0.5 truncate">
                                <i class="fa-solid fa-location-dot me-1" aria-hidden="true"></i>{{ $relProject->location }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Quick Action: Post requirement -->
            <div class="bg-[#DFE8FF] p-6 rounded-xl border border-blue-50 text-center select-none shadow-sm">
                <!-- IMPROVED: Added aria-hidden="true" to icon -->
                <div class="w-12 h-12 bg-white text-[#224194] rounded-full flex items-center justify-center text-lg mx-auto mb-3 shadow-sm">
                    <i class="fa-solid fa-envelope-open-text" aria-hidden="true"></i>
                </div>
                <!-- IMPROVED: h6 changed to p -->
                <p class="font-extrabold text-gray-800 text-base mb-1 uppercase tracking-wide">Looking to Buy Here?</p>
                <!-- IMPROVED: text-gray-500 changed to text-gray-600 -->
                <p class="text-gray-600 text-xs leading-relaxed mb-4">Post your requirements and let verified developers find the best matching properties in {{ $guide->title }} for you.</p>
                <!-- IMPROVED: bg-[#2ba351] and shadow changed to #2c4294 -->
                <a href="{{ route('requirements.create') }}" class="inline-block bg-[#2c4294] hover:bg-[#1a285a] text-white text-xs font-bold px-6 py-2.5 rounded-xl uppercase tracking-wider transition-all shadow-md shadow-[#2c4294]/10">
                    Post Requirement
                </a>
            </div>

        </div>

    </div>
</div>

{{-- ── 📌 ৫. গ্লোবাল লাইটবক্স মোডাল (Lightbox Modal Markup for Area Sights) ── --}}
@if(!empty($guide->img_paths) && is_array($guide->img_paths))
<div id="lightboxModal" class="hidden fixed inset-0 z-[9999] flex flex-col justify-between items-center p-6 backdrop-blur-md bg-black/95 transition-all duration-300">
    <div class="w-full flex justify-between items-center text-white max-w-7xl">
        <span id="lightboxIndexText" class="text-xs font-bold uppercase tracking-wider opacity-70">Image 1 of 5</span>

        <div class="flex items-center gap-4 bg-white/10 px-4 py-2 rounded-full border border-white/10">
            <!-- IMPROVED: hover:text-[#2ba351] changed to hover:text-[#2c4294] and added aria-hidden="true" -->
            <button type="button" id="zoomInBtn" class="text-white hover:text-[#2c4294] text-lg focus:outline-none transition-all" title="Zoom In"><i class="fa-solid fa-magnifying-glass-plus" aria-hidden="true"></i></button>
            <button type="button" id="zoomOutBtn" class="text-white hover:text-[#2c4294] text-lg focus:outline-none transition-all" title="Zoom Out"><i class="fa-solid fa-magnifying-glass-minus" aria-hidden="true"></i></button>
            <button type="button" id="zoomResetBtn" class="text-white hover:text-[#2c4294] text-lg focus:outline-none transition-all" title="Reset Zoom"><i class="fa-solid fa-compress" aria-hidden="true"></i></button>
        </div>

        <!-- IMPROVED: Added aria-hidden="true" to close icon -->
        <button type="button" id="closeLightboxBtn" class="text-white hover:text-red-500 text-xl focus:outline-none transition-all">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>

    <div class="relative w-full flex-grow flex items-center justify-center my-4 max-w-5xl overflow-hidden select-none">
        <!-- IMPROVED: Added aria-hidden="true" to arrow icons -->
        <button type="button" id="prevLightboxBtn" class="absolute left-0 md:left-4 text-white/50 hover:text-white text-xl md:text-5xl focus:outline-none transition-all z-[10000]"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>

        <div class="max-h-[60vh] max-w-[85vw] flex items-center justify-center rounded-xl overflow-hidden bg-black/20 shadow-2xl border border-white/10 relative">
            <img id="lightboxMainImage" src="" class="max-h-[60vh] max-w-full object-contain transition-transform duration-200 select-none pointer-events-none" style="transform-origin: center; transition: transform 0.15s ease-out;">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20 pointer-events-none text-white/20 text-base md:text-xl font-black uppercase tracking-[0.2em] text-center whitespace-nowrap select-none">
                {{ $setting->site_name ?? 'bdHousing' }}
            </div>
        </div>

        <button type="button" id="nextLightboxBtn" class="absolute right-0 md:right-4 text-white/50 hover:text-white text-xl md:text-5xl focus:outline-none transition-all z-[10000]"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>
    </div>

    <div class="w-full max-w-4xl overflow-x-auto pb-2 scrollbar-hide flex gap-2 justify-center" id="lightboxThumbnailsContainer">
        @foreach($guide->img_paths as $index => $path)
        <button type="button" id="lightbox-thumb-{{ $index }}" class="lightbox-thumb-btn w-16 h-12 md:w-20 md:h-14 rounded-lg overflow-hidden border-2 border-transparent flex-shrink-0 focus:outline-none transition-all">
            <img src="{{ asset('storage/' . $path) }}" class="w-full h-full object-cover pointer-events-none" alt="Lightbox Thumb">
        </button>
        @endforeach
    </div>
</div>

<script>
    const allGalleryImages = @json(array_map(fn($p) => asset('storage/'.$p), $guide->img_paths ?? []));
    let currentLightboxIndex = 0;

    let currentScale = 1;
    let isDragging = false;
    let startX = 0,
        startY = 0;
    let translateX = 0,
        translateY = 0;

    const modal = document.getElementById('lightboxModal');
    const mainImg = document.getElementById('lightboxMainImage');
    const counterText = document.getElementById('lightboxIndexText');

    document.querySelectorAll('[data-lightbox-index]').forEach(el => {
        el.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-lightbox-index'));
            openLightbox(index);
        });
    });

    function openLightbox(index) {
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.classList.add('overflow-hidden');
            switchLightboxImage(index);
        }
    }

    const closeBtn = document.getElementById('closeLightboxBtn');
    if (closeBtn) closeBtn.addEventListener('click', closeLightbox);

    function closeLightbox() {
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.classList.remove('overflow-hidden');
            resetZoom();
        }
    }

    const prevBtn = document.getElementById('prevLightboxBtn');
    const nextBtn = document.getElementById('nextLightboxBtn');
    if (prevBtn) prevBtn.addEventListener('click', () => navigateLightbox(-1));
    if (nextBtn) nextBtn.addEventListener('click', () => navigateLightbox(1));

    function navigateLightbox(direction) {
        switchLightboxImage(currentLightboxIndex + direction);
    }

    document.querySelectorAll('.lightbox-thumb-btn').forEach((btn, idx) => {
        btn.addEventListener('click', () => switchLightboxImage(idx));
    });

    function switchLightboxImage(index) {
        if (!allGalleryImages || allGalleryImages.length === 0) return;

        if (index >= allGalleryImages.length) index = 0;
        if (index < 0) index = allGalleryImages.length - 1;

        currentLightboxIndex = index;
        resetZoom();

        if (mainImg) mainImg.src = allGalleryImages[currentLightboxIndex];

        if (counterText) {
            counterText.textContent = `Image ${currentLightboxIndex + 1} of ${allGalleryImages.length}`;
        }

        document.querySelectorAll('.lightbox-thumb-btn').forEach((btn, idx) => {
            if (idx === currentLightboxIndex) {
                // IMPROVED: border-[#2ba351] updated to #2c4294 inside JS for consistency
                btn.classList.add('border-[#2c4294]');
                btn.classList.remove('border-transparent');
                btn.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            } else {
                btn.classList.add('border-transparent');
                btn.classList.remove('border-[#2c4294]');
            }
        });
    }

    const zoomIn = document.getElementById('zoomInBtn');
    const zoomOut = document.getElementById('zoomOutBtn');
    const zoomReset = document.getElementById('zoomResetBtn');

    if (zoomIn) zoomIn.addEventListener('click', () => zoomImage(1.2));
    if (zoomOut) zoomOut.addEventListener('click', () => zoomImage(0.8));
    if (zoomReset) zoomReset.addEventListener('click', resetZoom);

    function zoomImage(amount) {
        currentScale *= amount;
        if (currentScale < 0.8) currentScale = 0.8;
        if (currentScale > 5) currentScale = 5;

        if (currentScale === 1) {
            translateX = 0;
            translateY = 0;
        }
        updateImageTransform();
    }

    function resetZoom() {
        currentScale = 1;
        translateX = 0;
        translateY = 0;
        updateImageTransform();
    }

    function updateImageTransform() {
        if (mainImg) {
            mainImg.style.transform = `translate(${translateX}px, ${translateY}px) scale(${currentScale})`;
            mainImg.style.cursor = currentScale > 1 ? 'grab' : 'default';
        }
    }

    const imgContainer = mainImg ? mainImg.parentElement : null;
    if (mainImg && imgContainer) {
        imgContainer.addEventListener('mousedown', function(e) {
            if (currentScale <= 1) return;
            e.preventDefault();
            isDragging = true;
            mainImg.style.cursor = 'grabbing';
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            updateImageTransform();
        });

        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                mainImg.style.cursor = currentScale > 1 ? 'grab' : 'default';
            }
        });

        imgContainer.addEventListener('wheel', function(e) {
            e.preventDefault();
            if (e.deltaY < 0) {
                zoomImage(1.1);
            } else {
                zoomImage(0.9);
            }
        }, {
            passive: false
        });
    }

    document.addEventListener('keydown', function(e) {
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'ArrowRight') {
                navigateLightbox(1);
            } else if (e.key === 'ArrowLeft') {
                navigateLightbox(-1);
            } else if (e.key === 'Escape') {
                closeLightbox();
            }
        }
    });
</script>
@endif
@endsection
