<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Setting;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap XML.
     */
    public function index(): Response
    {
        // 1. Static Pages
        $staticPages = [
            ['loc' => url('/'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('web.project'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('developers.index'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('web.blog'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('web.gallery'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('faq.index'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('loans.index'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('area-guides.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('about.index'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('web.contact'), 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => route('projects.compare'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('requirements.create'), 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        // 2. Dynamic Categories
        $categories = Content::withoutGlobalScopes()
            ->where('module', 'category')
            ->where('status', Content::STATUS_ACTIVE)
            ->select('slug', 'updated_at')
            ->get();

        // 3. Dynamic Projects
        $projects = Content::withoutGlobalScopes()
            ->where('module', 'project')
            ->where('status', Content::STATUS_ACTIVE)
            ->where('admin_approved', 1)
            ->select('slug', 'updated_at')
            ->get();

        // 4. Dynamic Blogs
        $blogs = Content::withoutGlobalScopes()
            ->where('module', 'blog')
            ->where('status', Content::STATUS_ACTIVE)
            ->select('slug', 'updated_at')
            ->get();

        // 5. Dynamic Area Guides
        $areaGuides = Content::withoutGlobalScopes()
            ->where('module', 'area_guide')
            ->where('status', Content::STATUS_ACTIVE)
            ->select('slug', 'updated_at')
            ->get();

        // 6. Dynamic Campaigns
        $campaigns = Content::withoutGlobalScopes()
            ->where('module', 'campaign')
            ->where('status', Content::STATUS_ACTIVE)
            ->select('slug', 'updated_at')
            ->get();

        // 7. Dynamic Custom Pages
        $pages = Content::withoutGlobalScopes()
            ->where('module', 'page')
            ->where('status', Content::STATUS_ACTIVE)
            ->select('slug', 'updated_at')
            ->get();

        $content = view('sitemap.index', compact(
            'staticPages',
            'categories',
            'projects',
            'blogs',
            'areaGuides',
            'campaigns',
            'pages'
        ))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate dynamic robots.txt.
     */
    public function robots(): Response
    {
        $setting = Setting::instance();
        $isNoIndex = ($setting->meta_index === 'noindex');

        if ($isNoIndex) {
            $robots = "User-agent: *\nDisallow: /\n";
        } else {
            $sitemapUrl = url('/sitemap.xml');
            $robots = "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /dashboard/\nDisallow: /company-profile/\nSitemap: {$sitemapUrl}\n";
        }

        return response($robots, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * Generate llms.txt guide for AI agents.
     */
    public function llms(): Response
    {
        $setting = Setting::instance();
        $siteName = $setting->site_name ?? config('app.name', 'Housing Abason');
        $siteSlogan = $setting->site_slogan ?? 'Find verified properties, apartments, and land projects in Bangladesh.';
        $url = url('/');

        $content = "Title: {$siteName}\n";
        $content .= "Description: {$siteSlogan}\n";
        $content .= "---\n\n";
        $content .= "# {$siteName}\n\n";
        $content .= "This file provides guidance for Large Language Models (LLMs) and AI crawlers.\n\n";
        $content .= "## Site Information\n";
        $content .= "- **Base URL:** [{$url}]({$url})\n";
        $content .= "- **Sitemap:** [Sitemap.xml]({$url}/sitemap.xml)\n";
        $content .= "- **Meta Title:** {$siteName}\n";
        $content .= "- **Meta Description:** {$siteSlogan}\n\n";

        $content .= "## Key Directories\n";
        $content .= "- [Home]({$url})\n";
        $content .= "- [Properties]({$url}/properties)\n";
        $content .= "- [Developers]({$url}/developers)\n";
        $content .= "- [Blog]({$url}/blog)\n";
        $content .= "- [Area Guides]({$url}/area-guides)\n";
        $content .= "- [Gallery]({$url}/gallery)\n";
        $content .= "- [FAQ]({$url}/faq)\n";
        $content .= "- [Home Loans]({$url}/loan-partners)\n";
        $content .= "- [Property Compare]({$url}/compare)\n";
        $content .= "- [Post Requirement]({$url}/post-requirement)\n";
        $content .= "- [About Us]({$url}/about)\n";
        $content .= "- [Contact]({$url}/contact)\n\n";

        // Dynamic Categories
        $categories = Content::withoutGlobalScopes()
            ->where('module', 'category')
            ->where('status', Content::STATUS_ACTIVE)
            ->select('title', 'slug')
            ->get();

        if ($categories->isNotEmpty()) {
            $content .= "## Property Categories\n";
            foreach ($categories as $cat) {
                $categoryUrl = route('web.project.category', $cat->slug);
                $content .= "- [{$cat->title}]({$categoryUrl})\n";
            }
            $content .= "\n";
        }

        // Dynamic Projects
        $projects = Content::withoutGlobalScopes()
            ->where('module', 'project')
            ->where('status', Content::STATUS_ACTIVE)
            ->where('admin_approved', 1)
            ->select('title', 'slug')
            ->get();

        if ($projects->isNotEmpty()) {
            $content .= "## Featured Projects & Properties\n";
            foreach ($projects as $proj) {
                $projUrl = route('project.details', $proj->slug);
                $content .= "- [{$proj->title}]({$projUrl})\n";
            }
            $content .= "\n";
        }

        // Dynamic Campaigns
        $campaigns = Content::withoutGlobalScopes()
            ->where('module', 'campaign')
            ->where('status', Content::STATUS_ACTIVE)
            ->select('title', 'slug')
            ->get();

        if ($campaigns->isNotEmpty()) {
            $content .= "## Special Campaigns & Offers\n";
            foreach ($campaigns as $camp) {
                $campUrl = route('campaign.details', $camp->slug);
                $content .= "- [{$camp->title}]({$campUrl})\n";
            }
            $content .= "\n";
        }

        $content .= "## Guidelines\n";
        $content .= "- Crawlers must respect the instructions in [robots.txt]({$url}/robots.txt).\n";
        $content .= "- Avoid indexing private user dashboards or admin panels.\n";
        $content .= "- Attribution is required for content used in training sets.\n";

        return response($content, 200)
            ->header('Content-Type', 'text/markdown');
    }
}