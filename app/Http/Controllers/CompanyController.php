<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Content;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\EmailVerificationController;
use App\Models\Contact;
use App\Rules\ValidPhoneNumber;

class CompanyController extends Controller
{


    public function index(Request $request)
    {
        $query = Company::where('status', 1);

        // Filter companies by search keyword if provided
        if ($request->filled('search')) {
            $query->where('company_name', 'like', '%' . $request->input('search') . '%');
        }

        // Retrieve companies sorted alphabetically and paginated
        $companies = $query->orderBy('company_name', 'asc')->paginate(18);

        return view('frontend.company.index', compact('companies'));
    }
    public function LoginPage()
    {
        $loginBenefits = Content::where('module', 'login-benefits')->active()->sorted()->get();
        $helpline = Content::where('module', 'helpline')->active()->latest()->first();

        return view('frontend.company.login', compact('loginBenefits', 'helpline'));
    }
    public function companyLogin(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
            'recaptcha_token' => ['required', new Recaptcha()],
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Please verify your email address before logging in. Check your inbox for the verification link.'
                ], 403);
            }

            if ($user->status !== 1) {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is currently under review and has not been activated yet.'
                ], 403);
            }

            if ($request->filled('redirect')) {
                $redirectUrl = $request->input('redirect');
            } else {
                if ($user->user_type === 'user') {
                    $redirectUrl = route('user.dashboard');
                } else if ($user->user_type === 'developer') {
                    $redirectUrl = route('home');
                } else {
                    $redirectUrl = route('home');
                }
            }

            return response()->json([
                'success' => true,
                'redirect_url' => $redirectUrl
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'These credentials do not match our records.'
        ], 422);
    }
    public function companyRegistration()
    {
        $loginBenefits = Content::where('module', 'login-benefits')->active()->sorted()->get();
        $helpline = Content::where('module', 'helpline')->active()->latest()->first();

        return view('frontend.company.registration', compact('loginBenefits', 'helpline'));
    }

    public function register(Request $request)
    {
        $accountType = $request->input('account_type', 'individual');

        $rules = [
            'account_type' => 'required|in:individual,company',
            'email'        => 'required|string|email|max:255|unique:users,email',
            'phone'      => ['required', 'string', new ValidPhoneNumber()],
            'country_code' => 'required|string',
            'password'     => 'required|string|min:8|confirmed',
            'terms'        => 'accepted',
            'recaptcha_token' => ['required', new Recaptcha()],
        ];

        $messages = [
            'account_type.required' => 'Account type is required.',
            'email.required'        => 'Email address is required.',
            'email.email'           => 'Please enter a valid email address.',
            'email.unique'          => 'This email is already registered.',
            'phone.required'        => 'Phone number is required.',
            'password.required'     => 'Password is required.',
            'password.min'          => 'Password must be at least 8 characters.',
            'password.confirmed'    => 'Passwords do not match.',
            'terms.accepted'        => 'You must agree to the Terms & Conditions.',
        ];

        if ($accountType === 'individual') {
            $rules['full_name'] = 'required|string|max:255';
            $messages['full_name.required'] = 'Full name is required.';
        } else if ($accountType === 'company') {
            $rules['company_name'] = 'required|string|max:255';
            $rules['contact_person_name'] = 'required|string|max:255';
            $messages['company_name.required'] = 'Company name is required.';
            $messages['contact_person_name.required'] = 'Contact person name is required.';
        }

        $request->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $fullPhone = $request->country_code . ' ' . $request->phone;

            if ($accountType === 'individual') {
                $user = User::create([
                    'name'      => $request->full_name,
                    'email'     => $request->email,
                    'password'  => Hash::make($request->password),
                    'phone'     => $fullPhone,
                    'user_type' => 'user',
                    'status'    => 1,
                ]);

                Auth::login($user);

                DB::commit();

                // ✅ Verification email পাঠান
                app(EmailVerificationController::class)->sendVerificationEmail($user);

                // ✅ dashboard এ না পাঠিয়ে verification notice এ পাঠান
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful! Please check your email to verify your account.',
                    'redirect_url' => route('verification.notice')
                ], 201);
            } else {
                $user = User::create([
                    'name'      => $request->contact_person_name,
                    'email'     => $request->email,
                    'password'  => Hash::make($request->password),
                    'phone'     => $fullPhone,
                    'user_type' => 'developer',
                    'status'    => 1,
                ]);

                $company = Company::create([
                    'user_id'      => $user->id,
                    'company_name' => $request->company_name,
                    'slug'         => \Illuminate\Support\Str::slug($request->company_name),
                    'email'        => $request->email,
                    'phone'        => $fullPhone,
                    'status'       => 1,
                ]);

                $user->update([
                    'company_id' => $company->id
                ]);

                Auth::login($user);

                DB::commit();

                // ✅ Verification email পাঠান
                app(EmailVerificationController::class)->sendVerificationEmail($user);

                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful! Please verify your email. Your company account will also be reviewed by our admin team.',
                    'redirect_url' => route('verification.notice')
                ], 201);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    public function edit(?int $id = null)
    {
        $user = auth()->user();

        if ($id !== null) {
            abort_unless($user->hasRole('super-admin'), 403, 'Unauthorized access.');
            $company = Company::findOrFail($id);
        } else {
            abort_unless($user->company_id, 404, 'No company is linked to your account.');
            $company = Company::findOrFail($user->company_id);
        }
        $socials = is_array($company->social_links) ? $company->social_links : [];
        $socials = array_merge([
            'facebook' => '',
            'linkedin' => '',
            'twitter' => '',
            'instagram' => '',
            'youtube' => ''
        ], $socials);

        return view('admin.companies.profile', compact('company', 'socials', 'id'));
    }

    public function update(Request $request, ?int $id = null)
    {
        $user = auth()->user();

        if ($id !== null) {
            abort_unless($user->hasRole('super-admin'), 403, 'Unauthorized access.');
            $company = Company::findOrFail($id);
        } else {
            abort_unless($user->company_id, 404, 'No company is linked to your account.');
            $company = Company::findOrFail($user->company_id);
        }

        $request->validate([
            'company_name'     => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'phone'      => ['required', 'string', new ValidPhoneNumber()],
            'whatsapp'         => 'nullable|string|max:30',
            'website_url'      => 'nullable|url|max:255',
            'company_logo'     => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'company_cover'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'facebook'         => 'nullable|url',
            'linkedin'         => 'nullable|url',
            'twitter'          => 'nullable|url',
            'instagram'        => 'nullable|url',
            'youtube'          => 'nullable|url',
            'foundation_date'  => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('company_logo')) {
                if ($company->company_logo) {
                    Storage::disk('public')->delete($company->company_logo);
                }
                $company->company_logo = $request->file('company_logo')->store('uploads/companies/logos', 'public');
            }

            if ($request->hasFile('company_cover')) {
                if ($company->company_cover) {
                    Storage::disk('public')->delete($company->company_cover);
                }
                $company->company_cover = $request->file('company_cover')->store('uploads/companies/covers', 'public');
            }

            $socialLinks = [
                'facebook'  => $request->facebook,
                'linkedin'  => $request->linkedin,
                'twitter'   => $request->twitter,
                'instagram' => $request->instagram,
                'youtube'   => $request->youtube,
            ];

            $company->update([
                'company_name'     => $request->company_name,
                'tagline'          => $request->tagline,
                'about_us'         => $request->about_us,
                'email'            => $request->email,
                'phone'            => $request->phone,
                'whatsapp'         => $request->whatsapp,
                'website_url'      => $request->website_url,
                'address'          => $request->address,
                'maps_embed'       => $request->maps_embed,
                'social_links'     => $socialLinks,
                'founder_name'     => $request->founder_name,
                'foundation_date'  => $request->foundation_date,
                'industry'         => $request->industry,
                'trade_license'    => $request->trade_license,
                'tin_id'           => $request->tin_id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Company Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Company Profile Update Failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
    public function companyList(Request $request)
    {
        if (!auth()->user()->hasRole('super-admin')) {
            abort(403, 'Unauthorized');
        }

        $query = Company::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $companies = $query->latest()->paginate(10);

        return view('admin.companies.index', compact('companies'));
    }
    public function toggleCompanyStatus(Request $request, int $id)
    {
        try {
            $company = Company::findOrFail($id);

            $company->status = ($company->status === 1) ? 0 : 1;
            $company->save();

            if ($company->user) {
                $company->user->update([
                    'status' => $company->status
                ]);
            }

            return response()->json([
                'success' => true,
                'status'  => $company->status,
                'message' => 'Company and associated user status updated successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
    public function guide()
    {
        $user = auth()->user();

        abort_unless($user->isCompany() && $user->status == 1, 403, 'Unauthorized access.');

        return view('frontend.user.guide', compact('user'));
    }
    public function myProfile()
    {
        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->back()->with('error', 'No company associated with your account.');
        }

        return $this->showProfileData($company);
    }

    public function show(Company $company)
    {
        if (!auth()->user()->hasRole('super-admin') && auth()->user()->company_id !== $company->id) {
            abort(403, 'Unauthorized');
        }

        return $this->showProfileData($company);
    }

    private function showProfileData(Company $company)
    {
        // ১. মোট প্রজেক্ট ও ব্লগের হিসাব
        $totalProjects = Content::where('company_id', $company->id)->where('module', 'project')->count();
        $totalBlogs = Content::where('company_id', $company->id)->where('module', 'blog')->count();

        // ২. সর্বশেষ ৫টি প্রজেক্ট
        $latestProjects = Content::where('company_id', $company->id)
            ->where('module', 'project')
            ->latest()
            ->limit(5)
            ->get();

        // ── ৩. প্রোফাইল কমপ্লিটনেস হিসাব (ডাইনামিক) ──
        $fields = ['company_name', 'email', 'phone', 'about_us', 'company_logo', 'address', 'maps_embed', 'trade_license', 'tin_id', 'website_url', 'social_links'];
        $filledFields = 0;
        foreach ($fields as $field) {
            if (!empty($company->$field)) {
                $filledFields++;
            }
        }
        $profileCompleteness = round(($filledFields / count($fields)) * 100);

        // ── ৪. লাইভ কাস্টমার ইনকোয়ারি ফিড (CRM Activity) ──
        $recentLeads = 
             Contact::where('company_id', $company->id)->latest()->limit(3)->get()
            ?? collect();

        // ── ৫. প্রজেক্ট মডারেশন হিসাব ──
        $approvedProjects = Content::where('company_id', $company->id)->where('module', 'project')->where('status', 1)->count();
        $pendingProjects  = Content::where('company_id', $company->id)->where('module', 'project')->where('status', 0)->count();

        return view('admin.companies.show', compact(
            'company',
            'totalProjects',
            'totalBlogs',
            'latestProjects',
            'profileCompleteness',
            'recentLeads',
            'approvedProjects',
            'pendingProjects'
        ));
    }

    // ৪. প্রোফাইল এডিট পেজ
    public function edit2(Company $company)
    {
        if (!auth()->user()->hasRole('super-admin') && auth()->user()->company_id !== $company->id) {
            abort(403, 'Unauthorized');
        }

        return view('admin.companies.edit', compact('company'));
    }

    // ৫. প্রোফাইল আপডেট লজিক
    public function update2(Request $request, Company $company)
    {
        if (!auth()->user()->hasRole('super-admin') && auth()->user()->company_id !== $company->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            // অন্যান্য ভ্যালিডেশন...
        ]);

        $company->update($request->only('name', 'address', 'phone', 'email', 'website'));

        $redirectRoute = auth()->user()->hasRole('super-admin') ? 'admin.companies.index' : 'admin.companies.profile';

        return redirect()->route($redirectRoute)->with('success', 'Company profile updated successfully.');
    }
    public function changePassword()
    {
        return view('admin.password.edit');
    }

    public function updatePassword(Request $request)
    {
        // ফর্ম ভ্যালিডেশন
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'The provided current password does not match our records.');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Your password has been changed successfully.');
    }
    public function showLinkRequestForm()
    {
        return view('frontend.auth.forgot-password');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $successMessage = 'A password reset link has been sent to your email address. If you don\'t see it in your inbox within a few minutes, please check your Spam or Junk folder.';

        $user = User::where('email', $request->email)->first();

        // Same response whether the user exists or not - avoids leaking
        // which emails are registered in the system.
        if (!$user) {
            return back()->with('success', $successMessage);
        }

        $token = FacadesPassword::createToken($user);

        $resetLink = route('frontend.password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-Key' => config('services.mailer.key'),
                ])
                ->post(config('services.mailer.url'), [
                    'type'    => 'verification',
                    'to'      => $user->email,
                    'subject' => 'Reset Your Password',
                    'data'    => [
                        'eyebrow'           => 'Password Reset',
                        'heading'           => 'Reset your password',
                        'name'              => $user->name,
                        'message'           => 'We received a request to reset your password. Click the button below to choose a new one. This link is valid for a limited time and can only be used once.',
                        'button_text'       => 'Reset Password',
                        'verification_link' => $resetLink,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Mailer API failed for password reset: ' . $response->body());
            }
        } catch (\Throwable $e) {
            // Mailer service unreachable/timeout - don't break the user flow,
            // just log it so it can be investigated.
            Log::error('Mailer API exception for password reset: ' . $e->getMessage());
        }

        return back()->with('success', $successMessage);
    }

    /**
     * Reset password form দেখাও
     */
    public function showResetForm(Request $request, $token)
    {
        return view('frontend.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Password reset koro
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = FacadesPassword::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === FacadesPassword::PASSWORD_RESET) {
            return redirect()->route('company.login')->with('success', 'Your password has been reset successfully. Please sign in.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
