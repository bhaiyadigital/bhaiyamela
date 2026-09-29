@extends('layouts.front')
@section('title', "About Us | Bhaiya Group | Salt Bay Luxury Living ")

@section('meta')
{{-- ================= PAGE META ================= --}}
<meta name="description" content="Learn about Bhaiya Group, a leading Bangladeshi conglomerate with 50+ years of legacy in real estate, hospitality, food & beverage, insurance, and allied sectors. Discover our founder, achievements, and brands.">
<meta name="keywords" content="Bhaiya Group, Salt Bay, Salt Bay Coxbazar, Bhaiya Hotels, Luxury Hospitality Bangladesh, Bangladeshi conglomerate, real estate, Eden Bay, Pine City, Right Aid Hospital">
<meta name="robots" content="index, follow, max-image-preview:large">
<link rel="canonical" href="{{ url()->current() }}">

{{-- ================= OPEN GRAPH ================= --}}
<meta property="og:type" content="website">
<meta property="og:title" content="About Bhaiya Group | Salt Bay Luxury Living">
<meta property="og:description" content="Discover Bhaiya Group’s legacy, founder, brands, and commitment to luxury hospitality and sustainable growth.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:site_name" content="Bhaiya Group">
<meta property="og:image" content="{{ asset('assets/images/og-bhaiya.jpg') }}">
<meta property="og:locale" content="en_US">

{{-- ================= TWITTER ================= --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="About Bhaiya Group | Salt Bay">
<meta name="twitter:description" content="Learn about Bhaiya Group, a leading Bangladeshi conglomerate with 50+ years of legacy in multiple sectors.">
<meta name="twitter:image" content="{{ asset('assets/images/og-bhaiya.jpg') }}">

{{-- ================= BREADCRUMB & ORGANIZATION SCHEMA ================= --}}
@php
$schema = [
    "breadcrumb" => [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => [
            [
                "@type" => "ListItem",
                "position" => 1,
                "name" => "Home",
                "item" => url('/')
            ],
            [
                "@type" => "ListItem",
                "position" => 2,
                "name" => "About Us",
                "item" => url()->current()
            ],
        ],
    ],
    "organization" => [
        "@context" => "https://schema.org",
        "@graph" => [
            // Bhaiya Group
            [
                "@type" => "Organization",
                "name" => "Bhaiya Group",
                "url" => url('/'),
                "logo" => asset('assets/images/logo.png'),
                "sameAs" => [
                    "https://www.facebook.com/profile.php?id=61584236870956",
                    "https://www.linkedin.com/company/bhaiya-group-of-industries"
                ],
                "founder" => [
                    "@type" => "Person",
                    "name" => $founder?->name ?? 'Maksud Ali',
                    "jobTitle" => "Chairman"
                ],
                "foundingDate" => "1972",
                "address" => [
                    "@type" => "PostalAddress",
                    "streetAddress" => "Nabil House, House-09, Road-17, Block-D, Banani",
                    "addressLocality" => "Dhaka",
                    "addressCountry" => "BD"
                ]
            ],

            // Salt Bay Hotel as Project under Bhaiya Group
            [
                "@type" => "Hotel",
                "name" => "Salt Bay",
                "image" => asset('assets/images/og-saltbay.jpg'),
                "description" => "Salt Bay is a five-star luxury hotel by Bhaiya Hotels, a concern of Bhaiya Group, offering premium rooms, suites, and world-class hospitality at Inani Beach, Cox’s Bazar.",
                "address" => [
                    "@type" => "PostalAddress",
                    "streetAddress" => "Marine Drive Road, Inani Beach",
                    "addressLocality" => "Cox's Bazar",
                    "addressCountry" => "BD"
                ],
                "starRating" => [
                    "@type" => "Rating",
                    "ratingValue" => "5"
                ],
                "parentOrganization" => [
                    "@type" => "Organization",
                    "name" => "Bhaiya Hotels",
                    "parentOrganization" => [
                        "@type" => "Organization",
                        "name" => "Bhaiya Group"
                    ]
                ],
                "amenityFeature" => [
                    ["@type"=>"LocationFeatureSpecification","name"=>"Swimming Pool","value"=>true],
                    ["@type"=>"LocationFeatureSpecification","name"=>"Sea View Rooms","value"=>true],
                    ["@type"=>"LocationFeatureSpecification","name"=>"Conference Hall","value"=>true],
                    ["@type"=>"LocationFeatureSpecification","name"=>"Spa & Wellness","value"=>true],
                    ["@type"=>"LocationFeatureSpecification","name"=>"Rooftop Restaurant","value"=>true],
                ]
            ]
        ]
    ]
];
@endphp

{{-- BREADCRUMB SCHEMA --}}
<script type="application/ld+json">
{!! json_encode($schema['breadcrumb'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>

{{-- ORGANIZATION / HOTEL SCHEMA --}}
<script type="application/ld+json">
{!! json_encode($schema['organization'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
@endsection


@section('content')
    {{-- Check if the section is active/exists --}}
    @include('frontend.profile.hero')

    {{-- NEXT SECTION: ABOUT BHAIYA GROUP --}}
    @include('frontend.profile.aboutBhaiya')

    {{-- NEXT SECTION: FOUNDER --}}
    @include('frontend.profile.founder')

    <div class="sticky-card-wrap">
    <!-- Chairman Section -->
        @include('frontend.profile.chairman')
    <!-- Vice Chairman Section -->
        @include('frontend.profile.ceo')
    <!-- Brands Section -->
        @include('frontend.profile.brand')
    </div>

@endsection
