@extends('layouts.backend')
@section('title', 'Company Profile Dashboard')
@section('content')
<div class="container-fluid px-3 px-md-4 py-4" style="font-family: 'Outfit', sans-serif;">

    <!-- Header & Actions -->
    <div class="row align-items-center justify-content-between mb-4 g-3 border-bottom pb-4 border-light">
        <div class="col-12 col-md-auto">
            <h1 class="h3 mb-1 text-slate-900 fw-bold">Company Hub</h1>
            <p class="text-muted small mb-0">Unified analytics, listing activity, and lead performance management.</p>
        </div>
        <div class="col-12 col-md-auto d-flex gap-2">
     
            @if(auth()->user()->hasRole('super-admin'))
            <a href="{{ route('admin.company-profile.admin.edit', $company->id) }}" class="btn btn-accent-success btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3 text-white transition-all">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </a>
            @else
            <a href="{{ route('admin.company-profile.edit') }}" class="btn btn-accent-success btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold rounded-3 text-white transition-all">
                <i class="bi bi-pencil-square"></i> Edit Profile
            </a>
            @endif
        </div>
    </div>

    <!-- Main Identity Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden position-relative analytics-card">
        <div class="position-absolute top-0 start-0 w-100" style="height: 100px; background: linear-gradient(90deg, #f1f5f9 0%, #e2e8f0 100%); border-bottom: 1px solid #e2e8f0;"></div>
        
        <div class="card-body p-4 position-relative" style="margin-top: 45px;">
            <div class="row align-items-center g-4">
                <!-- Logo -->
                <div class="col-auto">
                    <div class="bg-white border rounded-4 d-flex align-items-center justify-content-center overflow-hidden shadow-sm" style="width: 100px; height: 100px; border-color: #e2e8f0 !important;">
                        @if($company->company_logo)
                        <img src="{{ asset('storage/'.$company->company_logo) }}" alt="{{ $company->company_name }}" class="img-fluid p-2" style="max-height: 100%; object-fit: contain;">
                        @else
                        <i class="bi bi-building text-secondary fs-1"></i>
                        @endif
                    </div>
                </div>

                <!-- Info Block -->
                <div class="col text-center text-md-start">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-2 mb-2 justify-content-center justify-content-md-start">
                        <h2 class="h4 mb-0 fw-bold text-slate-900">{{ $company->company_name }}</h2>
                        @if(($company->status ?? 0) == 1)
                        <span class="badge bg-emerald-soft border border-emerald-soft px-3 py-1 rounded-pill small">Active</span>
                        @else
                        <span class="badge bg-rose-soft border border-rose-soft px-3 py-1 rounded-pill small">Awaiting Verification</span>
                        @endif
                    </div>
                    @if($company->tagline)
                    <p class="text-muted mb-3 small fw-normal">{{ $company->tagline }}</p>
                    @endif

                    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-3 text-secondary small pt-3 border-top border-light">
                        <span class="d-flex align-items-center gap-1.5"><i class="bi bi-envelope text-muted"></i>{{ $company->email }}</span>
                        <span class="d-flex align-items-center gap-1.5"><i class="bi bi-telephone text-muted"></i>{{ $company->phone }}</span>
                        @if($company->whatsapp)
                        <span class="d-flex align-items-center gap-1.5"><i class="bi bi-whatsapp text-success fw-bold"></i>{{ $company->whatsapp }}</span>
                        @endif
                        @if($company->website_url)
                        <span class="d-flex align-items-center gap-1.5"><i class="bi bi-globe text-muted"></i><a href="{{ $company->website_url }}" target="_blank" class="text-decoration-none text-secondary hover-primary">{{ $company->website_url }}</a></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Dashboard Overview -->
    <div class="row g-4 mb-4">
        
        <!-- Metric Widget 1: Total Projects -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 analytics-card">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted text-uppercase small fw-bold tracking-wider fs-7">Active Listings</span>
                        <h3 class="h2 mb-0 fw-bold mt-1 text-slate-900">{{ $totalProjects }}</h3>
                    </div>
                    <div class="bg-emerald-soft p-3 rounded-4">
                        <i class="bi bi-kanban fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Widget 2: Total Blogs -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 analytics-card">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted text-uppercase small fw-bold tracking-wider fs-7">Articles & Blogs</span>
                        <h3 class="h2 mb-0 fw-bold mt-1 text-slate-900">{{ $totalBlogs }}</h3>
                    </div>
                    <div class="bg-sky-soft p-3 rounded-4">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Widget 3: Live Leads Received -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 analytics-card">
                <div class="card-body d-flex align-items-center justify-content-between p-4">
                    <div>
                        <span class="text-muted text-uppercase small fw-bold tracking-wider fs-7">Leads Inquiries</span>
                        <h3 class="h2 mb-0 fw-bold mt-1 text-slate-900">{{ $recentLeads->count() }}</h3>
                    </div>
                    <div class="bg-amber-soft p-3 rounded-4">
                        <i class="bi bi-envelope-heart fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- NEW Metric Widget 4: Profile Completeness -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 analytics-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted text-uppercase small fw-bold tracking-wider fs-7">Completeness Score</span>
                        <span class="text-emerald small fw-bold">{{ $profileCompleteness }}%</span>
                    </div>
                    <div class="progress mb-2" style="height: 8px; border-radius: 4px; background-color: #f1f5f9;">
                        <div class="progress-bar bg-success" style="width: {{ $profileCompleteness }}%;"></div>
                    </div>
                    <span class="text-muted" style="font-size: 11px;">Fill legal documents & address to reach 100%.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Layout Area -->
    <div class="row g-4 align-items-start">
        
        <!-- LEFT PANEL (Main Performance Feed) -->
        <div class="col-12 col-lg-8">
            
            <!-- CRM Live Leads Panel (Recent Inquiries) -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 analytics-card">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h4 class="card-title h6 mb-0 fw-bold text-slate-900"><i class="bi bi-chat-left-quote text-emerald me-2"></i>Live Lead Activity</h4>
                    <span class="badge bg-amber-soft border border-amber-soft px-3 py-1.5 rounded-pill small">Inbound</span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-3">
                        @forelse($recentLeads as $lead)
                            <div class="p-3 rounded-3 border border-light d-flex justify-content-between align-items-start gap-3 bg-light bg-opacity-10">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 13.5px;">{{ $lead->name }}</h6>
                                    <p class="text-muted mb-1" style="font-size: 12px;"><i class="bi bi-envelope me-1"></i>{{ $lead->email }} | <i class="bi bi-phone me-1"></i>{{ $lead->phone }}</p>
                                    @if($lead->message)
                                        <blockquote class="text-secondary mb-0 bg-light p-2 rounded mt-2 border-start border-emerald border-3" style="font-size: 11.5px; font-style: italic;">
                                            "{{ Str::limit($lead->message, 120) }}"
                                        </blockquote>
                                    @endif
                                </div>
                                <span class="text-muted small" style="font-size: 11px;">{{ $lead->created_at ? $lead->created_at->diffForHumans() : 'N/A' }}</span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-envelope-open fs-3 mb-2 d-block text-secondary opacity-50"></i>
                                <span class="small">No buyer inquiries received yet.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- About Company -->
            @if($company->about_us)
            <div class="card border-0 shadow-sm rounded-4 mb-4 analytics-card">
                <div class="card-body p-4">
                    <h4 class="card-title h6 mb-3 fw-bold text-slate-900 border-bottom pb-3">
                        <i class="bi bi-file-earmark-person text-emerald me-2"></i>About Us
                    </h4>
                    <p class="text-secondary small leading-relaxed mb-0">{!! nl2br(e($company->about_us)) !!}</p>
                </div>
            </div>
            @endif

            <!-- Map View -->
            @if($company->maps_embed)
            <div class="card border-0 shadow-sm rounded-4 mb-4 analytics-card">
                <div class="card-body p-4">
                    <h4 class="card-title h6 mb-3 fw-bold text-slate-900 border-bottom pb-3">
                        <i class="bi bi-geo-alt text-emerald me-2"></i>Location Map
                    </h4>
                    <div class="ratio ratio-21x9 rounded-4 overflow-hidden border border-light" style="min-height: 250px;">
                        {!! $company->maps_embed !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Listings Table -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden analytics-card">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h4 class="card-title h6 mb-0 fw-bold text-slate-900">Latest 5 Projects</h4>
                    <span class="badge bg-emerald-soft border border-emerald-soft px-3 py-1.5 rounded-pill small">Recent Projects</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 13.5px;">
                        <thead class="bg-light-subtle text-secondary text-uppercase small">
                            <tr>
                                <th class="ps-4 py-3">Image</th>
                                <th class="py-3">Title</th>
                                <th class="py-3">Uploaded Date</th>
                                <th class="text-center pe-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($latestProjects as $project)
                            <tr>
                                <td class="ps-4 py-3">
                                    @if($project->img_path)
                                    <img src="{{ asset('storage/' . $project->img_path) }}"
                                        class="rounded-3 border border-light"
                                        alt="{{ $project->title }}"
                                        style="width: 64px; height: 64px; object-fit: cover; object-position: center;">
                                    @else
                                    <div class="bg-light rounded-3 border border-light d-flex align-items-center justify-content-center"
                                        style="width: 64px; height: 64px;">
                                        <i class="bi bi-image text-muted fs-4"></i>
                                    </div>
                                    @endif
                                </td>
                                <td class="py-3 fw-semibold text-slate-900 text-truncate" style="max-width: 280px;">
                                    {{ $project->title }}
                                </td>
                                <td class="py-3 text-muted">
                                    {{ $project->created_at ? $project->created_at->format('d M, Y') : 'N/A' }}
                                </td>
                                <td class="py-3 text-center pe-4">
                                    <a href="{{ route('project.details', $project->slug) }}" class="btn btn-link btn-sm text-decoration-none fw-bold text-emerald px-0 hover-underlined">
                                        View Details <i class="bi bi-arrow-right small ms-0.5"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-open fs-2 mb-2 text-secondary opacity-50 d-block"></i>
                                    <span class="small">No recent projects found</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL (Company Assets) -->
        <div class="col-12 col-lg-4">

            <!-- Progress Moderation Analysis Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 analytics-card">
                <div class="card-body p-4">
                    <h4 class="h6 fw-bold text-slate-900 border-bottom pb-3 mb-3">
                        <i class="bi bi-pie-chart text-emerald me-2"></i>Catalog Diagnostics
                    </h4>
                    
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span class="small text-secondary"><i class="bi bi-check-circle-fill text-success me-1.5"></i> Approved Listings</span>
                        <span class="fw-bold small text-dark">{{ $approvedProjects }}</span>
                    </div>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span class="small text-secondary"><i class="bi bi-hourglass-split text-warning me-1.5"></i> Under Moderation</span>
                        <span class="fw-bold small text-dark">{{ $pendingProjects }}</span>
                    </div>

                    <div class="progress" style="height: 8px; border-radius: 4px; background-color: #f1f5f9;">
                        @php
                            $totalLocal = $approvedProjects + $pendingProjects;
                            $approvedPercentage = $totalLocal > 0 ? ($approvedProjects / $totalLocal) * 100 : 0;
                            $pendingPercentage = $totalLocal > 0 ? ($pendingProjects / $totalLocal) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ $approvedPercentage }}%;"></div>
                        <div class="progress-bar bg-warning" style="width: {{ $pendingPercentage }}%;"></div>
                    </div>
                </div>
            </div>

            <!-- Profile Info Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 analytics-card">
                <div class="card-body p-4">
                    <h4 class="h6 fw-bold text-slate-900 border-bottom pb-3 mb-3">
                        <i class="bi bi-info-circle text-emerald me-2"></i>Company Details
                    </h4>
                    <div class="d-flex flex-column gap-3" style="font-size: 13.5px;">
                        @if($company->founder_name)
                        <div>
                            <span class="text-muted text-uppercase d-block small fw-bold tracking-wider fs-7">Founder</span>
                            <p class="mb-0 fw-semibold text-slate-900 mt-1">{{ $company->founder_name }}</p>
                        </div>
                        @endif

                        @if($company->foundation_date)
                        <div>
                            <span class="text-muted text-uppercase d-block small fw-bold tracking-wider fs-7">Foundation Date</span>
                            <p class="mb-0 fw-semibold text-slate-900 mt-1">
                                {{ \Carbon\Carbon::parse($company->foundation_date)->format('d F, Y') }}
                            </p>
                        </div>
                        @endif

                        @if($company->industry)
                        <div>
                            <span class="text-muted text-uppercase d-block small fw-bold tracking-wider fs-7">Industry</span>
                            <p class="mb-0 fw-semibold text-slate-900 mt-1">{{ $company->industry }}</p>
                        </div>
                        @endif

                        @if($company->address)
                        <div class="border-top pt-3 mt-1">
                            <span class="text-muted text-uppercase d-block small fw-bold tracking-wider fs-7">Address</span>
                            <p class="text-secondary mb-0 small leading-relaxed mt-1">{{ $company->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Legal Info Card -->
            @if($company->trade_license || $company->tin_id)
            <div class="card border-0 shadow-sm rounded-4 mb-4 analytics-card">
                <div class="card-body p-4">
                    <h4 class="h6 fw-bold text-slate-900 border-bottom pb-3 mb-3">
                        <i class="bi bi-patch-check text-emerald me-2"></i>Verification & Licenses
                    </h4>
                    <div class="d-flex flex-column gap-3" style="font-size: 13.5px;">
                        @if($company->trade_license)
                        <div>
                            <span class="text-muted text-uppercase d-block small fw-bold fs-7">Trade License ID</span>
                            <p class="mb-0 fw-semibold text-slate-900 mt-1">
                                <i class="bi bi-file-earmark-ruled text-secondary me-1.5"></i> {{ $company->trade_license }}
                            </p>
                        </div>
                        @endif

                        @if($company->tin_id)
                        <div>
                            <span class="text-muted text-uppercase d-block small fw-bold fs-7">TIN Certificate ID</span>
                            <p class="mb-0 fw-semibold text-slate-900 mt-1">
                                <i class="bi bi-shield-lock text-secondary me-1.5"></i> {{ $company->tin_id }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Social Links Panel -->
            @if($company->social_links)
                @php
                    $socialLinks = is_array($company->social_links)
                        ? $company->social_links
                        : json_decode($company->social_links, true);
                @endphp

                @if(!empty($socialLinks) && count(array_filter($socialLinks)) > 0)
                <div class="card border-0 shadow-sm rounded-4 mb-4 analytics-card">
                    <div class="card-body p-4">
                        <h4 class="h6 fw-bold text-slate-900 border-bottom pb-3 mb-3">
                            <i class="bi bi-people text-emerald me-2"></i>Connect on Socials
                        </h4>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($socialLinks as $platform => $url)
                                @if($url)
                                    @php
                                        $iconClass = 'bi bi-globe';
                                        $btnClass = 'btn-social-default';
                                        $platformLower = strtolower($platform);

                                        if ($platformLower === 'facebook') {
                                            $iconClass = 'bi bi-facebook';
                                            $btnClass = 'btn-social-fb';
                                        }
                                        elseif ($platformLower === 'twitter' || $platformLower === 'x') {
                                            $iconClass = 'bi bi-twitter-x';
                                            $btnClass = 'btn-social-tw';
                                        }
                                        elseif ($platformLower === 'instagram') {
                                            $iconClass = 'bi bi-instagram';
                                            $btnClass = 'btn-social-ig';
                                        }
                                        elseif ($platformLower === 'linkedin') {
                                            $iconClass = 'bi bi-linkedin';
                                            $btnClass = 'btn-social-ln';
                                        }
                                        elseif ($platformLower === 'youtube') {
                                            $iconClass = 'bi bi-youtube';
                                            $btnClass = 'btn-social-yt';
                                        }
                                    @endphp
                                    <a href="{{ $url }}" target="_blank" class="btn {{ $btnClass }} d-inline-flex align-items-center justify-content-center rounded-3 shadow-sm transition-all" style="width: 38px; height: 38px;" title="{{ ucfirst($platform) }}">
                                        <i class="{{ $iconClass }} fs-5"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            @endif

        </div>
    </div>
</div>

<style scoped>
    #main {
        background: #f8fafc !important;
    }

    .btn-accent-success {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
        transition: all 0.2s ease;
    }
    .btn-accent-success:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
        color: #ffffff !important;
    }

    .hover-underlined:hover {
        text-decoration: underline !important;
    }

    .transition-all {
        transition: all 0.25s ease-in-out !important;
    }

    .card.analytics-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card.analytics-card:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08) !important;
    }

    .bg-emerald-soft { background: #ecfdf5; color: #059669; }
    .bg-sky-soft { background: #f0f9ff; color: #0284c7; }
    .bg-rose-soft { background: #fff1f2; color: #e11d48; }
    .bg-amber-soft { background: #fffbeb; color: #d97706; }

    .action-pill {
        background: #ffffff;
        border: 1px solid #e2e8f0 !important;
        color: #475569;
    }
    .action-pill:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .fs-7 {
        font-size: 0.725rem !important;
    }

    /* Social icons customized button behaviors */
    .btn-social-default { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .btn-social-default:hover { background-color: #cbd5e1; color: #1e293b; }

    .btn-social-fb { background-color: #f0f9ff; color: #0369a1; border: 1px solid #e0f2fe; }
    .btn-social-fb:hover { background-color: #0284c7; color: #ffffff; }

    .btn-social-tw { background-color: #f8fafc; color: #0f172a; border: 1px solid #e2e8f0; }
    .btn-social-tw:hover { background-color: #0f172a; color: #ffffff; }

    .btn-social-ig { background-color: #fff1f2; color: #db2777; border: 1px solid #ffe4e6; }
    .btn-social-ig:hover { background-color: #db2777; color: #ffffff; }

    .btn-social-ln { background-color: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
    .btn-social-ln:hover { background-color: #16a34a; color: #ffffff; }

    .btn-social-yt { background-color: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }
    .btn-social-yt:hover { background-color: #dc2626; color: #ffffff; }

    .gap-1.5 {
        gap: 0.375rem !important;
    }
</style>
@endsection
