@extends('layouts.front')
@section('meta')
    @include('frontend.partials.meta', ['pageKey' => 'blog'])
@endsection
@section('content')
    <section class="bg-[#f8faff] py-16 md:py-24">
        <div class="container mx-auto px-6 md:px-10">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-8 mb-16">
                <div class="max-w-xl">
                    <h2 class="text-xl md:text-[32px] font-semibold text-gray-900 leading-tight">
                        Our Blog
                    </h2>
                </div>
            </div>

            <!-- Blog Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
               @forelse($blogs as $blog)
                <div class="swiper-slide h-auto">
                    <a href="{{ route('web.blog.details', $blog->slug) }}" aria-label="View blog post"
                        class="bg-white h-full flex flex-col rounded-xl overflow-hidden shadow-[0_15px_40px_-15px_rgba(0,0,0,0.05)] border border-gray-100 group transition-all duration-300 hover:shadow-2xl">

                        <!-- Image Area -->
                        <div class="relative aspect-[16/10] overflow-hidden">
                            <img src="{{ asset('storage/'.$blog->img_path ?? '') }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                alt="{{ $blog->title ?? '' }}" />
                        </div>

                        <!-- Card Content Body -->
                        <div class="p-8 pb-6">
                            <div class="flex justify-between items-center mb-6">
                                <span
                                    class="bg-[#f3f4f6] text-gray-700 px-4 py-1.5 rounded-lg md:rounded-full text-base font-medium">
                                    {{ $blog->project->title ?? 'Bhaiya Group' }}
                                </span>
                                <div
                                    class="flex items-center gap-2 text-gray-700 text-xs md:text-base font-semibold">
                                    <i class="fa-regular fa-clock text-xs md:text-base"></i>
                                    <span>
                                        @php
                                        $wordCount = str_word_count(strip_tags($blog->body));
                                        $minutes = ceil($wordCount / 200);
                                        @endphp
                                        {{ $minutes == 0 ? 1 : $minutes }} min read
                                    </span>
                                </div>
                            </div>

                            <h3
                                class="text-xl font-medium text-gray-900 mb-6 leading-tight group-hover:text-[#2c4294] transition line-clamp-2">
                                {{ $blog->title ?? '' }}
                            </h3>

                            <span
                                class="inline-flex items-center gap-2 text-[#2c4294] font-bold text-base md:text-base">
                                Explore Article
                                <i
                                    class="fa-solid fa-arrow-right text-[12px] group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </div>

                        <!-- Card Footer -->
                        <div
                            class="px-5 md:px-8 py-4 md:py-6 border-t border-gray-100 flex items-center justify-between gap-3 mt-auto">
                            <div class="flex items-center gap-2 md:gap-3 overflow-hidden">
                                <img src="{{ asset($blog->company?->company_logo ? 'storage/'.$blog->company->company_logo : 'assets/images/logo.png') }}"
                                    class="w-8 h-8 md:w-12 md:h-12 rounded-full object-cover shadow-sm"
                                    alt="Author" />
                                <div class="leading-tight overflow-hidden">
                                    <h4 class="text-[11px] md:text-base font-bold text-gray-900 truncate">
                                        {{ $blog->user->name ?? 'Admin' }}
                                    </h4>
                                    <p class="text-[9px] md:text-base font-medium text-gray-500 truncate">
                                        {{ $blog->company->company_name ?? 'Bhaiya Group' }}
                                    </p>
                                </div>
                            </div>
                             @if($blog->start_date)
                            <div class="flex items-center gap-1.5 text-gray-500 text-[10px] md:text-base font-semibold whitespace-nowrap">
                                <!-- FontAwesome Calendar Icon -->
                                <i class="fa-regular fa-calendar-days text-[10px] md:text-base text-gray-400"></i>
                                <span>{{ $blog->start_date->format('M d, Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </a>
                </div>
                @empty
                <p class="col-span-full text-center text-gray-400">No blog posts found.</p>
                @endforelse
            </div>

            <div class="mt-16 flex justify-center">
                {{ $blogs->links('frontend.partials.custom_pagination') }}
            </div>

        </div>
    </section>
    @php
$page = $allMeta->get('blog');
@endphp
@if($page)
@include('frontend.partials.page_descriptions', ['model' => $page])
@endif
@endsection
