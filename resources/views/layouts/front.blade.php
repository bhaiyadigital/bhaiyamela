<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="index, follow, max-image-preview:large">
    <meta name="googlebot" content="noindex, nofollow">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">


    @yield('meta')

    @if(!empty($setting->favicon))
        <link rel="icon" href="{{ asset('storage/' . $setting->favicon) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
        <!-- Default Static Fallback Favicons -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link class="favicon-png" rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link class="favicon-png" rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('site.webmanifest') }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    @if(isset($project) && is_object($project))
        @php
            if (!empty($project->img_path)) {
                $heroImageUrl = asset('storage/' . $project->img_path);
            } else {
                $images = $project->img_paths;
                $firstImage = !empty($images) ? $images[0] : null;
                $heroImageUrl = $firstImage ? asset('storage/' . $firstImage) : null;
            }
        @endphp
        @if($heroImageUrl)
            <link rel="preload" as="image" href="{{ $heroImageUrl }}">
        @endif
    @elseif(isset($projects) && (is_array($projects) || is_object($projects)) && count($projects) > 0)
        @php
            $firstProject = is_array($projects) ? $projects[0] : $projects->first();
            if (!empty($firstProject->img_path)) {
                $heroImageUrl = asset('storage/' . $firstProject->img_path);
            } else {
                $images = $firstProject->img_paths;
                $firstImage = !empty($images) ? $images[0] : null;
                $heroImageUrl = $firstImage ? asset('storage/' . $firstImage) : null;
            }
        @endphp
        @if($heroImageUrl)
            <link rel="preload" as="image" href="{{ $heroImageUrl }}">
        @endif
    @endif

    <link rel="preload" href="{{ asset('fontawesome/css/all.min.css') }}" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    </noscript>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('build/assets/app-tn0RQdqM.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/app-CHwZotqq.css') }}">

    <script type="module" src="{{ asset('build/assets/app-Cj6ktIgB.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @font-face {
            font-family: 'Noto Sans Bengali';
            src: url('{{ asset("fonts/NotoSansBengali-Regular.ttf") }}') format('truetype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Noto Sans Bengali';
            src: url('{{ asset("fonts/NotoSansBengali-Medium.ttf") }}') format('truetype');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Noto Sans Bengali';
            src: url('{{ asset("fonts/NotoSansBengali-SemiBold.ttf") }}') format('truetype');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Noto Sans Bengali';
            src: url('{{ asset("fonts/NotoSansBengali-Bold.ttf") }}') format('truetype');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }
        body {
            font-family: 'Noto Sans Bengali', sans-serif !important;
        }
    </style>
</head>

<body class="bg-[#F2F4F7]" data-page="index">
    <style>
        .select2-container .select2-selection--single {
            background-color: #f9fafb !important;
            /* bg-gray-50 */
            border: 1px solid #e5e7eb !important;
            /* border-gray-200 */
            border-radius: 12px !important;
            /* rounded-xl */
            height: 42px !important;
            /* Matches py-2.5 input height */
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            outline: none !important;
            box-shadow: none !important;
            transition: all 0.2s ease-in-out;
        }

        /* Injecting FontAwesome Search Glass Icon on the Left Inside the Select Box */
        .select2-container--default .select2-selection--single::before {
            content: "\f002";
            /* FontAwesome 6 Search icon unicode */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            /* text-gray-400 */
            font-size: 11px;
            pointer-events: none;
            z-index: 10;
        }

        /* Adjusting text padding to make room for the left search icon */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #4b5563 !important;
            /* text-gray-600 */
            font-size: 0.75rem !important;
            /* text-xs */
            font-weight: 700 !important;
            /* font-bold */
            padding-left: 28px !important;
            /* Safe padding for search glass */
        }

        /* Adjusting default dropdown arrow vertical position */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
            right: 10px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #9ca3af transparent transparent transparent !important;
        }

        /* Active open state focus styling matching Tailwind text input focus ring */
        .select2-container--open .select2-selection--single {
            border-color: #2c4294 !important;
            /* Active green border */
            box-shadow: 0 0 0 2px rgba(27, 110, 53, 0.2) !important;
            /* Focus ring */
        }

        .select2-search--dropdown {
            display: block !important;
        }

        .select2-search--dropdown .select2-search__field {
            display: block !important;
            width: 100% !important;
        }
    </style>
    <!-- হেডার বা নেভিগেশন এরিয়া -->
    @include('frontend.partials.header')

    <!-- মেইন কন্টেন্ট এরিয়া -->
    <main class="min-h-screen">
        @yield('content')
    </main>
    <div id="confirmModal"
        class="hidden fixed inset-0 z-[10000] flex items-center justify-center p-4 backdrop-blur-sm bg-black/60 transition-all duration-300 select-none">
        <div
            class="bg-white rounded-3xl p-6 border border-gray-100 max-w-sm w-full text-center shadow-2xl scale-95 opacity-0 transition-all duration-300">
            <div id="confirmModalIcon"
                class="w-16 h-16 bg-red-50 text-red-500 rounded-full mx-auto flex items-center justify-center text-xl mb-4">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h4 id="confirmModalTitle" class="font-extrabold text-gray-800 text-lg mb-2">Are you sure?</h4>
            <p id="confirmModalText" class="text-gray-500 text-xs leading-relaxed mb-6">Do you really want to perform
                this action?</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeConfirmModal()"
                    class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all">Cancel</button>
                <button type="button" id="confirmModalBtn" onclick="executeConfirmAction()"
                    class="w-1/2 bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-red-500/10">Yes,
                    Remove</button>
            </div>
        </div>
    </div>
    <!-- ফুটার এরিয়া -->


    @include('frontend.partials.footer')
    <button type="button" id="scrollToTopBtn"
        class="hidden opacity-0 fixed bottom-6 right-6 z-[2000] bg-[#2c4294] hover:bg-[#1a285a] text-white w-11 h-11 rounded-full flex items-center justify-center transition-all duration-300 shadow-lg shadow-[#2ba351]/20 hover:scale-110 active:scale-95 cursor-pointer">
        <!-- FontAwesome Up Arrow Icon -->
        <i class="fa-solid fa-arrow-up text-sm"></i>
    </button>
    <!-- অন্যান্য পেজের স্ক্রিপ্ট পুশ করার স্ট্যাক -->
    @stack('scripts')

    <!-- মোবাইল নেভিগেশন ড্রয়ার হ্যান্ডলার -->
    <script>
        const menuToggle = document.getElementById("menu-toggle");
        const closeDrawer = document.getElementById("close-drawer");
        const mobileDrawer = document.getElementById("mobile-drawer");
        const drawerOverlay = document.getElementById("drawer-overlay");

        function toggleMenu() {
            if (mobileDrawer) {
                mobileDrawer.classList.toggle("translate-x-full");
            }
        }

        if (menuToggle && closeDrawer && drawerOverlay) {
            menuToggle.addEventListener("click", toggleMenu);
            closeDrawer.addEventListener("click", toggleMenu);
            drawerOverlay.addEventListener("click", toggleMenu);
        }
        // ── Global Scroll-To-Top Button Logic (Vanilla JS) ──
        document.addEventListener('DOMContentLoaded', function () {
            const scrollTopBtn = document.getElementById('scrollToTopBtn');

            if (scrollTopBtn) {
                // Monitor scroll position to show/hide the button dynamically
                window.addEventListener('scroll', function () {
                    if (window.scrollY > 300) {
                        scrollTopBtn.classList.remove('hidden', 'opacity-0');
                        scrollTopBtn.classList.add('flex', 'opacity-100');
                    } else {
                        scrollTopBtn.classList.remove('flex', 'opacity-100');
                        scrollTopBtn.classList.add('hidden', 'opacity-0');
                    }
                });

                // Smoothly scroll back to the very top on button click
                scrollTopBtn.addEventListener('click', function () {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>

    <!-- Facebook Pixel -->
    <script>
        setTimeout(() => {
            function loadPixel() {
                if (window.fbLoaded) return;
                ! function (f, b, e, v, n, t, s) {
                    if (f.fbq) return;
                    n = f.fbq = function () {
                        n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq) f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '{{ config('services.facebook.pixel_id') }}');
                @if(isset($fbEventId))
                    fbq('track', 'PageView', {}, { eventID: '{{ $fbEventId }}' });
                @else
                    fbq('track', 'PageView');
                @endif

                @if(session('success'))
                    fbq('track', 'Lead');
                @endif

                @if(in_array(Route::currentRouteName(), ['project.details', 'web.project.category', 'web.page', 'web.blog.details', 'area-guides.show']))
                    fbq('track', 'ViewContent');
                @endif

                @stack('fb_pixel_events')

                window.fbLoaded = true;
            }
            ['mouseover', 'scroll', 'touchstart'].forEach(event => {
                window.addEventListener(event, loadPixel, {
                    once: true
                });
            });
        }, 1500);
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ config('services.facebook.pixel_id') }}&ev=PageView&noscript=1" />
    </noscript>
</body>

</html>