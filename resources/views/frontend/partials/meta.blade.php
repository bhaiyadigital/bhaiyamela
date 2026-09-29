@props([
'title' => null,
'description' => null,
'keywords' => null,
'image' => null,
'pageKey' => 'home',
])

@php
$pageMeta = $allMeta[$pageKey] ?? null;

$displayTitle = $title ?? ($pageMeta->meta_title ?? ($globalMeta->meta_title ?? $setting->title));

$displayDesc = strip_tags($description ?? ($pageMeta->meta_description ?? ($globalMeta->meta_description ?? 'Building dreams since 1972.')));
$displayKeywords = $keywords ?? ($pageMeta->meta_keywords ?? ($globalMeta->meta_keywords ?? ''));

$shareImage = $image ?? (isset($setting->img_path) ? asset('storage/'.$setting->img_path) : asset('assets/images/logo.png'));
$socialUrls = isset($socials) ? $socials->pluck('url')->toArray() : [];
$currentUrl = url()->current();

$breadcrumbItems = [
['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')]
];
if ($pageKey !== 'home') {
$breadcrumbItems[] = ['@type' => 'ListItem', 'position' => 2, 'name' => $displayTitle, 'item' => $currentUrl];
}

$schema = [
'page' => [
'title' => $displayTitle,
'description' => $displayDesc,
'keywords' => $displayKeywords,
'canonical' => $currentUrl,
],
'openGraph' => [
'type' => 'website',
'title' => $displayTitle,
'description' => $displayDesc,
'url' => $currentUrl,
'site_name' => $setting->title ?? 'Bhaiya Housing',
'image' => $shareImage,
'locale' => 'en_US',
],
'twitter' => [
'card' => 'summary_large_image',
'title' => $displayTitle,
'description' => $displayDesc,
'image' => $shareImage,
],
'breadcrumb' => [
'@context' => 'https://schema.org',
'@type' => 'BreadcrumbList',
'itemListElement' => $breadcrumbItems,
],
'organization' => [
'@context' => 'https://schema.org',
'@graph' => [
[
'@type' => ['Organization', 'RealEstateBuilder'],
'name' => $setting->title ?? 'Bhaiya Housing Ltd.',
'url' => url('/'),
'logo' => !empty($setting->img_path)
? asset('storage/' . $setting->img_path)
: asset('assets/images/logo.png'), 'sameAs' => $socialUrls,
'founder' => [
'@type' => 'Person',
'name' => 'Maroof Sattar Ali',
'jobTitle' => 'Chairman',
],
'parentOrganization' => ['@type' => 'Organization', 'name' => 'Bhaiya Group'],
'foundingDate' => '1972',
'address' => [
'@type' => 'PostalAddress',
'streetAddress' => strip_tags($setting->body ?? 'Century Trade Center, Banani'),
'addressLocality' => 'Dhaka',
'addressCountry' => 'BD',
],
'contactPoint' => [
'@type' => 'ContactPoint',
'telephone' => $setting->extra ?? '',
'contactType' => 'customer service',
],
],
],
],
];
@endphp

{{-- HTML Meta Tags --}}
<title>{{ $displayTitle }}</title>
<meta name="description" content="{{ Str::limit($displayDesc, 160) }}">
<meta name="keywords" content="{{ $displayKeywords }}">
<link rel="canonical" href="{{ $schema['page']['canonical'] }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $displayTitle }}">
<meta property="og:description" content="{{ Str::limit($displayDesc, 160) }}">
<meta property="og:url" content="{{ $schema['openGraph']['url'] }}">
<meta property="og:site_name" content="{{ $schema['openGraph']['site_name'] }}">
<meta property="og:image" content="{{ $schema['openGraph']['image'] }}">

{{-- Twitter --}}
<meta name="twitter:card" content="{{ $schema['twitter']['card'] }}">
<meta name="twitter:title" content="{{ $schema['twitter']['title'] }}">
<meta name="twitter:description" content="{{ Str::limit($displayDesc, 160) }}">
<meta name="twitter:image" content="{{ $schema['twitter']['image'] }}">

{{-- JSON-LD Scripts --}}
<script type="application/ld+json">
    {
        !!json_encode($schema['breadcrumb'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!
    }
</script>
<script type="application/ld+json">
    {
        !!json_encode($schema['organization'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!
    }
</script>