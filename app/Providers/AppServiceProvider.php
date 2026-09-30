<?php

namespace App\Providers;

use App\Models\Content;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        $modules = [

            // ------------------------------------------------------------------
            // Hero / Banner — homepage or section hero banners
            // ------------------------------------------------------------------
            'hero' => [
                'module_name' => ['label' => 'Hero', 'icon' => 'bi bi-image'],
                'title'            => ['label' => 'Title',                'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'img_path'         => ['label' => 'Slider Image',     'required' => false, 'show_in_table' => false, 'type' => 'image'],
                'status'           => ['label' => 'Status',               'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],
            'login-benefits' => [
                'module_name' => ['label' => 'Login Benefits', 'icon' => 'bi bi-tag'],
                'title'            => ['label' => 'Benefits Text',                'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'status'           => ['label' => 'Status',               'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],
            'helpline' => [
                'module_name' => ['label' => 'Helpline', 'icon' => 'bi bi-telephone'],

                'title'            => ['label' => 'Title',                'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'short'            => ['label' => 'Sub Heading',                'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'location'            => ['label' => 'Helpline Number',                'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'description'            => ['label' => 'Opening Day',                'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'description_1'            => ['label' => 'Opening Hour',                'required' => true,  'show_in_table' => true,  'type' => 'text'],

                'status'           => ['label' => 'Status',               'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],

            // ------------------------------------------------------------------
            // Destination — travel destinations with tabs
            // ------------------------------------------------------------------
            'destination' => [
                'module_name' => ['label' => 'Destinations', 'icon' => 'bi bi-geo-alt'],
                'short'            => ['label' => 'City',    'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'description'            => ['label' => 'Sub  area',    'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'title'            => ['label' => 'Title',            'required' => true,  'show_in_table' => true,  'type' => 'text'],

                'url'              => ['label' => 'Link URL',             'required' => false, 'show_in_table' => false, 'type' => 'url'],
                'meta_title'       => ['label' => 'Meta Title',           'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'meta_description' => ['label' => 'Meta Description',     'required' => false, 'show_in_table' => false, 'type' => 'textarea'],
                'meta_keywords'    => ['label' => 'Meta Keywords',        'required' => false, 'show_in_table' => false, 'type' => 'tag'],
                'sort_order'       => ['label' => 'Sort Order',           'required' => false, 'show_in_table' => true,  'type' => 'number'],
                'status'           => ['label' => 'Status',               'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],

            // ------------------------------------------------------------------
            // Category — hero categories / property types (apartments, villas, etc.)
            // ------------------------------------------------------------------
            'category' => [
                'module_name' => ['label' => 'Categories', 'icon' => 'bi bi-grid'],
                'title'            => ['label' => 'Category Name',        'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'slug'             => ['label' => 'Slug',                 'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'prev_slug'        => ['label' => 'Previous Slug',        'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'description'      => ['label' => 'Description',          'required' => false, 'show_in_table' => false, 'type' => 'editor'],
                'img_path'         => ['label' => 'Category Icon',        'required' => true,  'show_in_table' => true,  'type' => 'image'],
                'meta_title'       => ['label' => 'Meta Title',           'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'meta_description' => ['label' => 'Meta Description',     'required' => false, 'show_in_table' => false, 'type' => 'textarea'],
                'meta_keywords'    => ['label' => 'Meta Keywords',        'required' => false, 'show_in_table' => false, 'type' => 'tag'],
                'sort_order'       => ['label' => 'Sort Order',           'required' => false, 'show_in_table' => true,  'type' => 'number'],
                'status'           => ['label' => 'Status',               'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],



            // ------------------------------------------------------------------
            // Project — main project or property listing
            // ------------------------------------------------------------------
            'project' => [
                'module_name'      => ['label' => 'Projects', 'icon' => 'bi bi-building'],
                'title'            => ['label' => 'Title',         'required' => true,  'show_in_table' => true,  'type' => 'text'],

                'parent_id'        => ['label' => 'Select Category',      'required' => true,  'show_in_table' => false, 'type' => 'select'],
                'destination_id'        => ['label' => 'Select Destination',      'required' => true,  'show_in_table' => false, 'type' => 'select'],
                'slug'             => ['label' => 'Slug',                 'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'prev_slug'        => ['label' => 'Previous Slug',        'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'short'            => ['label' => 'Price',    'required' => false,  'show_in_table' => false, 'type' => 'text'],
                'description'      => ['label' => 'Project Description',        'required' => false, 'show_in_table' => false, 'type' => 'editor'],

                'description_3'    => ['label' => 'Project Description 2',        'required' => false, 'show_in_table' => false, 'type' => 'editor'],
                'features'         => [
                    'label' => 'Property  Overview',
                    'required' => false,
                    'show_in_table' => false,
                    'type' => 'features',
                    'keys' => ['Bedrooms', 'Baths', 'Size (Sq.ft)', 'Garage', 'Facing']
                ],
                'extra'            => ['label' => 'Property Features',         'required' => false,  'show_in_table' => false,  'type' => 'extra'],
                'img_path'         => ['label' => 'Thumbnail',          'required' => false, 'show_in_table' => true, 'type' => 'image'],
                'img_paths'        => ['label' => 'Property Images',       'required' => true,  'show_in_table' => false,  'type' => 'image_multiple'],
                'description_1'    => ['label' => 'Floor Plan',        'required' => false, 'show_in_table' => false, 'type' => 'image_multiple'],
                'description_2'    => ['label' => 'Location View',        'required' => false, 'show_in_table' => false, 'type' => 'image_multiple'],
                'video_path'       => ['label' => 'Property Video',        'required' => false, 'show_in_table' => false, 'type' => 'video'],
                // 'virtual_tour' =>     ['label' => '360° Panorama Image (Virtual Tour)', 'required' => false, 'show_in_table' => false, 'type' => 'image'],
                'url'              => ['label' => 'Location Maps(Embed Url)',        'required' => false, 'show_in_table' => false, 'type' => 'url'],
                'location'         => ['label' => 'Location',             'required' => false, 'show_in_table' => true,  'type' => 'text'],
                'start_date'       => ['label' => 'Publish Date',         'required' => false, 'show_in_table' => true,  'type' => 'datetime'],
                'meta_title'       => ['label' => 'Meta Title',           'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'meta_description' => ['label' => 'Meta Description',     'required' => false, 'show_in_table' => false, 'type' => 'textarea'],
                'meta_keywords'    => ['label' => 'Meta Keywords',        'required' => false, 'show_in_table' => false, 'type' => 'tag'],
                'sort_order'       => ['label' => 'Sort Order',           'required' => false, 'show_in_table' => false,  'type' => 'number'],
                'status'           => ['label' => 'Status',               'required' => true,  'show_in_table' => true,  'type' => 'select'],
                'admin_approved'   => ['label' => 'Approve Status',       'required' => false,  'show_in_table' => true,  'type' => 'number'],
            ],


            // ------------------------------------------------------------------
            // Blog — blog posts with rich text and SEO
            // ------------------------------------------------------------------
            'blog' => [
                'module_name'      => ['label' => 'Blogs', 'icon' => 'bi bi-pencil-square'],
                'parent_id'        => ['label' => 'Select Category',      'required' => true,  'show_in_table' => false, 'type' => 'select'],
                'title'            => ['label' => 'Title',                'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'slug'             => ['label' => 'Slug',                 'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'prev_slug'        => ['label' => 'Previous Slug',        'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'short'            => ['label' => 'Short Description',    'required' => true,  'show_in_table' => false, 'type' => 'textarea'],
                'description'      => ['label' => 'Description 1',        'required' => false, 'show_in_table' => false, 'type' => 'editor'],

                'description_1'    => ['label' => 'Description 2',        'required' => false, 'show_in_table' => false, 'type' => 'editor'],
                'description_2'    => ['label' => 'Description 3',        'required' => false, 'show_in_table' => false, 'type' => 'editor'],
                'img_path'         => ['label' => 'Thumbnail',            'required' => true,  'show_in_table' => true,  'type' => 'image'],

                'extra'            => ['label' => 'Extra Data',           'required' => false, 'show_in_table' => false, 'type' => 'json'],
                'start_date'       => ['label' => 'Publish Date',         'required' => false, 'show_in_table' => true,  'type' => 'datetime'],
                'end_date'         => ['label' => 'End Date',             'required' => false, 'show_in_table' => false, 'type' => 'datetime'],
                'meta_title'       => ['label' => 'Meta Title',           'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'meta_description' => ['label' => 'Meta Description',     'required' => false, 'show_in_table' => false, 'type' => 'textarea'],
                'meta_keywords'    => ['label' => 'Meta Keywords',        'required' => false, 'show_in_table' => false, 'type' => 'tag'],
                'status'           => ['label' => 'Status',               'required' => true,  'show_in_table' => true,  'type' => 'select'],
                'admin_approved'   => ['label' => 'Approve Status',       'required' => false,  'show_in_table' => true,  'type' => 'number'],

            ],

            'faq' => [
                'module_name' => ['label' => 'FAQs', 'icon' => 'bi bi-question-circle'],
                'title'       => ['label' => 'Question', 'required' => true, 'show_in_table' => true, 'type' => 'text'],
                'description' => ['label' => 'Answer',   'required' => true, 'show_in_table' => false, 'type' => 'editor'],
                'sort_order'  => ['label' => 'Sort Order', 'required' => false, 'show_in_table' => true, 'type' => 'number'],
                'status'      => ['label' => 'Status',     'required' => true, 'show_in_table' => true, 'type' => 'select'],
            ],
            'partner' => [
                'module_name' => ['label' => 'Loan Partners', 'icon' => 'bi bi-bank'],
                'title'       => ['label' => 'Bank / FI Name', 'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'short'       => ['label' => 'Interest Rate',  'required' => true,  'show_in_table' => true,  'type' => 'text'], // e.g. Starting from 7.99%
                'img_path'    => ['label' => 'Bank Logo',      'required' => true,  'show_in_table' => true,  'type' => 'image'],
                'description' => ['label' => 'Loan Details',   'required' => false, 'show_in_table' => false, 'type' => 'editor'], // Max loan tenure, processing fee, etc.
                'sort_order'  => ['label' => 'Sort Order',     'required' => false, 'show_in_table' => true,  'type' => 'number'],
                'status'      => ['label' => 'Status',         'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],
            'area_guide' => [
                'module_name'    => ['label' => 'Area Guides', 'icon' => 'bi bi-map'],
                'destination_id' => ['label' => 'Select Location (Destination)', 'required' => true, 'show_in_table' => true, 'type' => 'select'],
                'title'          => ['label' => 'Area Name',    'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'slug'           => ['label' => 'Slug',         'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'short'          => ['label' => 'Tagline',      'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'features'       => [
                    'label' => 'Area Key Metrics / Sights',
                    'required' => false,
                    'show_in_table' => false,
                    'type' => 'features',
                    'keys' => ['Average Price', 'Popular Schools', 'Top Hospitals', 'Metro Access', 'Development Status']
                ],
                // 3 Description Rich Text Editors
                'description'    => ['label' => 'Detailed Guide Part 1', 'required' => false, 'show_in_table' => false, 'type' => 'editor'],
                'description_1'  => ['label' => 'Detailed Guide Part 2', 'required' => false, 'show_in_table' => false, 'type' => 'editor'],
                'description_2'  => ['label' => 'Detailed Guide Part 3', 'required' => false, 'show_in_table' => false, 'type' => 'editor'],

                // Single and Multiple Images config
                'img_path'       => ['label' => 'Featured Image', 'required' => true,  'show_in_table' => true,  'type' => 'image'],
                'img_paths'      => ['label' => 'Gallery Images',  'required' => false, 'show_in_table' => false, 'type' => 'image_multiple'],

                'sort_order'     => ['label' => 'Sort Order',   'required' => false, 'show_in_table' => true,  'type' => 'number'],
                'status'         => ['label' => 'Status',       'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],
            // ------------------------------------------------------------------
            // Pages — static pages (about, contact, etc.)
            // ------------------------------------------------------------------
            'page' => [
                'module_name' => ['label' => 'Pages', 'icon' => 'bi bi-file-text'],
                'title'            => ['label' => 'Title',                'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'slug'             => ['label' => 'Slug',                 'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'prev_slug'        => ['label' => 'Previous Slug',        'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'short'            => ['label' => 'Short Description',    'required' => false, 'show_in_table' => false, 'type' => 'textarea'],
                'description'      => ['label' => 'Description',          'required' => true,  'show_in_table' => false, 'type' => 'editor'],
                'description_1'    => ['label' => 'Additional Section',   'required' => false, 'show_in_table' => false, 'type' => 'editor'],
                'meta_title'       => ['label' => 'Meta Title',           'required' => false, 'show_in_table' => false, 'type' => 'text'],
                'meta_description' => ['label' => 'Meta Description',     'required' => false, 'show_in_table' => false, 'type' => 'textarea'],
                'meta_keywords'    => ['label' => 'Meta Keywords',        'required' => false, 'show_in_table' => false, 'type' => 'tag'],
                'status'           => ['label' => 'Status',               'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],

            // ------------------------------------------------------------------
            // Social — social media platform links
            // ------------------------------------------------------------------
            'social' => [
                'module_name' => ['label' => 'Social', 'icon' => 'bi bi-share'],
                'title'       => ['label' => 'Icon Code - fontawsome',             'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'url'         => ['label' => 'Profile URL',               'required' => true,  'show_in_table' => true,  'type' => 'url'],
                'status'      => ['label' => 'Status',                    'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],
            'meta_info' => [
                'module_name' => ['label' => 'Meta Info', 'icon' => 'bi bi-info-circle'],
                'title'            => ['label' => 'Page Name (e.g. Home Page)', 'show_in_table' => true, 'required' => true],
                'slug'             => ['label' => 'Page Slug', 'required' => true,  'show_in_table' => false,],
                'description'          => ['label' => 'Description 1 (Box 1)', 'required' => false, 'type' => 'editor'],
                'description_status'   => ['label' => 'Description 1 Status', 'required' => true, 'type' => 'select_status'],

                'description_1'        => ['label' => 'Description 2 (Box 2)', 'required' => false, 'type' => 'editor'],
                'description_1_status' => ['label' => 'Description 2 Status', 'required' => true, 'type' => 'select_status'],

                'description_2'        => ['label' => 'Description 3 (Box 3)', 'required' => false, 'type' => 'editor'],
                'description_2_status' => ['label' => 'Description 3 Status', 'required' => true, 'type' => 'select_status'],
                'meta_title'       => ['label' => 'Meta Title', 'required' => true,  'show_in_table' => true,],

                'meta_description' => ['label' => 'Meta Description', 'required' => false,  'show_in_table' => true,],
                'meta_keywords'    => ['label' => 'Meta Keywords', 'required' => false, 'show_in_table' => false,],
                'status'           => ['label' => 'Status',               'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],
            'global_meta' => [
                'module_name' => ['label' => 'Global Meta', 'icon' => 'bi bi-globe'],
                'meta_title'       => ['label' => 'Meta Title', 'required' => true,  'show_in_table' => true,],
                'meta_description' => ['label' => 'Meta Description', 'required' => false,  'show_in_table' => true,],
                'meta_keywords'    => ['label' => 'Meta Keywords', 'required' => false, 'show_in_table' => false,],
                'status'           => ['label' => 'Status',               'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],
            'about_hero' => [
                'module_name' => ['label' => 'About Hero', 'icon' => 'bi bi-person-badge'],
                'title'       => ['label' => 'Hero Title',            'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'short'       => ['label' => 'Hero Description',      'required' => true,  'show_in_table' => false, 'type' => 'textarea'],
                'img_path'         => ['label' => 'Hero Image',            'required' => true,  'show_in_table' => true,  'type' => 'image'],
                'sort_order'  => ['label' => 'Sort Order',             'required' => false, 'show_in_table' => true,  'type' => 'number'],
                'status'      => ['label' => 'Status',                 'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],
            'about_value' => [
                'module_name' => ['label' => 'About Core Values', 'icon' => 'bi bi-gem'],
                'title'       => ['label' => 'Value Title',            'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'short'       => ['label' => 'Value Description',      'required' => true,  'show_in_table' => false, 'type' => 'textarea'],
                'url'         => ['label' => 'FontAwesome Icon Class', 'required' => true,  'show_in_table' => true,  'type' => 'text'], // e.g. fa-solid fa-lightbulb
                'sort_order'  => ['label' => 'Sort Order',             'required' => false, 'show_in_table' => true,  'type' => 'number'],
                'status'      => ['label' => 'Status',                 'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],
            'stat' => [
                'module_name' => ['label' => 'Statistics', 'icon' => 'bi bi-bar-chart'],
                'title'       => ['label' => 'Value Title',            'required' => true,  'show_in_table' => true,  'type' => 'text'],
                'short'       => ['label' => 'Value',      'required' => true,  'show_in_table' => false, 'type' => 'text'],
                'sort_order'  => ['label' => 'Sort Order',             'required' => false, 'show_in_table' => true,  'type' => 'number'],
                'status'      => ['label' => 'Status',                 'required' => true,  'show_in_table' => true,  'type' => 'select'],
            ],

        ];

        View::share('modules', $modules);

        $setting = Cache::rememberForever('site_settings', function () {
            return Setting::latest()->first();
        });
        View::share('setting', $setting);
        $allMeta = Content::where('module', 'meta_info')->where('status', 1)->get()->keyBy('slug');
        View::share('allMeta', $allMeta);

        // Share active social links
        $socials = Cache::rememberForever('social_links', function () {
            return Content::where('module', 'social')
                ->where('status', 1)
                ->orderBy('sort_order')
                ->get()
                ->keyBy(function ($item) {
                    return strtolower($item->title);
                });
        });
        View::share('socials', $socials);

        $globalMeta = Cache::rememberForever('global_meta', function () {
            return Content::where('module', 'global_meta')
                ->where('status', 1)
                ->latest()
                ->first();
        });
        View::share('globalMeta', $globalMeta);

        $pages = Content::where('module', 'page')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        View::share('pages', $pages);




        $categories = Content::where('module', 'category')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();
        View::share('categories', $categories);

        $destination = Content::where('module', 'destination')
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        View::share('destination', $destination);
    }
}
