@extends('layouts.backend')

@section('content')
<style>
    #main {
        background: #f8fafc !important;
    }
    
    .dashboard-container {
        background: #f8fafc;
        min-height: 100vh;
        color: #1e293b;
        font-family: 'Outfit', sans-serif;
    }

    .card.analytics-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card.analytics-card:hover {
        transform: translateY(-2px);
        border-color: #cbd5e1 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08) !important;
    }

    .text-emerald { color: #059669 !important; }
    .bg-emerald-soft { background: #ecfdf5; color: #059669; }
    
    .text-sky { color: #0284c7 !important; }
    .bg-sky-soft { background: #f0f9ff; color: #0284c7; }
    
    .text-amber { color: #d97706 !important; }
    .bg-amber-soft { background: #fffbeb; color: #d97706; }

    .text-rose { color: #e11d48 !important; }
    .bg-rose-soft { background: #fff1f2; color: #e11d48; }

    .action-pill {
        background: #ffffff;
        border: 1px solid #e2e8f0 !important;
        color: #475569;
        transition: all 0.2s ease;
    }

    .action-pill:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1 !important;
    }

    .btn-accent-success {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
    }
    .btn-accent-success:hover {
        background-color: #059669 !important;
    }

    .tracking-wider-sm {
        letter-spacing: 0.05em;
        font-size: 0.725rem !important;
    }

    /* custom filter styling */
    .filter-select, .filter-input {
        font-size: 13.5px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 6px 12px;
        color: #334155;
    }
    .filter-select:focus, .filter-input:focus {
        border-color: #10b981;
        outline: none;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }
</style>

<div class="dashboard-container container-fluid py-5">
    
    <!-- Header Block with Dynamic Filters -->
    <div class="row align-items-center justify-content-between mb-4 g-4 border-bottom pb-4 border-light">
        <div class="col-12 col-md-auto">
            @if(Auth::check())
                <h2 class="fw-bold mb-1 text-slate-900">Console Overview</h2>
                <p class="text-muted small mb-0">Welcome back, <span class="text-dark fw-semibold">{{ Auth::user()->name }}</span>. Current scope: <strong>{{ ucfirst(str_replace('_', ' ', $currentFilter)) }}</strong></p>
            @endif
        </div>
        
        <!-- Filter Controls Form -->
        <div class="col-12 col-md-auto">
            <form action="{{ url()->current() }}" method="GET" class="d-flex flex-wrap align-items-center gap-2">
                <select name="filter" id="filterDropdown" class="filter-select" onchange="toggleCustomDates(this.value)">
                    <option value="all" {{ $currentFilter == 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="today" {{ $currentFilter == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="yesterday" {{ $currentFilter == 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                    <option value="last_7" {{ $currentFilter == 'last_7' ? 'selected' : '' }}>Last 7 Days</option>
                    <option value="this_month" {{ $currentFilter == 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="custom" {{ $currentFilter == 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>

                <div id="customDateContainer" class="d-flex align-items-center gap-2" style="display: {{ $currentFilter == 'custom' ? 'flex' : 'none' }} !important;">
                    <input type="date" name="start_date" value="{{ $startDateVal }}" class="filter-input">
                    <span class="text-muted small">to</span>
                    <input type="date" name="end_date" value="{{ $endDateVal }}" class="filter-input">
                </div>

                <button type="submit" class="btn btn-accent-success btn-sm px-3 rounded-2 py-1.5 fw-semibold border-0">
                    <i class="bi bi-funnel"></i> Apply Filter
                </button>
            </form>
        </div>
    </div>

    @auth
        <div class="row justify-content-center g-4">
            <div class="col-12 col-xl-11">

                {{-- ── A. SUPER ADMIN SECTION ── --}}
                @if(auth()->user()->hasRole('super-admin'))
                    
                    <!-- KPI Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="card analytics-card p-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small text-uppercase fw-bold tracking-wider-sm">Total Projects</span>
                                    <div class="p-2 rounded-3 bg-emerald-soft"><i class="bi bi-building fs-5"></i></div>
                                </div>
                                <h3 class="fw-bold text-slate-900 mb-1">{{ $totalProjects }}</h3>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="text-emerald small fw-semibold"><i class="bi bi-graph-up me-1"></i>Active</span>
                                    <span class="text-muted small">catalog items</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="card analytics-card p-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small text-uppercase fw-bold tracking-wider-sm">Developers</span>
                                    <div class="p-2 rounded-3 bg-sky-soft"><i class="bi bi-people fs-5"></i></div>
                                </div>
                                <h3 class="fw-bold text-slate-900 mb-1">{{ $totalDevelopers }}</h3>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="text-sky small fw-semibold"><i class="bi bi-person-check me-1"></i>Registered</span>
                                    <span class="text-muted small">partners</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="card analytics-card p-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small text-uppercase fw-bold tracking-wider-sm">Requirements</span>
                                    <div class="p-2 rounded-3 bg-amber-soft"><i class="bi bi-journal-text fs-5"></i></div>
                                </div>
                                <h3 class="fw-bold text-slate-900 mb-1">{{ $totalRequirements }}</h3>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="text-amber small fw-semibold"><i class="bi bi-arrow-repeat me-1"></i>Total</span>
                                    <span class="text-muted small">buyer queries</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="card analytics-card p-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small text-uppercase fw-bold tracking-wider-sm">Subscribers</span>
                                    <div class="p-2 rounded-3 bg-rose-soft"><i class="bi bi-envelope fs-5"></i></div>
                                </div>
                                <h3 class="fw-bold text-slate-900 mb-1">{{ $totalSubscribers }}</h3>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="text-rose small fw-semibold"><i class="bi bi-mailbox me-1"></i>Audience</span>
                                    <span class="text-muted small">subscribers</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart & Insights -->
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-lg-8">
                            <div class="card analytics-card p-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-light">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">System Growth Trend Chart</h6>
                                        <p class="text-muted small mb-0">Metrics timeline visualizing project uploads & registered companies.</p>
                                    </div>
                                </div>
                                <div style="height: 280px; position: relative;">
                                    <canvas id="performanceChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="card analytics-card p-4 h-100">
                                <h6 class="fw-bold text-dark border-bottom border-light pb-3 mb-3">Verification Targets</h6>
                                
                                <div class="mb-4">
                                    @php
                                        $approvedProjectsPercent = $totalProjects > 0 ? (($totalProjects - $pendingProjects) / $totalProjects) * 100 : 0;
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="text-secondary small fw-medium">Project Approval Rate</span>
                                        <span class="text-dark small fw-bold">{{ round($approvedProjectsPercent, 1) }}%</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 4px; background-color: #f1f5f9;">
                                        <div class="progress-bar bg-success" style="width: {{ $approvedProjectsPercent }}%;"></div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    @php
                                        $approvedDevsPercent = $totalDevelopers > 0 ? (($totalDevelopers - $pendingDevelopers) / $totalDevelopers) * 100 : 0;
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="text-secondary small fw-medium">Verified Developers</span>
                                        <span class="text-dark small fw-bold">{{ round($approvedDevsPercent, 1) }}%</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 4px; background-color: #f1f5f9;">
                                        <div class="progress-bar bg-info" style="width: {{ $approvedDevsPercent }}%;"></div>
                                    </div>
                                </div>

                                <div class="p-3 rounded-4 mt-2" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="bi bi-shield-alert text-warning mt-0.5"></i>
                                        <div>
                                            <span class="text-dark small fw-semibold d-block">Pending Action Logs</span>
                                            <span class="text-muted d-block mt-1" style="font-size: .75rem; line-height: 1.4;">
                                                There are <strong class="text-warning">{{ $pendingProjects }} pending projects</strong> and <strong class="text-danger">{{ $pendingDevelopers }} developer requests</strong> waiting inside the Action Center.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Center Links -->
                    <div class="card analytics-card p-4 text-start">
                        <h6 class="fw-bold text-dark border-bottom border-light pb-3 mb-3 text-uppercase tracking-wider" style="font-size: .8rem;">
                            <i class="bi bi-shield-check me-2 text-warning"></i>Action Center Shortcut
                        </h6>
                        <div class="d-flex flex-wrap gap-2.5">
                            <a href="{{ route('admin.contents.index', 'project') }}?status=0" class="btn action-pill btn-sm px-3 py-2 rounded-3 fw-semibold">
                                Pending Projects: <span class="text-dark fw-bold">{{ $pendingProjects }}</span>
                            </a>
                            <a href="{{ route('admin.companies.index') }}?status=0" class="btn action-pill btn-sm px-3 py-2 rounded-3 fw-semibold">
                                Pending Developers: <span class="text-dark fw-bold">{{ $pendingDevelopers }}</span>
                            </a>
                            <a href="{{ route('admin.customers.index') }}" class="btn action-pill btn-sm px-3 py-2 rounded-3 fw-semibold">
                                Customers: <span class="text-dark fw-bold">{{ $registeredCustomers }}</span>
                            </a>
                        </div>
                    </div>

                {{-- ── B. DEVELOPER SECTION ── --}}
                @elseif(auth()->user()->isCompany())
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-4">
                            <div class="card analytics-card p-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small text-uppercase fw-bold tracking-wider-sm">Active Projects</span>
                                    <div class="p-2 rounded-3 bg-emerald-soft"><i class="bi bi-building-check fs-5"></i></div>
                                </div>
                                <h3 class="fw-bold text-dark mb-1">{{ $activeProjects }}</h3>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="text-emerald small fw-semibold">Published</span>
                                    <span class="text-muted small">listings</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="card analytics-card p-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small text-uppercase fw-bold tracking-wider-sm">Pending Review</span>
                                    <div class="p-2 rounded-3 bg-amber-soft"><i class="bi bi-hourglass-split fs-5"></i></div>
                                </div>
                                <h3 class="fw-bold text-dark mb-1">{{ $pendingReview }}</h3>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="text-amber small fw-semibold">Awaiting</span>
                                    <span class="text-muted small">moderator checking</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="card analytics-card p-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted small text-uppercase fw-bold tracking-wider-sm">Received Leads</span>
                                    <div class="p-2 rounded-3 bg-sky-soft"><i class="bi bi-envelope-open fs-5"></i></div>
                                </div>
                                <h3 class="fw-bold text-dark mb-1">{{ $myLeads }}</h3>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <span class="text-sky small fw-semibold">Customer leads</span>
                                    <span class="text-muted small">received</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Developer Performance Chart -->
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-lg-8">
                            <div class="card analytics-card p-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-light">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">Project & Inquiries Trend Map</h6>
                                        <p class="text-muted small mb-0">Timeline analyzing customer engagement metrics.</p>
                                    </div>
                                </div>
                                <div style="height: 280px; position: relative;">
                                    <canvas id="performanceChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="card analytics-card p-4 h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-dark border-bottom border-light pb-3 mb-3">Quick Control Panel</h6>
                                    <p class="text-muted small" style="line-height: 1.5;">
                                        Add new project cards, edit your public developer profile, or look through the developer documentation.
                                    </p>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-3">
                                    <a href="{{ route('admin.contents.create', 'project') }}" class="btn btn-accent-success btn-sm w-100 py-2.5 rounded-3 fw-bold">
                                        <i class="bi bi-plus-circle me-1.5"></i> Add New Project
                                    </a>
                                    <a href="{{ route('admin.company-profile.edit') }}" class="btn action-pill btn-sm w-100 py-2.5 rounded-3 fw-semibold text-start px-3">
                                        <i class="bi bi-person-badge me-2"></i> Update Profile Settings
                                    </a>
                                    <a href="{{ route('developer.guide') }}" class="btn action-pill btn-sm w-100 py-2.5 rounded-3 fw-semibold text-start px-3">
                                        <i class="bi bi-journal-bookmark-fill text-info me-2"></i> Developer Manual
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endauth

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Custom Date toggler logic based on dropdown select
    function toggleCustomDates(value) {
        const container = document.getElementById('customDateContainer');
        if (value === 'custom') {
            container.style.setProperty('display', 'flex', 'important');
        } else {
            container.style.setProperty('display', 'none', 'important');
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('performanceChart');
        if (ctx) {
            // কন্ট্রোলার থেকে আসা ডাইনামিক ফিল্টার্ড ডাটা
            const chartLabels = @json($chartLabels);
            const projectMonthlyData = @json($chartProjects);
            const developerMonthlyData = @json($chartDevelopers);
            const leadMonthlyData = @json($chartLeads);

            const chartData = {
                labels: chartLabels,
                @if(auth()->user()->hasRole('super-admin'))
                    datasets: [
                        {
                            label: 'New Projects Uploaded',
                            data: projectMonthlyData,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.02)',
                            tension: 0.35,
                            fill: true,
                            borderWidth: 2.5
                        },
                        {
                            label: 'Registered Developers',
                            data: developerMonthlyData,
                            borderColor: '#0284c7',
                            backgroundColor: 'rgba(2, 132, 199, 0.02)',
                            tension: 0.35,
                            fill: true,
                            borderWidth: 2.5
                        }
                    ]
                @else
                    datasets: [
                        {
                            label: 'Published Projects',
                            data: projectMonthlyData,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.02)',
                            tension: 0.35,
                            fill: true,
                            borderWidth: 2.5
                        },
                        {
                            label: 'Received Inquiries/Leads',
                            data: leadMonthlyData,
                            borderColor: '#0284c7',
                            backgroundColor: 'rgba(2, 132, 199, 0.02)',
                            tension: 0.35,
                            fill: true,
                            borderWidth: 2.5
                        }
                    ]
                @endif
            };

            new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                color: '#475569',
                                font: { family: 'Outfit', size: 11 }
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: { color: '#64748b', font: { family: 'Outfit', size: 11 } }
                        },
                        x: {
                            grid: { color: '#f1f5f9' },
                            ticks: { color: '#64748b', font: { family: 'Outfit', size: 11 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection