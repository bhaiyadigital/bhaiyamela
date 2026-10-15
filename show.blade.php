@extends('layouts.front')
@section('title', $event->title . " | Salt Bay Events | Bhaiya Hotels")

@section('meta')
@php
$schema = [
    "page" => [
        "description" => Str::limit(strip_tags($event->body), 150),
        "keywords" => "Salt Bay, Bhaiya Hotels, Bhaiya Group, Event, " . $event->title,
        "robots" => "index, follow, max-image-preview:large",
        "canonical" => url()->current(),
    ],
    "openGraph" => [
        "type" => "website",
        "title" => $event->title . " | Salt Bay Events",
        "description" => Str::limit(strip_tags($event->body), 150),
        "url" => url()->current(),
        "site_name" => "Salt Bay",
        "image" => asset($event->img_path),
        "locale" => "en_US",
    ],
    "twitter" => [
        "card" => "summary_large_image",
        "title" => $event->title . " | Salt Bay Events",
        "description" => Str::limit(strip_tags($event->body), 150),
        "image" => asset($event->img_path),
    ],
    "breadcrumb" => [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => [
            [
                "@type" => "ListItem",
                "position" => 1,
                "name" => "Home",
                "item" => url('/')
            ],
            [
                "@type" => "ListItem",
                "position" => 2,
                "name" => "Events",
                "item" => route('web.events')
            ],
            [
                "@type" => "ListItem",
                "position" => 3,
                "name" => $event->title,
                "item" => url()->current()
            ],
        ],
    ],
    "organization" => [
        "@context" => "https://schema.org",
        "@graph" => [
            [
                "@type" => "Organization",
                "name" => "Salt Bay",
                "url" => url('/'),
                "logo" => asset('assets/images/logo.png'),
                "sameAs" => [
                    "https://www.facebook.com/Saltbayhotel",
                    "https://www.youtube.com/@SaltbayHotel"
                ],
                "founder" => [
                    "@type" => "Person",
                    "name" => 'Maroof Sattar Ali',
                    "jobTitle" => "Chairman",
                ],
                "parentOrganization" => [
                    "@type" => "Organization",
                    "name" => "Bhaiya Hotels",
                    "parentOrganization" => [
                        "@type" => "Organization",
                        "name" => "Bhaiya Group"
                    ]
                ],
                "foundingDate" => "1972",
                "address" => [
                    "@type" => "PostalAddress",
                    "streetAddress" => "Nabil House, House-09, Road-17, Block-D, Banani",
                    "addressLocality" => "Dhaka",
                    "addressCountry" => "BD"
                ]
            ]
        ]
    ]
];
@endphp

{{-- META TAGS --}}
<meta name="description" content="{{ $schema['page']['description'] }}">
<meta name="keywords" content="{{ $schema['page']['keywords'] }}">
<meta name="robots" content="{{ $schema['page']['robots'] }}">
<link rel="canonical" href="{{ $schema['page']['canonical'] }}">

{{-- OPEN GRAPH --}}
<meta property="og:type" content="{{ $schema['openGraph']['type'] }}">
<meta property="og:title" content="{{ $schema['openGraph']['title'] }}">
<meta property="og:description" content="{{ $schema['openGraph']['description'] }}">
<meta property="og:url" content="{{ $schema['openGraph']['url'] }}">
<meta property="og:site_name" content="{{ $schema['openGraph']['site_name'] }}">
<meta property="og:image" content="{{ $schema['openGraph']['image'] }}">
<meta property="og:locale" content="{{ $schema['openGraph']['locale'] }}">

{{-- TWITTER --}}
<meta name="twitter:card" content="{{ $schema['twitter']['card'] }}">
<meta name="twitter:title" content="{{ $schema['twitter']['title'] }}">
<meta name="twitter:description" content="{{ $schema['twitter']['description'] }}">
<meta name="twitter:image" content="{{ $schema['twitter']['image'] }}">

