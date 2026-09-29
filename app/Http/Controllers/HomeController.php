<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Requirement;
use App\Models\Subscription;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
public function index()
{
    if (auth()->check() && auth()->user()->user_type === 'user') {
        return redirect()->route('web.home');
    }

    $filter = request()->get('filter', 'all'); // default to 'all'
    $startDate = null;
    $endDate = null;

    // কার্বন ডেট প্রসেসিং
    if (class_exists(\Carbon\Carbon::class)) {
        switch ($filter) {
            case 'today':
                $startDate = \Carbon\Carbon::today()->startOfDay();
                $endDate = \Carbon\Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $startDate = \Carbon\Carbon::yesterday()->startOfDay();
                $endDate = \Carbon\Carbon::yesterday()->endOfDay();
                break;
            case 'last_7':
                $startDate = \Carbon\Carbon::today()->subDays(7)->startOfDay();
                $endDate = \Carbon\Carbon::now()->endOfDay();
                break;
            case 'this_month':
                $startDate = \Carbon\Carbon::now()->startOfMonth()->startOfDay();
                $endDate = \Carbon\Carbon::now()->endOfDay();
                break;
            case 'custom':
                if (request()->filled('start_date') && request()->filled('end_date')) {
                    $startDate = \Carbon\Carbon::parse(request()->get('start_date'))->startOfDay();
                    $endDate = \Carbon\Carbon::parse(request()->get('end_date'))->endOfDay();
                }
                break;
        }
    }

    // ফিল্টার কুয়েরি হেল্পার ক্লোজার
    $applyFilter = function($query) use ($startDate, $endDate) {
        if ($startDate && $endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        return $query;
    };

    $data = [
        'totalProjects'       => 0,
        'totalDevelopers'     => 0,
        'totalRequirements'   => 0,
        'totalSubscribers'    => 0,
        'pendingProjects'     => 0,
        'pendingDevelopers'   => 0,
        'registeredCustomers' => 0,

        'activeProjects'      => 0,
        'pendingReview'       => 0,
        'myLeads'             => 0,
        
        'currentFilter'       => $filter,
        'startDateVal'        => request()->get('start_date', ''),
        'endDateVal'          => request()->get('end_date', ''),
    ];

    $chartLabels = [];
    $chartProjects = [];
    $chartDevelopers = [];
    $chartLeads = [];
    $currentYear = (int) date('Y'); // কার্বনের টাইপ এরর এড়াতে ইন্টিজারে কাস্ট করা হয়েছে

    if (auth()->check()) {
        $user = auth()->user();
        $companyId = $user->company_id;

        if ($user->hasRole('super-admin')) {
            $data['totalProjects']       = $applyFilter(Content::where('module', 'project'))->count() ?? 0;
            $data['totalDevelopers']     = $applyFilter(Company::query())->count() ?? 0;
            $data['totalRequirements']   = $applyFilter(Requirement::query())->count() ?? 0;
            $data['totalSubscribers']    = $applyFilter(Subscription::query())->count() ?? 0;

            $data['pendingProjects']     = Content::where('module', 'project')->where('status', 0)->count() ?? 0;
            $data['pendingDevelopers']   = Company::where('status', 0)->count() ?? 0;
            $data['registeredCustomers'] = User::where('user_type', 'user')->count() ?? 0;
        } elseif ($user->isCompany()) {
            $data['activeProjects']      = Content::where('module', 'project')->where('company_id', $companyId)->where('status', 1)->count() ?? 0;
            $data['pendingReview']       = Content::where('module', 'project')->where('company_id', $companyId)->where('status', 0)->count() ?? 0;
            $data['myLeads']             = $applyFilter(Contact::where('company_id', $companyId))->count() ?? 0;
        }

        // ── ডাইনামিক চার্ট লেবেল ও এক্স-অক্ষ (X-Axis) নির্ধারণ ──
        if (in_array($filter, ['today', 'yesterday']) && $startDate) {
            // আজকের/গতকালের জন্য প্রতি ২ ঘণ্টার ডাটা গ্রাফ
            for ($h = 0; $h < 24; $h += 2) {
                $hourInt = (int) $h;
                $chartLabels[] = \Carbon\Carbon::today()->hour($hourInt)->format('g A');
                $hStart = $startDate->copy()->hour($hourInt)->startOfHour();
                $hEnd = $startDate->copy()->hour($hourInt + 1)->endOfHour();

                if ($user->hasRole('super-admin')) {
                    $chartProjects[]   = Content::where('module', 'project')->whereBetween('created_at', [$hStart, $hEnd])->count();
                    $chartDevelopers[] = Company::whereBetween('created_at', [$hStart, $hEnd])->count();
                } else {
                    $chartProjects[]   = Content::where('module', 'project')->where('company_id', $companyId)->whereBetween('created_at', [$hStart, $hEnd])->count();
                    $chartLeads[]      = Contact::where('company_id', $companyId)->whereBetween('created_at', [$hStart, $hEnd])->count();
                }
            }
        } elseif ($filter === 'last_7') {
            // গত ৭ দিনের দিনভিত্তিক গ্রাফ
            for ($i = 6; $i >= 0; $i--) {
                $daysSub = (int) $i;
                $day = \Carbon\Carbon::today()->subDays($daysSub);
                $chartLabels[] = $day->format('D, d M');
                $dayStart = $day->copy()->startOfDay();
                $dayEnd = $day->copy()->endOfDay();

                if ($user->hasRole('super-admin')) {
                    $chartProjects[]   = Content::where('module', 'project')->whereBetween('created_at', [$dayStart, $dayEnd])->count();
                    $chartDevelopers[] = Company::whereBetween('created_at', [$dayStart, $dayEnd])->count();
                } else {
                    $chartProjects[]   = Content::where('module', 'project')->where('company_id', $companyId)->whereBetween('created_at', [$dayStart, $dayEnd])->count();
                    $chartLeads[]      = Contact::where('company_id', $companyId)->whereBetween('created_at', [$dayStart, $dayEnd])->count();
                }
            }
        } elseif ($filter === 'this_month') {
            // চলতি মাসের তারিখভিত্তিক গ্রাফ (১ দিন গ্যাপ রেখে রিড করা হবে)
            $daysInMonth = (int) \Carbon\Carbon::now()->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                if ($d % 2 !== 0 && $d !== $daysInMonth) continue;
                $dayInt = (int) $d;
                $day = \Carbon\Carbon::now()->day($dayInt);
                $chartLabels[] = $day->format('d M');
                $dayStart = $day->copy()->startOfDay();
                $dayEnd = $day->copy()->endOfDay();

                if ($user->hasRole('super-admin')) {
                    $chartProjects[]   = Content::where('module', 'project')->whereBetween('created_at', [$dayStart, $dayEnd])->count();
                    $chartDevelopers[] = Company::whereBetween('created_at', [$dayStart, $dayEnd])->count();
                } else {
                    $chartProjects[]   = Content::where('module', 'project')->where('company_id', $companyId)->whereBetween('created_at', [$dayStart, $dayEnd])->count();
                    $chartLeads[]      = Contact::where('company_id', $companyId)->whereBetween('created_at', [$dayStart, $dayEnd])->count();
                }
            }
        } else {
            // All-Time অথবা কাস্টম ফিল্টারের ক্ষেত্রে মাসিক গ্রাফ
            $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            for ($m = 1; $m <= 12; $m++) {
                $monthInt = (int) $m;
                $mStart = \Carbon\Carbon::now()->year($currentYear)->month($monthInt)->startOfMonth();
                $mEnd = \Carbon\Carbon::now()->year($currentYear)->month($monthInt)->endOfMonth();

                if ($user->hasRole('super-admin')) {
                    $chartProjects[]   = Content::where('module', 'project')->whereBetween('created_at', [$mStart, $mEnd])->count();
                    $chartDevelopers[] = Company::whereBetween('created_at', [$mStart, $mEnd])->count();
                } else {
                    $chartProjects[]   = Content::where('module', 'project')->where('company_id', $companyId)->whereBetween('created_at', [$mStart, $mEnd])->count();
                    $chartLeads[]      = Contact::where('company_id', $companyId)->whereBetween('created_at', [$mStart, $mEnd])->count();
                }
            }
        }
    }

    $data['chartLabels']     = $chartLabels;
    $data['chartProjects']   = $chartProjects;
    $data['chartDevelopers'] = $chartDevelopers;
    $data['chartLeads']      = $chartLeads;

    return view('home', $data);
}


    public function contactList()
    {
        $contacts = Contact::get();
        return view('backend.contactList', compact('contacts'));
    }
    public function toggleRead($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->is_read = !$contact->is_read; // Toggles between true and false
        $contact->save();

        return back()->with('success', 'Status updated successfully');
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return back()->with('success', 'Contact deleted successfully');
    }
}
