@extends('layouts.front')
@section('meta')
@include('frontend.partials.meta', ['pageKey' => 'area-guide'])
@endsection
@section('content')
<section style="border: 1px solid gainsboro;margin-bottom: 20px;">
    <div class="container mx-auto px-6 md:px-10  ">
        <!-- Image Overlap using Negative Margin -->
        <div class="flex   gap-2 text-base  px-4 py-2  border-gray-100">
            <a href="{{ url('/') }}" class="text-gray-600 hover:text-black">
                Home
            </a>

            <span class="text-gray-300">›</span>

            <a href="#" class="text-gray-600 hover:text-black">
                Area Guide
            </a>


        </div>
    </div>
</section>
<div class="container mx-auto py-8 px-4 md:px-8 max-w-7xl">


    <!-- 2. Header Section -->
    <div class="text-center max-w-4xl mx-auto mb-12">
        <!-- IMPROVED: text-[#2ba351] changed to text-[#1b6e35] -->
        <h1 class="text-xl md:text-xl font-black text-[#1b6e35] uppercase tracking-wide mb-3">Explore Neighborhoods</h1>
        <p class="text-gray-500 text-xs md:text-base leading-relaxed">
            Find the perfect place to live. Explore our in-depth area guides containing lifestyle reviews, famous landmarks, school information, average pricing, and transportation facilities of Dhaka's most popular neighborhoods.
        </p>
        <!-- IMPROVED: bg-[#2ba351] changed to bg-[#1b6e35] -->
        <div class="w-24 h-1 bg-[#1b6e35] mx-auto mt-4"></div>
    </div>

    <!-- 3. Responsive Area Guides Grid (3 Columns) -->
    @if($guides->isEmpty())
    <div class="text-center py-16 bg-white border border-gray-100 rounded-3xl p-8 max-w-md mx-auto shadow-sm">
        <i class="fa-solid fa-map-location-dot text-gray-300 text-5xl mb-4" aria-hidden="true"></i>
        <!-- IMPROVED: Swapped h4 for h2 to satisfy heading sequence -->
        <h2 class="font-bold text-gray-700 text-base">No Guides Found</h2>
        <!-- IMPROVED: text-gray-400 changed to text-gray-600 -->
        <p class="text-xs text-gray-600 mt-2">We are currently preparing detailed area guides. Please check back soon!</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($guides as $guide)
        @php
        // Dynamic Specs Parser: Extracting Average Price and Metro Access from features array safely
        $features = $guide->features ?? [];
        $avgPrice = 'N/A';
        $metro = 'N/A';

        foreach($features as $f) {
        if (is_array($f) && isset($f['key'])) {
        $keyLower = strtolower($f['key']);
        // Match keys and assign values dynamically
        if (str_contains($keyLower, 'price')) {
        $avgPrice = $f['value'];
        }
        if (str_contains($keyLower, 'metro')) {
        $metro = $f['value'];
        }
        }
        }
        @endphp

        <!-- Individual Area Card -->
        <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.01)] hover:shadow-md transition-all duration-300 flex flex-col justify-between group">
            <a href="{{ route('area-guides.show', $guide->slug) }}">
            <!-- Featured Image Area -->
            <div class="relative aspect-[16/10] overflow-hidden bg-gray-50">
                <img src="{{ asset('storage/' . $guide->img_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $guide->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                <!-- IMPROVED: Swapped h3 for h2 for optimal heading sequence -->
                <h2 class="absolute bottom-4 left-6 text-white font-black text-xl uppercase tracking-wider">{{ $guide->title }}</h2>
            </div>

            <!-- Card Body -->

                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <!-- Tagline -->
                        <!-- IMPROVED: text-gray-500 changed to text-gray-600 -->
                        <p class="text-gray-600 text-xs font-semibold leading-relaxed mb-4 italic">
                            "{{ $guide->short ?? 'Explore lifestyle & landmarks' }}"
                        </p>

                        <!-- Area Key Facts Grid (Parsed from features array) -->
                        <div class="grid grid-cols-2 gap-3 bg-gray-50/50 p-3 rounded-2xl border border-gray-100 mb-5 text-xs select-none">
                            <div>
                                <!-- IMPROVED: text-gray-400 changed to text-gray-600 for contrast pass -->
                                <span class="text-gray-600 block font-semibold mb-0.5">Avg. Price</span>
                                <span class="text-gray-800 font-extrabold">{{ $avgPrice }}</span>
                            </div>
                            <div>
                                <!-- IMPROVED: text-gray-400 changed to text-gray-600 for contrast pass -->
                                <span class="text-gray-600 block font-semibold mb-0.5">Metro Station</span>
                                <span class="text-gray-800 font-extrabold">{{ $metro }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Read Guide Button -->
                    <!-- IMPROVED: bg-[#2ba351] and shadow changed to #1b6e35 for contrast check -->

                    <span
                        class="w-full text-center bg-[#1b6e35] hover:bg-[#1f7035] text-white py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md shadow-[#1b6e35]/10">
                        Read {{ $guide->title }} Guide
                    </span>

                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif

</div>
@php
$page = $allMeta->get('area-guide');
@endphp
@if($page)
@include('frontend.partials.page_descriptions', ['model' => $page])
@endif
@endsection