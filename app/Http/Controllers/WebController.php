<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Rules\ValidPhoneNumber;
use Illuminate\Support\Facades\Validator;

class WebController extends Controller
{

    private function getFields(string $module): array
    {
        $modules = view()->shared('modules');

        // Fallback columns if module not found in config
        if (!isset($modules[$module])) {
            return ['id', 'module', 'title', 'slug', 'img_path', 'short', 'status'];
        }

        // Build column list from module config keys
        $fields = array_keys($modules[$module]);

        // Always include these core columns
        $fields[] = 'id';
        $fields[] = 'company_id';
        $fields[] = 'module';
        $fields[] = 'slug';
        $fields[] = 'sort_order';
        $fields[] = 'status';
        $fields[] = 'published_at';
        $fields[] = 'parent_id';
        $fields[] = 'destination_id';
        $fields[] = 'start_date';

        // Remove config-only keys that are not real DB columns
        $nonColumns = ['module_name', 'parent_module'];
        $fields = array_diff($fields, $nonColumns);

        return array_unique($fields);
    }


    private function fetchContent(string $module, ?int $limit = null)
    {
        $query = Content::select($this->getFields($module))
            ->where('module', $module)
            ->active()      // status = 1

            ->sorted()      // order by sort_order asc
            ->where(function ($q) {
                // published_at <= now() OR published_at is null
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });

        if ($limit === 1) {
            return $query->first();
        }

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }

    /**
     * Homepage
     */
    public function home()
    {
        // Hero banner
        $hero = Content::with('company')
            ->where('module', 'hero')
            ->active()

            ->sorted()
            ->limit(5)
            ->get();

        // Category tabs (4 items)
        $categories = $this->fetchContent('category', 4);

        // Statistics
        $stats = $this->fetchContent('stats');

        // Featured projects (6 items)
        $projects = Content::with('company', 'category', 'destination')
            ->where('module', 'project')
            ->active()
            ->approved()
            ->sorted()
            ->limit(6)
            ->get();

        $liveProject = Content::with('company', 'category', 'destination')
            ->where('module', 'project')
            ->active()
            ->approved()
            ->sorted()
            ->whereNotNull('video_path')
            ->where('video_path', '!=', '')
            ->limit(3)
            ->get();

        $galleryImages = $this->fetchContent('gallery', 6);


        $destinations = Content::where('module', 'destination')->where('status', 1)->sorted()->get();
        $companies = Company::where('status', 1)->get();
        // Blog posts (3 items)
        $blogs  = Content::with('company', 'user')
            ->where('module', 'blog')
            ->active()
            ->approved()
            ->sorted()
            ->limit(3)
            ->get();
        return view('frontend.index', compact(
            'hero',

            'stats',
            'projects',
            'liveProject',
            'galleryImages',

            'blogs',
            'categories',
            'destinations',
            'companies',
        ));
    }


    // WebController.php এর project মেথডের ভেতর পরিবর্তন করুন:

    public function project(Request $request)
    {
        $activeCategory = null;
        $activeCompany = null;

        if ($request->filled('category')) {
            $activeCategory = Content::where('module', 'category')
                ->where('status', 1)
                ->where('slug', $request->input('category'))
                ->first();
        }

        if ($request->filled('company')) {
            $activeCompany = Company::where('status', 1)
                ->where('slug', $request->input('company'))
                ->first();
        }

        $query = Content::where('module', 'project')
            ->active()
            ->approved()
            ->with(['company', 'category', 'destination']);

        if ($activeCategory) {
            $query->where('parent_id', $activeCategory->id);
        }

        if ($activeCompany) {
            $query->where('company_id', $activeCompany->id);
        }

        // Other filters (Location, Search, etc.)
        if ($request->filled('dest')) {
            $query->where('destination_id', $request->input('dest'));
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }
        if ($request->filled('city_area')) {
            $query->whereHas('destination', function ($q) use ($request) {
                $q->where('short', $request->input('city_area'));
            });
        }
        if ($request->filled('sub_area')) {
            $query->whereHas('destination', function ($q) use ($request) {
                $q->where('description', $request->input('sub_area'));
            });
        }

        // Paginate results with secure cursor pagination
        $projects = $query->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->cursorPaginate(12);

        $recentlyAdded = Content::where('module', 'project')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentIds = session()->get('recent_projects', []);
        $recentlyViewed = Content::where('module', 'project')
            ->where('status', 1)
            ->whereIn('id', $recentIds)
            ->limit(5)
            ->get();

        $categories = Content::where('module', 'category')->where('status', 1)->get();
        $destinations = Content::where('module', 'destination')->where('status', 1)->get();
        $companies = Company::where('status', 1)->get();

        if ($request->ajax() || $request->has('ajax') || $request->expectsJson()) {
            return response()->json([
                'html' => view('frontend.partials._items', compact('projects'))->render(),
                'next_page_url' => $projects->nextPageUrl()
            ]);
        }

        return view('frontend.pages.project', compact(
            'projects',
            'activeCategory',
            'activeCompany',
            'recentlyAdded',
            'recentlyViewed',
            'categories',
            'destinations',
            'companies'
        ));
    }

