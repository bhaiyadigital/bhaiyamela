@extends('layouts.front')
@section('meta')
@include('frontend.partials.meta', [
'pageKey' => 'blog',
'title' => $blog->meta_title ?? $blog->title,
'description' => $blog->meta_description ?? $blog->short,
'keywords' => $blog->meta_keywords,
'image' => asset($blog->img_path)
])
@endsection
@section('content')
<style>
    table {
        width: 100%;
    }

    table th,
    table td {
        border: 1px solid black !important;
        padding: 3px 5px;
    }

    ul {
        list-style: disc;
        padding-left: 20px;
    }

    ol {
        list-style: decimal;
        padding-left: 20px;
    }

    .blog-content h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 16px;
    }

    .blog-content h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .blog-content h3 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .blog-content h4 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .blog-content h5 {
        font-size: 18px;
        font-weight: 600;
    }

    .blog-content h6 {
        font-size: 16px;
        font-weight: 600;
    }

    .blog-content p {
        margin-bottom: 16px;
        line-height: 1.8;
    }

    .blog-content ul {
        list-style: disc;
        padding-left: 24px;
        margin-bottom: 16px;
    }

    .blog-content ol {
        list-style: decimal;
        padding-left: 24px;
        margin-bottom: 16px;
    }

    .blog-content li {
        margin-bottom: 0;
    }

    .blog-content strong {
        font-weight: 700;
    }

    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 16px 0;
    }

    .blog-content h1,
    .blog-content h2,
    .blog-content h3,
    .blog-content h4,
    .blog-content h5 {
        margin: 0;
        margin-top: 10px;
    }

    p {
        margin: 0;
    }

    .blog-content * {
        font-family: "Trebuchet MS", "sans-serif" !important;
        color: black;
    }

    .blog-content h2,
    .blog-content h3 {
        font-weight: 400;
        font-size: 20px;
        line-height: 26px;
        margin-bottom: 5px;
    }

    .blog-content p {
        font-size: 15px;
        line-height: 23px;
        text-align: justify;
        color: #333333fa;
    }

    .hr {
        height: 1px;
        background: gainsboro;
        margin-top: 30px;
    }
</style>
<section  style="border: 1px solid gainsboro; margin-bottom: 20px;">
    <div class="container mx-auto px-6 md:px-10">
        <!-- Image Overlap using Negative Margin (Added items-center and min-w-0) -->
        <div class="flex items-center gap-2 text-base px-4 py-2 border-gray-100 min-w-0">
            <!-- Added flex-shrink-0 -->
            <a href="{{ url('/') }}" class="text-gray-600 hover:text-black flex-shrink-0">
                Home
            </a>

            <!-- Added flex-shrink-0 -->
            <span class="text-gray-300 flex-shrink-0">›</span>

            <!-- Added flex-shrink-0 -->
            <a href="{{ route('web.blog') ?? '#' }}" class="text-gray-600 hover:text-black flex-shrink-0">
                Blog
            </a>

            <!-- Added flex-shrink-0 -->
            <span class="text-gray-300 flex-shrink-0">›</span>

            <!-- IMPROVED: Added truncate, min-w-0, and title attribute -->
            <span class="text-black font-semibold truncate min-w-0" title="{{ $blog->title }}">
                {{ $blog->title }}
            </span>

        </div>
    </div>
</section>

