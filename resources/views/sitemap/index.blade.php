<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Static Pages --}}
    @foreach($staticPages as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
    @endforeach

    {{-- Property Categories --}}
    @foreach($categories as $category)
    <url>
        <loc>{{ route('web.project.category', $category->slug) }}</loc>
        <lastmod>{{ optional($category->updated_at)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- Properties / Projects --}}
    @foreach($projects as $project)
    <url>
        <loc>{{ route('project.details', $project->slug) }}</loc>
        <lastmod>{{ optional($project->updated_at)->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    {{-- Blogs --}}
    @foreach($blogs as $blog)
    <url>
        <loc>{{ route('web.blog.details', $blog->slug) }}</loc>
        <lastmod>{{ optional($blog->updated_at)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Area Guides --}}
    @foreach($areaGuides as $guide)
    <url>
        <loc>{{ route('area-guides.show', $guide->slug) }}</loc>
        <lastmod>{{ optional($guide->updated_at)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Dynamic Campaigns --}}
    @foreach($campaigns as $camp)
    <url>
        <loc>{{ route('campaign.details', $camp->slug) }}</loc>
        <lastmod>{{ optional($camp->updated_at)->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- Custom Dynamic Pages --}}
    @foreach($pages as $p)
    <url>
        <loc>{{ route('web.page', $p->slug) }}</loc>
        <lastmod>{{ optional($p->updated_at)->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    @endforeach

</urlset>