{{-- BREADCRUMB --}}
<script type="application/ld+json">
{!! json_encode($schema['breadcrumb'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>

{{-- ORGANIZATION SCHEMA --}}
<script type="application/ld+json">
{!! json_encode($schema['organization'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>

<script>
    fbq('track', 'ViewContent', {
        content_name: '{{ $event->title }}',
        content_ids: ['{{ $event->id }}'],
        content_type: 'event'
    });
</script>
@endsection

@section('content')

<section class="relative w-full overflow-hidden cc">
            <div class="relative h-[60vh] md:h-[80vh] lg:h-[100vh] flex items-center justify-center px-4 md:px-8 animate-shrink-header bg-cover bg-center"
                style="background: url('/images/176820798293737.png') no-repeat center center; background-size: cover;">

                <div class="absolute right-0 bottom-0 lg:bottom-0 lg:top-auto
                    bg-white text-black
                    rounded-tl-[35px]
                    w-full max-w-[300px] sm:max-w-[400px] md:max-w-[500px] lg:max-w-[600px]
                    px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12
                    py-4 sm:py-6
                    flex flex-col justify-center items-center text-center shadow-2xl"
                >
                    <h1 class="text-xl sm:text-3xl md:text-3xl lg:text-5xl font-semibold animated-font">
                      Our Event
                    </h1>

                    <p class="text-xs sm:text-sm md:text-base lg:text-lg mt-2 w-full text-gray-500">
                        <span class="text-gray-400">Home / </span>Event
                    </p>
                </div>
            </div>
        </section>

<section class="relative overflow-hidden  p-8">
    <div class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('{{ asset($event->img_path) }}'); filter: blur(8px) brightness(0.4);"></div>

    <!-- Dark overlay for additional depth -->
    <div class="absolute inset-0 bg-black opacity-60"></div>

    <div class="relative z-10">
        <div class="container mx-auto px-4 py-8 sm:py-12 md:py-16">
            <div class="flex flex-col lg:flex-row gap-6 sm:gap-8 items-start max-w-6xl mx-auto">

                <!-- Left Side - Poster -->
                <div class="flex-shrink-0 w-full lg:w-auto flex justify-center lg:justify-start">
                    <div class="w-64 h-72 sm:w-56 sm:h-72 md:w-64 md:h-80 overflow-hidden rounded-lg shadow-2xl cursor-pointer hover:scale-105 transition-transform duration-300"
                        id="eventThumbnail">
                        <img src="{{ asset($event->img_path) }}" alt="Event Thumbnail"
                            class="w-full h-full object-cover" />
                    </div>
                </div>

                <!-- Right Side - Event Details -->
                <div class="text-white space-y-4 sm:space-y-6 flex-1 w-full mt-8">
                    <h1
                        class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold leading-tight text-center lg:text-left">
                        {{$event->title}}
                    </h1>

                    <!-- Event Info -->
                    <div class="flex flex-wrap gap-3 sm:gap-4 justify-center lg:justify-start">
                        <div class="flex items-center space-x-2 sm:space-x-3 bg-[#343434] rounded-lg p-3 sm:p-4">
                            <i class="fas fa-map-marker-alt text-white text-lg sm:text-xl"></i>
                            <span class="text-sm sm:text-base">{{$event->location}}</span>
                        </div>

                        
                    </div>
                    <div class="flex flex-wrap gap-3 sm:gap-4 justify-center lg:justify-start">
                      

                        <div class="flex items-center space-x-2 sm:space-x-3 bg-[#343434] rounded-lg p-3 sm:p-4">
                            <i class="far fa-calendar text-white text-lg sm:text-xl"></i>
                            <span class="text-sm sm:text-base"> {{ \Carbon\Carbon::parse($event->start_date)->format('F d, Y') }}
                            </span>
                        </div>

                        <div class="flex items-center space-x-2 sm:space-x-3 bg-[#343434] rounded-lg p-3 sm:p-4">
                            <i class="far fa-clock text-white text-lg sm:text-xl"></i>
                            <span class="text-sm sm:text-base"> {{ \Carbon\Carbon::parse($event->start_date)->format('g:i A') }}
                            </span>
                        </div>
                    </div>
                    <!-- Countdown Timer and Register Button -->

                  

                    <!-- Social Icons -->
                    <div class="flex  justify-center lg:justify-end space-x-4 sm:space-x-6 pt-2">
                        <a href="#" class="text-[#ce9131] hover:text-red-400 transition-colors">
                            <i class="fab fa-instagram text-xl sm:text-2xl"></i>
                        </a>
                        <a href="#" class="text-[#ce9131] hover:text-red-400 transition-colors">
                            <i class="fab fa-facebook text-xl sm:text-2xl"></i>
                        </a>
                        <a href="#" class="text-[#ce9131] hover:text-red-400 transition-colors">
                            <i class="fab fa-twitter text-xl sm:text-2xl"></i>
                        </a>
                        <a href="#" class="text-[#ce9131] hover:text-red-400 transition-colors">
                            <i class="fab fa-telegram text-xl sm:text-2xl"></i>
                        </a>
                        <a href="#" class="text-[#ce9131] hover:text-red-400 transition-colors">
                            <i class="far fa-copy text-xl sm:text-2xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="imageModal" class="modal">
    <span class="modal-close" id="closeModal"></span>

    <img class="modal-content" id="modalImage" src="{{ asset($event->banner_image) }}" alt="Event Banner">
</div>
<section class="bg-gray-50">
    <div class="container mx-auto px-4 py-12">
        <div class="grid lg:grid-cols-3 gap-8">

            <!-- About The Event Section - Left Side (2 columns) -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Main Event Description Card -->
                <div class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-3xl font-bold text-[#ce9131] mb-6 border-b-4 border-[#ce9131] inline-block pb-2">
                        About The Event</h2>

                    <div class="space-y-4">
                        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                            <span class="text-3xl"></span> {{$event->title}}
                        </h3>

                        <h4 class="text-xl font-semibold text-gray-700">{{$event->location}}</h4>

                        <div class="text-gray-600 leading-relaxed">
                            {!! $event->body !!}
                        </div>
                          <div class="text-gray-600 leading-relaxed">
                            {!! $event->body_2 !!}
                        </div>

                    </div>

                    <!-- Google Map Card -->
                     @if($event->extra)
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="mt-4 rounded-lg h-80 relative overflow-hidden">
                            <!-- Map Background -->
                            <iframe id="mapIframe" src="{{$event->extra}}" width="100%" height="100%" style="border:0;"
                                allowfullscreen="" loading="lazy" class="rounded-lg">
                            </iframe>
                        </div>

                    </div>
                    @endif
                </div>

            </div>

            <!-- Categories/Registration Card - Right Side (1 column) -->
            <div class="lg:col-span-1" id="registerNow">
                <div class="bg-white rounded-lg shadow-md p-8 sticky top-8">
                    <h2 class="text-2xl font-bold text-[#ce9131] mb-6 flex items-center gap-2">
                        <i class="fas fa-tag"></i> Want To Visit
                    </h2>

                    @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('web.event.registration') }}" method="POST" class="space-y-4">

                        @csrf

                        <input type="text" hidden name="event_slug" value="{{$event->name}}">

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Pass Holder Name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('email') border-red-500 @enderror">
                            @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                            <div class="flex gap-2">
                                <select class="px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <option value="+880">🇧🇩</option>
                                </select>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="01812-XXXXXX"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 @error('phone') border-red-500 @enderror">
                            </div>
                            @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="additionalAttendeesContainer" class="space-y-6"></div>

                        <button type="submit"
                            class="w-full bg-[#ce9131] hover:bg-red-600 text-white font-bold py-4 rounded-lg transition-colors shadow-lg">
                            Register Now!
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
<script>
    function updateCountdown() {
        const eventDate = new Date("{{ $event->start_date }}").getTime();
        const now = new Date().getTime();
        const distance = eventDate - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Pad with zeros to ensure 2 digits
        document.getElementById('days').textContent = String(days).padStart(2, '0');
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');

        if (distance < 0) {
            clearInterval(countdownInterval);
            document.getElementById('days').textContent = '00';
            document.getElementById('hours').textContent = '00';
            document.getElementById('minutes').textContent = '00';
            document.getElementById('seconds').textContent = '00';
        }
    }

    const countdownInterval = setInterval(updateCountdown, 1000);
    updateCountdown();
</script>
<script>
    const thumbnail = document.getElementById('eventThumbnail');
    const modal = document.getElementById('imageModal');
    const closeBtn = document.getElementById('closeModal');

    // Open modal when thumbnail is clicked
    thumbnail.addEventListener('click', function() {
        modal.classList.add('active');
    });

    // Close modal when X is clicked
    closeBtn.addEventListener('click', function() {
        modal.classList.remove('active');
    });

    // Close modal when clicking outside the image
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            modal.classList.remove('active');
        }
    });

 
</script>

@endsection