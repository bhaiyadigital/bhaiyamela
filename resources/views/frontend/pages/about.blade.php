@extends('layouts.front')
@section('meta')
    @include('frontend.partials.meta', ['pageKey' => 'about'])
@endsection
@section('content')
@if($aboutHero)
<div class="relative h-[250px] md:h-[350px] lg:h-[400px] w-full flex items-center justify-center overflow-hidden bg-black select-none">
    <!-- Background Image (Falls back to default if no image is uploaded) -->
    <img src="{{ asset($aboutHero->img_path ? 'storage/' . $aboutHero->img_path : 'assets/images/about-hero.jpg') }}"
         class="absolute inset-0 w-full h-full object-cover"
         alt="About Hero Background" />

    <!-- Dark Overlay to ensure optimal text contrast -->
    <div class="absolute inset-0 bg-black/60"></div>

    <!-- Centered Content -->
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
        <span class="text-yellow-500 text-xs font-bold uppercase tracking-widest block mb-2">Who We Are</span>
        <h1 class="text-white text-3xl md:text-5xl font-black uppercase tracking-wider leading-tight mb-2">
            {{ $aboutHero->title }}
        </h1>
        @if($aboutHero->short)
            <p class="text-white/80 text-xs md:text-sm font-semibold max-w-xl mx-auto leading-relaxed">
                {{ $aboutHero->short }}
            </p>
        @endif
    </div>
</div>
@endif

<div class="container mx-auto py-8 px-4 md:px-8 max-w-7xl">


    <!-- 4. Dynamic Core Values Grid Section (Looping over about_value module) -->
    @if($aboutValues->isNotEmpty())
    <div class="mb-16 select-none">
        <h3 class="text-center font-black text-gray-800 text-lg md:text-xl uppercase tracking-widest mb-10">Our Core Values</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($aboutValues as $value)
            <div class="bg-white border border-gray-100 p-6 rounded-xl text-center shadow-[0_10px_30px_rgba(0,0,0,0.01)] hover:shadow-md transition-all duration-300">
                <!-- Dynamic FontAwesome Icon with custom styling -->
                <div class="w-14 h-14 bg-green-50 text-[#2ba351] rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4">
                    <i class="{{ $value->url ?? 'fa-solid fa-award' }}"></i>
                </div>
                <h4 class="text-gray-800 font-extrabold text-sm mb-2 uppercase">{{ $value->title }}</h4>
                <p class="text-gray-500 text-xs leading-relaxed">
                    {{ $value->short }}
                </p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- 5. Dynamic Platform Stats Section (Looping over your existing stats module) -->
    @if($stats->isNotEmpty())
    <div class="bg-white rounded-xl p-8 md:p-10 border border-gray-100 shadow-sm text-center max-w-5xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 select-none justify-center">
            @foreach($stats as $stat)
            <div>
                <!-- Dynamic Numeric/Short text (e.g. 500+) -->
                <span class="text-2xl md:text-3xl font-black text-[#2ba351] block mb-1">{{ $stat->short }}</span>
                <!-- Dynamic Stat Title (e.g. Premium Developers) -->
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $stat->title }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection