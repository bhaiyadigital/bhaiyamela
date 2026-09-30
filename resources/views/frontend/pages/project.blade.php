@extends('layouts.front')
@section('meta')
    @include('frontend.partials.meta', ['pageKey' => 'project'])
@endsection
@section('content')
    @php
        $pageTitle = 'Our Properties';

        if (isset($activeCompany) && isset($activeCategory)) {
            $pageTitle = $activeCompany->company_name . ' ' . $activeCategory->title;
        } elseif (isset($activeCompany)) {
            $pageTitle = $activeCompany->company_name . ' Properties';
        } elseif (isset($activeCategory)) {
            $pageTitle = $activeCategory->title;
        }
    @endphp
    <section class="  " style="border: 1px solid gainsboro;margin-bottom: 20px;">
        <div class="container mx-auto px-4">
            <!-- Image Overlap using Negative Margin -->
            <div class="flex gap-2 text-base py-2  border-gray-100">

                <a href="{{ url('/') }}" class="text-gray-600 hover:text-black">
                    Home
                </a>

                <span class="text-gray-300">›</span>

                <a href="/properties" class="text-gray-600 hover:text-black">
                    Properties
                </a>
                @if(isset($activeCategory))

                    <span class="text-gray-300">›</span>
                    <span class="text-gray-600">
                        {{ $activeCategory->title }}
                    </span>
                @endif



            </div>
        </div>
    </section>
    <div class="container py-8 mx-auto px-4">

        @if(isset($activeCategory) || isset($activeCompany))
            <!-- White Card Container for Filter Titles & Descriptions -->
            <div class="bg-white p-6 mb-10 text-left rounded-sm">
                <h2 class="text-xl md:text-xl font-black text-[#2ba351] uppercase tracking-wide mb-3">
                    {{ $pageTitle }}
                </h2>
                <div class="w-16 h-1 bg-[#2ba351] mb-6"></div>

                @if(!empty($activeCategory->description))
                    <div class="text-gray-600 text-base leading-relaxed">
                        {!! $activeCategory->description !!}
                    </div>
                @else
                    <p class="text-gray-500 text-base leading-relaxed">
                        Explore our curated selection of premium properties in the {{ $activeCategory->title ?? "General" }}
                        category. Find your dream home or investment opportunity today.
                    </p>
                @endif
            </div>
        @else
            <div class="bg-white p-6 mb-10 text-left rounded-sm">

                <h2 class="text-xl md:text-xl font-black text-[#2ba351] uppercase tracking-wide mb-3">
                    Our Properties

                </h2>
                <div class="w-16 h-1 bg-[#2ba351] mb-6"></div>
                <p class="text-gray-500 text-base leading-relaxed max-w-3xl">
                    Browse our extensive catalog of premium residential and commercial projects listed by Bangladesh's top
                    developers.
                </p>
            </div>
        @endif
        <div class="block lg:hidden mb-6">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-600 border-b border-gray-100 pb-3 mb-4 uppercase tracking-wider">
                    Filter Properties
                </p>
                <form action="{{ route('web.project') }}" method="GET" class="flex flex-col gap-4">
                    <div>
                        <label for="mob-search-input" class="block text-xs font-bold text-gray-600 mb-1.5">Search
                            Keyword</label>
                        <input id="mob-search-input" type="text" name="search" value="{{ request('search') }}"
                            placeholder="e.g. Uttara Flat"
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all">
                    </div>
                    <div>
                        <label for="mob-filter-company" class="block text-xs font-bold text-gray-600 mb-1.5">Concern /
                            Company</label>
                        <select id="mob-filter-company" name="company"
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-600">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="mob-filter-category"
                            class="block text-xs font-bold text-gray-600 mb-1.5">Category</label>
                        <select id="mob-filter-category" name="category"
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-600">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="mob-filter-destination" class="block text-xs font-bold text-gray-600 mb-1.5">Location /
                            Destination</label>
                        <select id="mob-filter-destination" name="destination"
                            class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#1b6e35]/20 transition-all text-gray-600">
                            <option value="">All Locations</option>
                            @foreach($destinations as $dest)
                                <option value="{{ $dest->id }}" {{ request('destination') == $dest->id ? 'selected' : '' }}>
                                    {{ $dest->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full bg-[#1b6e35] hover:bg-[#1f7035] text-white py-2.5 rounded-xl font-bold text-xs transition-all">
                        Apply Filter
                    </button>
                    @if(request('search') || request('category') || request('destination') || request('company'))
                        <a href="{{ route('web.project') }}"
                            class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl font-bold text-xs transition-all">
                            Clear Filters
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="lg:col-span-3 flex flex-col gap-6">

                <div class="hidden lg:block bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-600 border-b border-gray-100 pb-3 mb-4 uppercase tracking-wider">
                        Filter Properties
                    </p>
                    <form action="{{ route('web.project') }}" method="GET" class="flex flex-col gap-4">
                        <div>
                            <label for="search-input" class="block text-xs font-bold text-gray-600 mb-1.5">Search
                                Keyword</label>
                            <input id="search-input" type="text" name="search" value="{{ request('search') }}"
                                placeholder="e.g. Uttara Flat"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all">
                        </div>

                        <div>
                            <!-- Added: for="filter-company" -->
                            <label for="filter-company" class="block text-xs font-bold text-gray-600 mb-1.5">Concern /
                                Company</label>
                            <!-- Added: id="filter-company" -->
                            <select id="filter-company" name="company"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all text-gray-600">
                                <option value="">All Companies</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->slug }}" {{ request('company') == $company->slug ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <!-- Added: for="filter-category" -->
                            <label for="filter-category"
                                class="block text-xs font-bold text-gray-600 mb-1.5">Category</label>
                            <!-- Added: id="filter-category" -->
                            <select id="filter-category" name="category"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-xs outline-none focus:ring-2 focus:ring-[#2ba351]/20 transition-all text-gray-600">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1.5">Location / Destination</label>

                            <!-- This styled div acts as a button that triggers your custom geolocation-supported modal -->
                            <div onclick="openLocationModal()"
                                class="w-full bg-gray-50 border border-gray-200 px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 flex justify-between items-center cursor-pointer select-none">
                                <span>Select Location...</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400"></i>
                            </div>
                        </div>

                        <!-- IMPROVED: Background changed from bg-[#2ba351] to bg-[#1b6e35] for color contrast compliance -->
                        <button type="submit"
                            class="w-full bg-[#1b6e35] hover:bg-[#1f7035] text-white py-2.5 rounded-xl font-bold text-xs transition-all">
                            Apply Filter
                        </button>

                        @if(request('search') || request('category') || request('destination') || request('company'))
                            <a href="{{ route('web.project') }}"
                                class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl font-bold text-xs transition-all">
                                Clear Filters
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Recently Added Properties -->
                <!-- . Recently Added -->
                @if($recentlyAdded->isNotEmpty())
                    <div class="hidden lg:block bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <!-- IMPROVED: Changed text-gray-500 to text-gray-600 for contrast compliance -->
                        <p class="text-xs font-bold text-gray-600 border-b border-gray-100 pb-3 mb-4 uppercase tracking-wider">
                            Recently Added
                        </p>
                        <div class="flex flex-col gap-4">
                            @foreach($recentlyAdded as $recAdd)
                                @php
                                    $addImages = $recAdd->img_paths;
                                    $addThumb = !empty($addImages) ? $addImages[0] : 'assets/images/placeholder.jpg';
                                @endphp
                                <a href="{{ route('project.details', $recAdd->slug) }}" class="flex items-center gap-3 group">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                                        <!-- IMPROVED: Replaced non-descriptive alt text with the actual project title -->
                                        <img src="{{ asset('storage/' . $addThumb) }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                            alt="{{ $recAdd->title }}">
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <!-- IMPROVED: Darkened active hover green from group-hover:text-[#2ba351] to group-hover:text-[#1b6e35] -->
                                        <span
                                            class="text-base font-bold text-gray-800 line-clamp-1 group-hover:text-[#1b6e35] transition-colors">
                                            {{ $recAdd->title }}
                                        </span>
                                        <!-- IMPROVED: Darkened green text from text-[#2ba351] to text-[#1b6e35] -->
                                        <span class="text-xs font-extrabold text-[#1b6e35] mt-0.5">{{ $recAdd->short }}</span>
                                        <!-- IMPROVED: Adjusted gray from text-gray-400 to text-gray-600 and hid icon from screen readers -->
                                        <span class="text-[10px] text-gray-600 mt-0.5 truncate">
                                            <i class="fa-solid fa-location-dot me-1"
                                                aria-hidden="true"></i>{{ $recAdd->destination->title }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- . Recently Viewed -->
                @if($recentlyViewed->isNotEmpty())
                    <div class="hidden lg:block bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <!-- IMPROVED: Changed text-gray-500 to text-gray-600 for contrast compliance -->
                        <p class="text-xs font-bold text-gray-600 border-b border-gray-100 pb-3 mb-4 uppercase tracking-wider">
                            Recently Viewed
                        </p>
                        <div class="flex flex-col gap-4">
                            @foreach($recentlyViewed as $recView)
                                @php
                                    $viewImages = $recView->img_paths;
                                    $viewThumb = !empty($viewImages) ? $viewImages[0] : 'assets/images/placeholder.jpg';
                                @endphp
                                <a href="{{ route('project.details', $recView->slug) }}" class="flex items-center gap-3 group">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-50 flex-shrink-0">
                                        <!-- IMPROVED: Replaced generic alt text with the actual project title -->
                                        <img src="{{ asset('storage/' . $viewThumb) }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                            alt="{{ $recView->title }}">
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <!-- IMPROVED: Darkened active hover green to group-hover:text-[#1b6e35] -->
                                        <span
                                            class="text-base font-bold text-gray-800 line-clamp-1 group-hover:text-[#1b6e35] transition-colors">
                                            {{ $recView->title }}
                                        </span>
                                        <!-- IMPROVED: Darkened green text to text-[#1b6e35] -->
                                        <span class="text-xs font-extrabold text-[#1b6e35] mt-0.5">{{ $recView->short }}</span>
                                        <!-- IMPROVED: Adjusted text-gray-400 to text-gray-600 and added aria-hidden to icon -->
                                        <span class="text-[10px] text-gray-600 mt-0.5 truncate">
                                            <i class="fa-solid fa-location-dot me-1"
                                                aria-hidden="true"></i>{{ $recView->destination->title }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
            <div class="lg:col-span-9">
                <!-- 2. Header Title & Dynamic Description Section (Styled with White Card Background) -->


                <div id="projectGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @include('frontend.partials._items', ['projects' => $projects])
                </div>
                <div id="compareBar"
                    class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-[1000] bg-white border border-gray-100 rounded-3xl p-4 shadow-[0_20px_50px_rgba(0,0,0,0.15)] flex items-center justify-between gap-6 max-w-lg w-full transition-all duration-300">
                    <div class="flex items-center gap-3" id="compareThumbs">
                        <!-- Selected project thumbnails will render here via JS -->
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="clearCompareList()"
                            class="text-xs text-red-500 font-bold hover:underline px-2 py-1">Clear</button>
                        <a id="compareBtnLink" href="#"
                            class="bg-[#2ba351] hover:bg-[#1f7035] text-white text-xs font-extrabold px-4 py-3 rounded-xl uppercase tracking-wider transition-all shadow-md shadow-[#2ba351]/10">Compare
                            Now</a>
                    </div>
                </div>
                <div id="loadMoreSentinel" class="text-center py-8 {{ $projects->hasMorePages() ? '' : 'hidden' }}">
                    <div class="inline-flex items-center gap-2 text-gray-500 font-semibold text-base">
                        <i class="fa-solid fa-spinner fa-spin text-lg text-[#2ba351]"></i>
                        Loading more properties...
                    </div>
                </div>

                <div id="noMoreProjectsMessage"
                    class="text-center py-8 text-gray-600 font-semibold text-base {{ !$projects->hasMorePages() ? '' : 'hidden' }}">
                    No more properties to display.
                </div>
            </div>


        </div>
    </div>
    @php
        $page = $allMeta->get('project');
    @endphp
    @if($page)
        @include('frontend.partials.page_descriptions', ['model' => $page])
    @endif
    @include('frontend.partials.location_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let nextPageUrl = "{{ $projects->nextPageUrl() }}";
            const gridContainer = document.getElementById('projectGrid');
            const sentinel = document.getElementById('loadMoreSentinel');
            const endMessage = document.getElementById('noMoreProjectsMessage');
            let isLoading = false;


            if (sentinel && gridContainer) {

                const observer = new IntersectionObserver(async (entries) => {
                    const firstEntry = entries[0];
                    console.log("5. Observer Triggered. Is Intersecting?:", firstEntry.isIntersecting);

                    if (firstEntry.isIntersecting && nextPageUrl && !isLoading) {
                        isLoading = true;

                        try {
                            const fetchUrl = nextPageUrl + (nextPageUrl.includes('?') ? '&' : '?') + 'ajax=1';

                            const response = await fetch(fetchUrl, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            });


                            const data = await response.json();

                            if (data.html) {
                                gridContainer.insertAdjacentHTML('beforeend', data.html);
                            }

                            nextPageUrl = data.next_page_url;

                            if (!nextPageUrl) {
                                console.log("12. No more pages left. Stopping observer.");
                                sentinel.classList.add('hidden');
                                endMessage.classList.remove('hidden');
                                observer.unobserve(sentinel);
                            }
                        } catch (error) {
                            console.error('13. ERROR during fetch:', error);
                        } finally {
                            isLoading = false;
                        }
                    }
                }, {
                    rootMargin: '100px'
                });

                observer.observe(sentinel);
            } else { }
        });
    </script>

@endsection
@push('scripts')
    @include('frontend.partials.project_scripts')
@endpush
