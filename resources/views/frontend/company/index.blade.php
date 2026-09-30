@extends('layouts.front')

@section('meta')
    @include('frontend.partials.meta', ['pageKey' => 'developer'])
@endsection

@section('content')
    <section class="  " style="border: 1px solid gainsboro;margin-bottom: 20px;">
        <div class="container mx-auto px-4  ">
            <!-- Image Overlap using Negative Margin -->
            <div class="flex gap-2 text-base py-2 rounded-full border-gray-100">

                <a href="{{ url('/') }}" class="text-gray-600 hover:text-black">
                    Home
                </a>

                <span class="text-gray-300">›</span>

                <a href="#" class="text-gray-600 hover:text-black">
                    Concerns
                </a>


            </div>
        </div>
    </section>
    <div class="container mx-auto py-8 px-4 md:px-8">



        <!-- 2. Header Content -->
        <div class="text-center max-w-5xl mx-auto mb-10">
            <h1 class="text-xl md:text-xl font-black text-[#2ba351] uppercase tracking-wide mb-4">
                Get Along With The Top Real Estate Companies in Bangladesh
            </h1>
            <p class="text-gray-600 text-xs md:text-base leading-relaxed">
                Searching for the best builder or concern companies in Bangladesh? You have certainly arrived at the right
                destination. bdHousing.com is working hand to hand with the following top real estate companies in
                Bangladesh. We believe the BDHOUSING member list will facilitate in a way that you will be amazed at the
                collection of properties like apartment or flat, commercial space, industrial space, office space, shop.
                Most importantly, you will get to know some of the current and most valuable apartment and land projects in
                Bangladesh run by those.
            </p>
        </div>

        <!-- 3. Section Title -->
        <div class="text-center mb-8">
            <h2 class="text-lg md:text-xl font-extrabold text-[#1b6e35] uppercase tracking-wider">
                Our Platinum Members
            </h2>
            <div class="w-24 h-1 bg-[#1b6e35] mx-auto mt-2"></div>
        </div>
        <!-- Dynamic Company Search Bar (Fixed Icon Alignment) -->
        <div class="max-w-md mx-auto mb-12">
            <form action="{{ route('developers.index') }}" method="GET" class="relative w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search concern or company..."
                    class="w-full bg-white border border-gray-150 pl-11 pr-10 py-3.5 rounded-2xl text-base font-bold text-gray-700 outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all shadow-sm">

                <!-- Left Search Icon (Fixed to left-4 and wrapped in flex for perfect vertical centering) -->
                <div class="absolute left-4 top-[70%] -translate-y-1/2 text-gray-400 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-base"></i>
                </div>

                <!-- Right Clear Search Button (Wrapped in flex for perfect vertical centering) -->
                @if(request('search'))
                    <a href="{{ route('developers.index') }}"
                        class="absolute right-4 top-[70%] -translate-y-1/2 text-red-500 hover:text-red-700 font-extrabold text-base transition-colors flex items-center">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </a>
                @endif
            </form>
        </div>
        <!-- 4. Companies Responsive Grid (6 Columns on Desktop) -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6 mb-8">
            @forelse($companies as $company)
                @php
                    $logo = $company->company_logo ? 'storage/' . $company->company_logo : 'assets/images/placeholder.jpg';
                @endphp

                <!-- Company Card link filters project list by selected developer id -->
                <a href="{{ route('web.project', ['company' => $company->id]) }}"
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
                            class="text-xs md:text-base font-extrabold text-[#1b6e35] group-hover:underline leading-tight line-clamp-2">
                            {{ $company->company_name }}
                        </h3>
                    </div>

                </a>
            @empty
                <div class="col-span-full text-center py-10 text-gray-400">
                    <p class="font-semibold text-base">No premium concerns found at the moment.</p>
                </div>
            @endforelse
        </div>

        <!-- 5. Custom Pagination Links -->
        <div class="mt-16 flex justify-center">
            {{ $companies->appends(request()->query())->links('frontend.partials.custom_pagination') }}
        </div>

    </div>
    @php
        $page = $allMeta->get('developer');
    @endphp
    @if($page)
        @include('frontend.partials.page_descriptions', ['model' => $page])
    @endif
@endsection