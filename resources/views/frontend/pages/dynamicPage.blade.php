@extends('layouts.front')

@section('meta')
@include('frontend.partials.meta', [
    'pageKey' => 'page',
    'title' => $page->meta_title ?? $page->title,
    'description' => $page->meta_description ?? $page->title,
    'keywords' => $page->meta_keywords,
])
@endsection
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
@section('content')
<section class="py-20 mt-10">
    <div class="container mx-auto px-6">
        
        <!-- Page Title -->
        <h1 class="text-4xl font-bold mb-8 text-gray-900">{{ $page->title }}</h1>

        <div class="flex flex-col gap-4">
            
            <!-- Description 1 -->
            <div class="blog-content prose max-w-none text-gray-700 text-base leading-relaxed">
                {!! $page->description !!}
            </div>
            <div class="blog-content prose max-w-none text-gray-700 text-base leading-relaxed">
                {!! $page->description_1 !!}
            </div>


        </div>

    </div>
</section>
@endsection
