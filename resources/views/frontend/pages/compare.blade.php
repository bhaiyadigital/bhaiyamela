@extends('layouts.front')

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
                    Compare
                </a>


            </div>
        </div>
    </section>


    <div class="container mx-auto py-8 px-4 md:px-8 max-w-7xl">

        <!-- 1. Breadcrumbs -->

        <!-- 2. Header Section -->
        <div class="text-center mb-10">
            <h1 class="text-xl md:text-xl font-black text-[#2ba351] uppercase tracking-wide">Property Comparison</h1>
            <div class="w-24 h-1 bg-[#2ba351] mx-auto mt-2"></div>
        </div>

        @if($projects->isEmpty())
            <!-- Fallback if no properties are selected -->
            <div class="text-center py-16 bg-white border border-gray-100 rounded-3xl p-8 max-w-md mx-auto">
                <i class="fa-solid fa-scale-unbalanced text-gray-300 text-5xl mb-4"></i>
                <h4 class="font-bold text-gray-800 text-base mb-3">No Properties Selected</h4>
                <p class="text-xs text-gray-400 mb-6 leading-relaxed">Select up to 3 properties from our listing page to compare
                    them side by side.</p>
                <a href="{{ route('projects.index') }}"
                    class="inline-block bg-[#2ba351] hover:bg-[#1a285a] text-white px-6 py-3 rounded-xl font-bold text-xs uppercase tracking-wider transition-all">Go
                    to Properties</a>
            </div>
        @else
            @php
                // Exact spec parser matching your standard details page logic
                $specs = [];
                foreach ($projects as $p) {
                    $featuresList = $p->features ?? [];
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

                    $specs[$p->id] = [
                        'bedrooms' => $bedrooms,
                        'baths' => $baths,
                        'size' => $size
                    ];
                }
            @endphp


            <div class="hidden lg:block overflow-x-auto scrollbar-hide">
                <div class="min-w-[650px] bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">

                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="p-6 text-base font-bold text-gray-500 w-1/4">Features</th>
                                @foreach($projects as $project)
                                    @php
                                        $images = $project->img_paths;
                                        $logo = !empty($images) ? $images[0] : 'assets/images/placeholder.jpg';
                                    @endphp
                                    <th class="p-6 w-1/4">
                                        <div class="flex flex-col gap-3">
                                            <div
                                                class="aspect-[16/11] w-full rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                                                <img src="{{ asset('storage/' . $logo) }}" class="w-full h-full object-cover"
                                                    alt="Property">
                                            </div>
                                            <h3
                                                class="font-extrabold text-gray-800 text-base leading-tight line-clamp-2 min-h-[40px]">
                                                {{ $project->title }}</h3>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 text-base">
                            <tr>
                                <td class="p-6 font-bold text-gray-500 bg-gray-50/50">Price</td>
                                @foreach($projects as $project)
                                    <td class="p-6 font-black text-[#2ba351] text-base">{{ $project->short }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-500 bg-gray-50/50">Location</td>
                                @foreach($projects as $project)
                                    <td class="p-6 text-gray-700 font-medium">
                                        <i class="fa-solid fa-location-dot me-1 text-gray-400"></i>
                                        {{ $project->location ?? 'N/A' }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-500 bg-gray-50/50">Bedrooms</td>
                                @foreach($projects as $project)
                                    <td class="p-6 text-gray-800 font-semibold">{{ $specs[$project->id]['bedrooms'] }} Beds</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-500 bg-gray-50/50">Baths</td>
                                @foreach($projects as $project)
                                    <td class="p-6 text-gray-800 font-semibold">{{ $specs[$project->id]['baths'] }} Baths</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-500 bg-gray-50/50">Property Size</td>
                                @foreach($projects as $project)
                                    <td class="p-6 text-gray-800 font-semibold">{{ $specs[$project->id]['size'] }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-500 bg-gray-50/50">Property Type</td>
                                @foreach($projects as $project)
                                    <td class="p-6 text-gray-700 font-medium">{{ $project->category->title ?? 'N/A' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-500 bg-gray-50/50">Developer</td>
                                @foreach($projects as $project)
                                    <td class="p-6">
                                        @if($project->company)
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-200">
                                                    <img src="{{ $project->company->company_logo ? asset('storage/' . $project->company->company_logo) : asset('assets/images/placeholder.jpg') }}"
                                                        class="w-full h-full object-cover" alt="Logo">
                                                </div>
                                                <span
                                                    class="font-bold text-[#2ba351] text-xs">{{ $project->company->company_name }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-200">
                                                    <img src="{{ $setting ? asset('storage/' . $setting->logo) : asset('assets/images/placeholder.jpg') }}"
                                                        class="w-full h-full object-cover" alt="Logo">
                                                </div>
                                                <span
                                                    class="font-bold text-[#2ba351] text-xs">{{ $setting ? $setting->site_name : 'Bhaiya Group' }}</span>
                                            </div>

                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td class="p-6 font-bold text-gray-500 bg-gray-50/50">Action</td>
                                @foreach($projects as $project)
                                    <td class="p-6">
                                        <a href="{{ route('project.details', $project->slug) }}"
                                            class="inline-block w-full text-center bg-[#2ba351] hover:bg-[#1a285a] text-white py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all">View
                                            Details</a>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>


            {{-- ════════════════════════════════════════
            ২. মোবাইল লেআউট (Visible ONLY on Mobile/Tablets - lg:hidden)
            ════════════════════════════════════════ --}}
            <div class="block lg:hidden space-y-6">

                <!-- Quick comparison items header card -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                    @foreach($projects as $index => $project)
                        @php
                            $images = $project->img_paths;
                            $logo = !empty($images) ? $images[0] : 'assets/images/placeholder.jpg';
                        @endphp
                        <div class="flex flex-col gap-2 text-center min-w-0">
                            <div class="aspect-square w-full rounded-xl overflow-hidden border bg-gray-50">
                                <img src="{{ asset('storage/' . $logo) }}" class="w-full h-full object-cover">
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Property
                                {{ $loop->iteration }}</span>
                            <h4 class="font-extrabold text-gray-800 text-xs line-clamp-1 leading-tight">{{ $project->title }}</h4>
                        </div>
                    @endforeach
                </div>

                <!-- Vertical Specs Stack Cards -->
                <div class="space-y-4">

                    <!-- Price comparison card -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-gray-400 uppercase mb-2.5 tracking-wider">Price</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($projects as $project)
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[9px] font-bold text-gray-400">Property {{ $loop->iteration }}</span>
                                    <span class="text-xs md:text-base font-black text-[#2ba351] mt-0.5">{{ $project->short }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Location comparison card -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-gray-400 uppercase mb-2.5 tracking-wider">Location</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($projects as $project)
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[9px] font-bold text-gray-400">Property {{ $loop->iteration }}</span>
                                    <span class="text-xs font-bold text-gray-700 mt-0.5 truncate"
                                        title="{{ $project->location }}">{{ $project->location ?? 'N/A' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bedrooms comparison card -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-gray-400 uppercase mb-2.5 tracking-wider">Bedrooms</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($projects as $project)
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[9px] font-bold text-gray-400">Property {{ $loop->iteration }}</span>
                                    <span class="text-xs font-extrabold text-gray-800 mt-0.5">{{ $specs[$project->id]['bedrooms'] }}
                                        Beds</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Baths comparison card -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-gray-400 uppercase mb-2.5 tracking-wider">Baths</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($projects as $project)
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[9px] font-bold text-gray-400">Property {{ $loop->iteration }}</span>
                                    <span class="text-xs font-extrabold text-gray-800 mt-0.5">{{ $specs[$project->id]['baths'] }}
                                        Baths</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Size comparison card -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-gray-400 uppercase mb-2.5 tracking-wider">Property Size</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($projects as $project)
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[9px] font-bold text-gray-400">Property {{ $loop->iteration }}</span>
                                    <span
                                        class="text-xs font-extrabold text-gray-800 mt-0.5 truncate">{{ $specs[$project->id]['size'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Developer comparison card -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-gray-400 uppercase mb-2.5 tracking-wider">Developer</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($projects as $project)
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[9px] font-bold text-gray-400">Property {{ $loop->iteration }}</span>
                                    @if($project->company)
                                        <span class="text-xs font-bold text-[#2ba351] mt-0.5 truncate"
                                            title="{{ $project->company->company_name }}">{{ $project->company->company_name }}</span>
                                    @else
                                        <span class="text-xs text-gray-400 mt-0.5">Admin</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Action button comparison card -->
                    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                        <span class="block text-xs font-bold text-gray-400 uppercase mb-3.5 tracking-wider">Actions</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($projects as $project)
                                <div class="flex flex-col gap-1 min-w-0">
                                    <span class="text-[9px] font-bold text-gray-400">Property {{ $loop->iteration }}</span>
                                    <a href="{{ route('project.details', $project->slug) }}"
                                        class="inline-block w-full text-center bg-[#2ba351] hover:bg-[#1a285a] text-white py-2 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all">Details</a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        @endif

    </div>
@endsection