<!-- ২. Main Content Section -->
<section class="bg-white   pb-20">
    <div class="container mx-auto px-6 md:px-10">

        <!-- Meta Bar: Category + Date + Read Time -->


        <!-- Grid Layout (Social | Content | Related) -->
        <div class="grid grid-cols-12 gap-8 lg:gap-16">

            <!-- Left Sidebar: Social Icons (Sticky) -->
            <div class="hidden lg:flex lg:col-span-1 justify-center">
                <div class="sticky top-32 flex flex-col gap-6 h-fit pt-2">
                    @foreach ($socials as $social)
                    <a href="{{ $social->url }}" target="_blank" aria-label="Visit our social media profile"
                        class="w-9 h-9 rounded-full bg-[#4D6356] flex items-center justify-center hover:bg-[#2c4294] transition-all shadow-sm text-white text-base">
                        <i class="{{ $social->title }}"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Middle: Article Body (Col 7) -->
            <div class="col-span-12 lg:col-span-7">
                <div class="flex flex-wrap justify-between items-center pb-6  gap-4">
                    <span
                        class="bg-[#1A1A1A] text-white px-5 py-2 text-base font-bold uppercase tracking-[0.2em] rounded-sm">
                        {{ $blog->project->title ?? 'Blog' }}
                    </span>
                    <div class="text-gray-500 text-base font-bold uppercase tracking-widest flex items-center">
                        {{ $blog->start_date ? \Carbon\Carbon::parse($blog->start_date)->format('F d, Y') : $blog->created_at->format('F d, Y') }}

                    </div>
                </div>
                <img src="{{ asset('storage/'.$blog->img_path ?? '') }}" class="w-full " alt="{{ $blog->title }}" />
                <h1 class="text-xl md:text-[30px] font-bold text-gray-900 leading-[1.1] mb-3 mt-4">
                    {{ $blog->title }}
                </h1>

                <!-- WYSIWYG Content with Tailwind Arbitrary Variants for List Icons -->
                <div
                    class="text-gray-700 leading-[1.9] text-base space-y-8 blog-content
                                [&_ul]:list-none [&_ul]:pl-0 [&_ul]:my-8
                                [&_li]:relative [&_li]:pl-10 [&_li]:mb-4 [&_li]:font-medium
                                [&_li::before]:content-['\f058'] [&_li::before]:font-['Font_Awesome_6_Free'] [&_li::before]:font-black
                                [&_li::before]:absolute [&_li::before]:left-0 [&_li::before]:text-black [&_li::before]:text-xl
                                [&_p]:mb-6 [&_img]:rounded-md [&_img]:shadow-sm">

                    <div>{!! $blog->description !!}</div>
                    @if (!empty(strip_tags($blog->description_1)))
                    <div>{!! $blog->description_1 !!}</div>
                    <div class="hr"></div>
                    @endif
                    @if (!empty(strip_tags($blog->description_2)))
                    <div>{!! $blog->description_2 !!}</div>
                    <div class="hr"></div>
                    @endif
                    @if (!empty(strip_tags($blog->description_3)))
                    <div>{!! $blog->description_3 !!}</div>
                    @endif
                </div>
            </div>

            <!-- Right Sidebar: Related Posts (Sticky) -->
             @if($relatedBlogs->count() > 1)

            <div class="col-span-12 lg:col-span-4">
                <div class="sticky top-32 lg:pl-4 h-fit">
                    <h2
                        class="text-base font-bold text-gray-900 mb-8 uppercase tracking-[0.2em] border-l-4 border-[#5B5F4C] pl-4">
                        Related Blog
                    </h2>
                    <div class="flex flex-col gap-10">
                        @foreach ($relatedBlogs as $rb)
                        <a href="{{ route('web.blog.details', $rb->slug) }}" class="flex items-start gap-4 group"
                            aria-label="Read related blog post">
                            <div class="w-28 h-28 flex-shrink-0 overflow-hidden bg-gray-100 rounded-sm">
                                <img src="{{ asset('storage/'.$rb->img_path ?? '') }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                    alt="{{ $rb->title }}" />
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-widest mb-1">
                                    {{ $rb->start_date ? \Carbon\Carbon::parse($rb->start_date)->format('M d, Y') : $rb->created_at->format('M d, Y') }}
                                </span>
                                <h3
                                    class="text-gray-900 font-bold leading-snug text-base transition-colors group-hover:text-[#5B5F4C]">
                                    {{ Str::limit($rb->title, 55) }}
                                </h3>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
             @endif
        </div>
    </div>
</section>
@endsection