    public function projectCat(Request $request, $slug)
    {
        $activeCategory = Content::where('module', 'category')
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $query = Content::where('module', 'project')
            ->active()
            ->approved()
            ->where('parent_id', $activeCategory->id)
            ->with(['company', 'category', 'destination']);

        $projects = $query->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->cursorPaginate(12);

        $recentlyAdded = Content::where('module', 'project')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentIds = session()->get('recent_projects', []);
        $recentlyViewed = Content::where('module', 'project')
            ->where('status', 1)
            ->whereIn('id', $recentIds)
            ->limit(5)
            ->get();

        $categories = Content::where('module', 'category')->where('status', 1)->get();
        $destinations = Content::where('module', 'destination')->where('status', 1)->get();
        $companies = Company::where('status', 1)->get();

        // 4. Handle AJAX infinite scroll response safely
        if ($request->ajax() || $request->has('ajax') || $request->expectsJson()) {
            return response()->json([
                'html' => view('frontend.partials._items', compact('projects'))->render(),
                'next_page_url' => $projects->nextPageUrl()
            ]);
        }

        return view('frontend.pages.project', compact(
            'projects',
            'activeCategory',
            'recentlyAdded',
            'recentlyViewed',
            'categories',
            'destinations',
            'companies'
        ));
    }
    public function pageDetails($slug)
    {
        $page = Content::where('module', 'page')->where('slug', $slug)->where('status', 1)->firstOrFail();

        return view('frontend.pages.dynamicPage', compact('page'));
    }

    public function details(Request $request, string $slug)
    {
        $query = Content::where('module', 'project')
            ->where('slug', $slug)
            ->with(['company', 'category']);


        if (request()->query('preview') !== '1') {
            $query->where('status', 1);
        }
        $project = $query->firstOrFail();
        $similarProjects = Content::where('module', 'project')
            ->where('status', 1)
            ->where('id', '!=', $project->id)
            ->where(function ($query) use ($project) {
                $query->where('parent_id', $project->parent_id)
                    ->orWhere('destination_id', $project->destination_id);
            })
            ->limit(3)
            ->get();

        $recentIds = session()->get('recent_projects', []);

        $recentProjects = Content::where('module', 'project')
            ->where('status', 1)
            ->whereIn('id', $recentIds)
            ->where('id', '!=', $project->id)
            ->limit(3)
            ->get();

        // ৪. বর্তমান প্রজেক্টের আইডিটি সেশনে পুশ করা (পরবর্তী পেজ লোডের জন্য)
        if (!in_array($project->id, $recentIds)) {
            array_unshift($recentIds, $project->id); // একদম শুরুতে যুক্ত করবে
            $recentIds = array_slice($recentIds, 0, 5); // সর্বোচ্চ ৫টি আইডি সেভ রাখবে
            session()->put('recent_projects', $recentIds);
        }

        return view('frontend.pages.details', compact('project', 'similarProjects', 'recentProjects'));
    }
    /**
     * Project/Property Details Page
     */
    public function projectDetails($slug)
    {
        // Get project by slug
        $project = Content::where('module', 'project')
            ->bySlug($slug)
            ->active()
            ->approved()
            ->firstOrFail();

        // Increment views
        $project->increment('views');

        // Get related projects (same category)
        $relatedProjects = Content::where('module', 'project')
            ->where('parent_id', $project->parent_id)
            ->where('id', '!=', $project->id)
            ->active()
            ->sorted()
            ->take(4)
            ->get();

        // If not enough related, get any active projects
        if ($relatedProjects->count() < 4) {
            $relatedProjects = Content::where('module', 'project')
                ->where('id', '!=', $project->id)
                ->active()
                ->sorted()
                ->take(4)
                ->get();
        }

        // Get project features/glances
        $projectGlances = $project->projectGlances()->get();

        // Get project features list
        $features = $project->features()->get();

        // Get project gallery
        $gallery = $project->gallery()->take(6)->get();

        // Get project videos
        $videos = $project->videos()->take(3)->get();

        return view('frontend.pages.project-details', compact(
            'project',
            'relatedProjects',
            'projectGlances',
            'features',
            'gallery',
            'videos'
        ));
    }

    /**
     * Project/Property Listing with Filters
     */
    public function projects(Request $request)
    {
        $query = Content::where('module', 'project')->active()->approved();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('parent_id', $request->category);
        }

        // Filter by destination
        if ($request->has('destination') && $request->destination) {
            $query->where('destination_id', $request->destination);
        }

        // Filter by location
        if ($request->has('location') && $request->location) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $projects = $query->sorted()->paginate(3);

        // Get filter options
        $categories = Content::where('module', 'category')->active()->sorted()->get();
        $destinations = Content::where('module', 'destination')->active()->sorted()->get();

        return view('frontend.pages.projects', compact('projects', 'categories', 'destinations'));
    }

    /**
     * Gallery/Image Gallery Listing
     */
    public function gallery(Request $request)
    {
        $query = Content::where('module', 'gallery')->active();

        // Filter by project (if applicable)
        if ($request->has('project') && $request->project) {
            $query->where('parent_id', $request->project);
        }

        $galleryImages = $query->sorted()->paginate(12);

        return view('frontend.pages.gallery', compact('galleryImages'));
    }

    /**
     * Blog Listing
     */
    public function blog(Request $request)
    {
        $query = Content::where('module', 'blog')->active()->approved();

        // Filter by project/category
        if ($request->has('category') && $request->category) {
            $query->where('parent_id', $request->category);
        }

        $blogs = $query->latest('start_date')->paginate(6);

        // Get categories
        $categories = Content::where('module', 'project')->active()->sorted()->get();

        return view('frontend.blog.index', compact('blogs', 'categories'));
    }

    /**
     * Blog Details Page
     */
    public function blogDetails($slug)
    {
        $query = Content::where('module', 'blog')
            ->bySlug($slug);


        if (request()->query('preview') !== '1') {
            $query->active();
        }

        $blog = $query->firstOrFail();
        // Increment views
        $blog->increment('views');

        // Get related blogs (same project/category)
        $relatedBlogs = Content::where('module', 'blog')
            ->where('parent_id', $blog->parent_id)
            ->where('id', '!=', $blog->id)
            ->active()
            ->approved()
            ->latest('start_date')
            ->take(5)
            ->get();

        // If not enough, get recent blogs
        if ($relatedBlogs->count() < 5) {
            $relatedBlogs = Content::where('module', 'blog')
                ->where('id', '!=', $blog->id)
                ->active()
                ->approved()
                ->latest('start_date')
                ->take(5)
                ->get();
        }

        return view('frontend.blog.details', compact('blog', 'relatedBlogs'));
    }

    /**
     * Static Page (About, Contact, Terms, etc.)
     */
    public function page($slug)
    {
        $page = Content::where('module', 'page')
            ->bySlug($slug)
            ->active()
            ->firstOrFail();

        return view('frontend.pages.page', compact('page'));
    }

    /**
     * Contact Form - Show form
     */
    public function contactForm()
    {
        // Get categories for form
        $categories = Content::where('module', 'category')
            ->active()
            ->sorted()
            ->get();

        return view('frontend.pages.contact', compact('categories'));
    }

    /**
     * Contact Form - Store submission
     */
    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'phone'      => ['required', 'string', new ValidPhoneNumber()],
            'designation' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:contents,id',
            'message'     => 'nullable|string|max:2000',
        ]);

        Contact::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'phone'      => ['required', 'string', new ValidPhoneNumber()],
            'designation' => $validated['designation'],
            'category_id' => $validated['category_id'],
            'message'     => $validated['message'],
        ]);

        return redirect()->back()->with('success', 'Thank you! Your message has been sent.');
    }

    /**
     * Email Subscription
     */
    public function subscriptionStore(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:subscriptions,email',
        ], [
            'email.unique' => 'This email is already subscribed!',
        ]);

        Subscription::create([
            'email' => $validated['email'],
        ]);

        return redirect()->back()->withFragment('subscribe-id')->with('success', 'Thank you for subscribing!');
    }


    public function compare(Request $request)
    {
        $ids = $request->filled('ids') ? explode(',', $request->input('ids')) : [];

        $ids = array_slice($ids, 0, 3);

        $projects = [];
        if (!empty($ids)) {
            $projects = Content::where('module', 'project')
                ->whereIn('id', $ids)
                ->where('status', 1)
                ->with(['company', 'category'])
                ->get();
        }

        return view('frontend.pages.compare', compact('projects'));
    }
    public function faq()
    {
        $faqs = Content::where('module', 'faq')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.faq', compact('faqs'));
    }
    public function loanPartners()
    {
        $partners = Content::where('module', 'partner')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.loan_partners', compact('partners'));
    }
    public function areaGuides()
    {
        $guides = Content::where('module', 'area_guide')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('frontend.area_guides', compact('guides'));
    }
    public function areaGuideDetails(string $slug)
    {
        $query = Content::where('module', 'area_guide')
            ->where('slug', $slug);
        if (request()->query('preview') !== '1') {
            $query->where('status', 1);
        }

        $guide = $query->firstOrFail();

        $relatedProjects = Content::where('module', 'project')
            ->where('destination_id', $guide->destination_id)
            ->where('status', 1)
            ->limit(3)
            ->get();

        return view('frontend.area_guide_details', compact('guide', 'relatedProjects'));
    }
    /**
     * Search across projects, blogs, pages
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return redirect()->back()->with('error', 'Search query too short');
        }

        $results = Content::where('status', 1)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('short', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%");
            })
            ->whereIn('module', ['project', 'blog', 'page'])
            ->latest('created_at')
            ->paginate(12);

        return view('frontend.pages.search-results', compact('results', 'query'));
    }

    /**
     * Sitemap (for SEO)
     */
    public function sitemap()
    {
        $pages = Content::where('module', 'page')
            ->active()
            ->latest()
            ->get();

        $projects = Content::where('module', 'project')
            ->active()
            ->latest()
            ->get();

        $blogs = Content::where('module', 'blog')
            ->active()
            ->latest()
            ->get();

        return view('frontend.sitemap', compact('pages', 'projects', 'blogs'))
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Category/Destination Listing
     */
    public function category($slug)
    {
        $category = Content::where('module', 'category')
            ->bySlug($slug)
            ->active()
            ->firstOrFail();

        // Get projects in this category
        $projects = Content::where('module', 'project')
            ->where('parent_id', $category->id)
            ->active()
            ->sorted()
            ->paginate(6);

        return view('frontend.pages.category', compact('category', 'projects'));
    }

    public function destination($slug)
    {
        $destination = Content::where('module', 'destination')
            ->bySlug($slug)
            ->active()
            ->firstOrFail();

        // Get projects in this destination
        $projects = Content::where('module', 'project')
            ->where('destination_id', $destination->id)
            ->active()
            ->sorted()
            ->paginate(6);

        return view('frontend.pages.destination', compact('destination', 'projects'));
    }
    public function about()
    {
        // 1. Query dynamic core values for the about page
        $aboutValues = Content::where('module', 'about_value')
            ->where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->get();
        $aboutHero = Content::where('module', 'about_hero')
            ->where('status', 1)
            ->first();

        // 2. Query dynamic statistics counters (Reusing your existing stats module)
        $stats = Content::where('module', 'stat')
            ->where('status', 1)
            ->get();

        return view('frontend.pages.about', compact('aboutValues', 'stats', 'aboutHero'));
    }
}
