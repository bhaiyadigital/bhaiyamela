@if ($paginator->hasPages())
    {{-- মেইন কন্টেইনার (সাদা ব্যাকগ্রাউন্ড ও শ্যাডো সহ) --}}
    <div class="inline-flex items-center bg-white px-8 py-5 rounded-[20px] shadow-[0_10px_30px_rgba(0,0,0,0.05)] border border-gray-50">
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-4">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="flex items-center gap-2 text-gray-300 cursor-not-allowed font-medium text-base">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i> Back
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center gap-2 text-[#2c4294] hover:text-indigo-600 font-medium text-base transition-colors">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i> Back
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="flex items-center gap-2.5 mx-2">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-2 text-gray-300 font-bold tracking-widest">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                {{-- Active Page (ছবির মতো গোল করা চারকোণা বক্স) --}}
                                <span class="w-10 h-11 flex items-center justify-center bg-[#5c5be5] text-white rounded-[12px] text-base font-bold shadow-md">
                                    {{ $page }}
                                </span>
                            @else
                                {{-- Inactive Page (হালকা ল্যাভেন্ডার/ব্লু ব্যাকগ্রাউন্ড) --}}
                                <a href="{{ $url }}" class="w-10 h-11 flex items-center justify-center bg-[#f0f2ff] text-[#2c4294] hover:bg-[#5c5be5] hover:text-white rounded-[12px] text-base font-semibold transition-all duration-300">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center gap-2 text-[#2c4294] hover:text-indigo-600 font-medium text-base transition-colors">
                    Next <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            @else
                <span class="flex items-center gap-2 text-gray-300 cursor-not-allowed font-medium text-base">
                    Next <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </span>
            @endif
        </nav>
    </div>
@endif
