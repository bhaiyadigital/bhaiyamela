@php
$firstImage = $project->img_path ?? 'assets/images/placeholder.webp';

$features = $project->features ?? [];
$displayFeatures = [];
if (!empty($features)) {
$count = 0;
foreach ($features as $key => $val) {
if ($count >= 3) break;

if (is_array($val) && isset($val['key'])) {
$displayFeatures[] = ['key' => $val['key'], 'val' => $val['value'] ?? ''];
} elseif (is_numeric($key) && !is_array($val)) {
$displayFeatures[] = ['key' => $val, 'val' => ''];
} else {
$displayFeatures[] = ['key' => $key, 'val' => $val];
}
$count++;
}
}

$isFavorite = false;
if (auth()->check()) {
$isFavorite = \App\Models\Favorite::where('user_id', auth()->id())
->where('content_id', $project->id)
->exists();
}
@endphp

<div class="project-card cursor-pointer bg-white h-full flex flex-col rounded-xl overflow-hidden shadow-[0_1px_1px_rgba(0,0,0,0.1)] border border-gray-100 group transition-all duration-300 hover:shadow-xl relative"
    data-section="{{ $section ?? 'grid' }}"
    data-category="{{ $project->parent_id }}"
    onclick="if(!event.target.closest('button')) window.location.href='{{ route('project.details', $project->slug) }}'">
    <div class="relative aspect-[16/11] overflow-hidden">
        <img src="{{ asset('storage/' . $firstImage) }}"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            alt="{{ $project->title }}" />

        <!-- Price / Call for Price Ribbon -->
        <div class="absolute left-[-4px] bottom-6 z-10">
            <div class="bg-[#1b6e35] text-white text-[11px] font-extrabold uppercase px-4 py-2 rounded-r-lg shadow-md relative tracking-wider">
                {{ $project->short ?? 'Call For Price'}}
                <div class="absolute left-0 bottom-[-4px] w-0 h-0 border-t-[4px] border-t-[#1c6e35] border-l-[4px] border-l-transparent"></div>
            </div>
        </div>

        <!-- Floating Favorite/Bookmark Button (Top Left) -->
        <button type="button"
            class="btn-favorite-toggle absolute top-3 left-3 z-30 bg-white/90 hover:bg-white text-gray-700 w-8 h-8 rounded-full flex items-center justify-center transition-all shadow-md focus:outline-none"
            data-id="{{ $project->id }}"
            title="{{ $isFavorite ? 'Remove from Favorites' : 'Add to Favorites' }}">
            <i class="{{ $isFavorite ? 'fa-solid fa-heart text-red-500' : 'fa-regular fa-heart text-gray-700' }} text-xs pointer-events-none"></i>
        </button>

        <!-- Floating Compare Button (Top Right) -->
<!-- Updated Compare Toggle Button with realistic real-estate comparison icon (fa-arrow-right-arrow-left) -->
        <button type="button"
            class="btn-compare-toggle absolute top-3 right-3 z-30 bg-white/90 hover:bg-white text-gray-700 hover:text-[#2ba351] w-8 h-8 rounded-full flex items-center justify-center transition-all shadow-md focus:outline-none"
            data-id="{{ $project->id }}"
            data-title="{{ $project->title }}"
            data-image="{{ asset('storage/' . $firstImage) }}"
            title="Add to Compare">
            <!-- Realistic real-estate/property comparison icon representing side-by-side swap/match -->
            <i class="fa-solid fa-arrow-right-arrow-left text-xs pointer-events-none"></i>
        </button>
    </div>

    <!-- Content Area -->
    <div class="p-6 md:p-8 flex-grow flex flex-col">
        <!-- Title & SALE Badge (Cleaned up: Removed nested <a> tags to pass HTML5 validation) -->
         <span class="inline-block bg-[#1b6e35] text-white text-[10px] font-bold px-3 py-1 rounded mb-4 uppercase tracking-wider w-fit">
            {{ $project->category->title ?? 'Apartments' }}
        </span>
        <div class="flex items-center gap-2 mb-3">
            <h3 class="text-base md:text-base font-bold text-gray-900 leading-tight line-clamp-1 group-hover:text-[#224194] transition-colors">
                {{ $project->title }}
            </h3>
     
        </div>

        <!-- Category Tag -->
       

        <!-- Location -->
        <div class="flex items-center gap-1.5 text-gray-600 text-base mb-5">
            <i class="fa-solid fa-location-dot"></i>
            <span class="line-clamp-1">{{ $project->destination->title ?? 'Dhaka' }}</span>
        </div>

        <!-- Specs Grid -->
        @if(!empty($displayFeatures))
        <div class="grid grid-cols-3 gap-2 border-t border-gray-100 pt-4 mb-5">
            @foreach($displayFeatures as $feature)
            <div>
                <span class="block text-xs font-semibold text-[#1b6e35] mb-1 line-clamp-1" title="{{ $feature['key'] }}">
                    {{ $feature['key'] }}
                </span>
                <span class="block text-base md:text-lg font-bold text-gray-800 leading-none truncate" title="{{ $feature['val'] }}">
                    {{ $feature['val'] }}
                </span>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Developer Company Footer -->
        <!-- @if($project->company)
        <div class="border-t border-gray-100 pt-4 mt-auto flex items-center gap-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200 bg-gray-50 flex-shrink-0">
                <img src="{{ $project->company->company_logo ? asset('storage/' . $project->company->company_logo) : asset('assets/images/placeholder.jpg') }}"
                    class="w-full h-full object-cover"
                    alt="{{ $project->company->company_name }}">
            </div>
            <div class="flex flex-col min-w-0">
                <span class="text-base font-bold text-[#1b6e35] leading-tight truncate">
                    {{ $project->company->company_name }}
                </span>
                <span class="text-[11px] text-gray-600 mt-0.5 truncate">
                    {{ $project->company->industry ?? 'Real Estate Company' }}
                </span>
            </div>
        </div>
        @else <div class="border-t border-gray-100 pt-4 mt-auto flex items-center gap-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200 bg-gray-50 flex-shrink-0">
                <img src="{{ $setting ? asset('storage/' . $setting->logo) : asset('assets/images/placeholder.jpg') }}"
                    class="w-full h-full object-cover"
                    alt="{{ $setting ? $setting->site_name : 'Bhaiya Group' }}">
            </div>
            <div class="flex flex-col min-w-0">
                <span class="text-base font-bold text-[#1b6e35] leading-tight truncate">
                    {{ $setting ? $setting->site_name : 'Bhaiya Group' }}
                </span>
                <span class="text-[11px] text-gray-600 mt-0.5 truncate">
                    {{ $setting ? $setting->site_name : 'Real Estate Company' }}
                </span>
            </div>
        </div>
        @endif -->
    </div>

</div>