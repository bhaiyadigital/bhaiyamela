<?php

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Content;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\JobApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/cc', function () {
    Artisan::call('optimize:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "✅ All caches cleared!";
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // ------------------------------------------------------------------
    // Users
    // ------------------------------------------------------------------
    Route::prefix('users')->name('users.')->middleware('permission:roles.view')->group(function () {
        Route::get('/',          [UserController::class, 'index'])->name('index');
        Route::get('/create',    [UserController::class, 'create'])->middleware('permission:roles.create')->name('create');
        Route::post('/',         [UserController::class, 'store'])->middleware('permission:roles.create')->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->middleware('permission:roles.edit')->name('edit');
        Route::put('/{id}',      [UserController::class, 'update'])->middleware('permission:roles.edit')->name('update');
        Route::delete('/{id}',   [UserController::class, 'destroy'])->middleware('permission:roles.delete')->name('destroy');
    });
    Route::get('/subscriptions', [UserController::class, 'subscriberList'])->name('subscriptions.index');
    Route::delete('/subscriptions/{id}', [UserController::class, 'destroySubscirber'])->name('subscriptions.destroy');
    Route::get('/customers', [UserController::class, 'customersIndex'])->name('customers.index');
    Route::post('/customers/{id}/toggle-status', [UserController::class, 'toggleCustomerStatus'])->name('customers.toggle-status');

    Route::get('/tickets', [SupportTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [SupportTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [SupportTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [SupportTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/close', [SupportTicketController::class, 'close'])->name('tickets.close');
    // ------------------------------------------------------------------
    // Roles
    // ------------------------------------------------------------------
    Route::prefix('roles')->name('roles.')->middleware('permission:roles.view')->group(function () {
        Route::get('/',                        [RoleController::class, 'index'])->name('index');
        Route::get('/create',                  [RoleController::class, 'create'])->middleware('permission:roles.create')->name('create');
        Route::post('/',                       [RoleController::class, 'store'])->middleware('permission:roles.create')->name('store');
        Route::get('/{id}/edit',               [RoleController::class, 'edit'])->middleware('permission:roles.edit')->name('edit');
        Route::put('/{id}',                    [RoleController::class, 'update'])->middleware('permission:roles.edit')->name('update');
        Route::delete('/{id}',                 [RoleController::class, 'destroy'])->middleware('permission:roles.delete')->name('destroy');
        Route::post('/{id}/toggle-permission', [RoleController::class, 'togglePermission'])->middleware('permission:roles.edit')->name('toggle-permission');
    });
    Route::prefix('settings')->name('settings.')->middleware('permission:settings.view')->group(function () {
        Route::get('/',    [SettingController::class, 'index'])->name('index');
        Route::put('/',    [SettingController::class, 'update'])->middleware('permission:settings.edit')->name('update');
    });

    Route::get('companies', [CompanyController::class, 'companyList'])->name('companies.index');
    Route::get('company/profile', [CompanyController::class, 'myProfile'])->name('companies.profile');

    Route::get('companies/{company}', [CompanyController::class, 'show'])->name('companies.show');

    Route::get('/company-profile', [CompanyController::class, 'edit'])->name('company-profile.edit');
    Route::put('/company-profile', [CompanyController::class, 'update'])->name('company-profile.update');

    Route::get('/company-profile/{id}', [CompanyController::class, 'edit'])->name('company-profile.admin.edit');
    Route::put('/company-profile/{id}', [CompanyController::class, 'update'])->name('company-profile.admin.update');
    Route::post('/companies/{id}/toggle-status', [CompanyController::class, 'toggleCompanyStatus'])
        ->name('companies.toggle-status');
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts/{id}/mark-read', [ContactController::class, 'markRead'])->name('contacts.mark-read');
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    Route::get('/change-password', [CompanyController::class, 'changePassword'])->name('password.edit');
    Route::put('/change-password', [CompanyController::class, 'updatePassword'])->name('password.update');
    Route::get('/requirements', [RequirementController::class, 'index'])->name('requirements.index');
    Route::post('/requirements/{id}/update-status', [RequirementController::class, 'updateStatus'])->name('requirements.update-status');

    // ------------------------------------------------------------------
    // Contents
    // ------------------------------------------------------------------
    Route::prefix('contents/{module}')->name('contents.')->group(function () {

        Route::post('/bulk',                    [ContentController::class, 'bulk'])->middleware('permission:{module}.delete')->name('bulk');

        Route::get('/',                         [ContentController::class, 'index'])->middleware('permission:{module}.view')->name('index');

        // create permission required
        Route::get('/create',                   [ContentController::class, 'create'])->middleware('permission:{module}.create')->name('create');
        Route::post('/',                        [ContentController::class, 'store'])->middleware('permission:{module}.create')->name('store');
        Route::post('/upload-image',            [ContentController::class, 'uploadImage'])->middleware('permission:{module}.create')->name('upload-image');

        // edit permission required
        Route::get('/{id}/edit',                [ContentController::class, 'edit'])->middleware('permission:{module}.edit')->name('edit');
        Route::put('/{id}',                     [ContentController::class, 'update'])->middleware('permission:{module}.edit')->name('update');
        Route::patch('/{id}/restore',           [ContentController::class, 'restore'])->middleware('permission:{module}.edit')->name('restore');
        Route::patch('/{id}/toggle-status',     [ContentController::class, 'toggleStatus'])->middleware('permission:{module}.edit')->name('toggle-status');
        Route::post('/reorder',                 [ContentController::class, 'reorder'])->middleware('permission:{module}.edit')->name('reorder');
        Route::delete('/{id}/remove-image',     [ContentController::class, 'removeImage'])->middleware('permission:{module}.edit')->name('remove-image');

        // delete permission required
        Route::patch('/{id}/trash',             [ContentController::class, 'trash'])->middleware('permission:{module}.delete')->name('trash');
        Route::delete('/{id}',                  [ContentController::class, 'destroy'])->middleware('permission:{module}.delete')->name('destroy');
        Route::post('/{id}/toggle-admin-approval', [ContentController::class, 'toggleAdminApproval'])
            ->name('admin.contents.toggle-admin-approval');
    });
    Route::post('contents/generate-slug', [ContentController::class, 'generateSlug'])->name('contents.generate-slug');
});

Route::get('/', [WebController::class, 'home'])->name('web.home');
Route::post('/contact-store', [WebController::class, 'contactStore'])->name('contact.store');
Route::get('/properties', [WebController::class, 'project'])->name('web.project');
Route::get('/properties/{slug}', [WebController::class, 'projectCat'])->name('web.project.category');
Route::get('/gallery', [WebController::class, 'gallery'])->name('web.gallery');
Route::get('/property/{slug}', [WebController::class, 'details'])->name('project.details');
Route::get('/blog', [WebController::class, 'blog'])->name('web.blog');
Route::get('/blog/{slug}', [WebController::class, 'blogDetails'])->name('web.blog.details');
Route::get('/page/{slug}', [WebController::class, 'pageDetails'])->name('web.page');
Route::post('/subscription', [WebController::class, 'subscriptionStore'])->name('web.subscription');
Route::get('/increment-video-view/{id}', function ($id) {
    Content::where('id', $id)->increment('views');
    return response()->json(['success' => true]);
});

Route::get('/user-login', [CompanyController::class, 'LoginPage'])->name('company.login');
Route::post('/user-login', [CompanyController::class, 'companyLogin'])->name('company.login');
Route::get('/user-registration', [CompanyController::class, 'companyRegistration'])->name('company.registration');
Route::post('/store/registration', [CompanyController::class, 'register'])->name('store.registration');
Route::post('/contact-owner', [ContactController::class, 'storeLead'])->name('contact.owner');
Route::get('/developers', [CompanyController::class, 'index'])->name('developers.index');
Route::get('/post-requirement', [RequirementController::class, 'create'])->name('requirements.create');
Route::post('/post-requirement', [RequirementController::class, 'store'])->name('requirements.store');
Route::get('/compare', [WebController::class, 'compare'])->name('projects.compare');
Route::get('/faq', [WebController::class, 'faq'])->name('faq.index');
Route::get('/loan-partners', [WebController::class, 'loanPartners'])->name('loans.index');
Route::get('/area-guides', [WebController::class, 'areaGuides'])->name('area-guides.index');
Route::get('/area-guides/{slug}', [WebController::class, 'areaGuideDetails'])->name('area-guides.show');

Route::get('/forgot-password', [CompanyController::class, 'showLinkRequestForm'])->name('frontend.password.request');
Route::post('/forgot-password', [CompanyController::class, 'sendResetLinkEmail'])->name('frontend.password.email');
Route::get('/reset-password/{token}', [CompanyController::class, 'showResetForm'])->name('frontend.password.reset');
Route::post('/reset-password', [CompanyController::class, 'reset'])->name('frontend.password.update');

Route::post('/toggle-favorite', [FavoriteController::class, 'toggleFavorite'])->name('projects.favorite.toggle');
Route::get('/about', [WebController::class, 'about'])->name('about.index');
Route::get('/contact', [WebController::class, 'contactForm'])->name('web.contact');
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.resend');
});

Route::get('/email/verify/{id}', [EmailVerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserProfileController::class, 'dashboard'])->name('dashboard');
    Route::put('/profile/update', [UserProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password/update', [UserProfileController::class, 'updatePassword'])->name('password.update');
});
Route::get('/developer/guide', [CompanyController::class, 'guide'])
    ->middleware('auth')
    ->name('developer.guide');
