@extends('layouts.backend')
@section('title', 'Company Profile')
@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Outfit', sans-serif;">

    <!-- Top Header -->
    <div class="row align-items-center justify-content-between mb-4 g-3">
        <div class="col-12 col-md-auto">
            <h1 class="h3 mb-0 text-dark font-weight-bold">Companies</h1>
            <p class="text-muted mb-0 small">Overview and management of all registered companies.</p>
        </div>

    </div>

    <!-- Filter / Search Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.companies.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <!-- Search Input -->
                    <div class="col-12 col-md-4 col-lg-5">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 text-base" placeholder="Search by name, email, or phone..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-12 col-md-3 col-lg-3">
                        <select name="status" class="form-select form-select-sm text-base">
                            <option value="">All Statuses</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}> Inactive</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-12 col-md-5 col-lg-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm flex-grow-1 text-white d-inline-flex align-items-center justify-content-center gap-1.5" style="background-color: #34a487; border-color: #34a487;">
                            <i class="bi bi-funnel-fill"></i> Filter
                        </button>
                        <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary btn-sm flex-grow-1 d-inline-flex align-items-center justify-content-center gap-1.5">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13.5px;">
                    <thead class="table-light text-secondary text-uppercase small">
                        <tr>
                            <th class="ps-4 py-3" style="width: 80px;">Logo</th>
                            <th class="py-3">Company Details</th>
                            <th class="py-3">Contact Info</th>
                            <th class="py-3">Industry</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="text-center pe-4 py-3" style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($companies as $company)
                        <tr class="hover:bg-light/30 transition-colors">
                            <!-- Logo Column -->
                            <td class="ps-4 py-3">
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center overflow-hidden" style="width: 50px; height: 50px;">
                                    @if($company->company_logo)
                                    <img src="{{ asset('storage/'.$company->company_logo) }}" alt="{{ $company->company_name }}" class="img-fluid p-1" style="max-height: 100%; object-fit: contain;">
                                    @else
                                    <i class="bi bi-building text-secondary fs-5"></i>
                                    @endif
                                </div>
                            </td>

                            <!-- Company Info Column -->
                            <td class="py-3">
                                <div class="font-weight-bold text-dark fs-6">{{ $company->company_name }}</div>
                                @if($company->tagline)
                                <div class="text-muted small text-truncate mt-0.5" style="max-width: 250px;">{{ $company->tagline }}</div>
                                @endif
                                @if($company->website_url)
                                <div class="small mt-1.5">
                                    <a href="{{ $company->website_url }}" target="_blank" class="text-decoration-none text-muted">
                                        <i class="bi bi-link-45deg"></i> Website
                                    </a>
                                </div>
                                @endif
                            </td>

                            <!-- Contact Info Column -->
                            <td class="py-3">
                                <div class="text-dark"><i class="bi bi-telephone text-muted me-1.5"></i>{{ $company->phone }}</div>
                                <div class="text-muted small mt-1"><i class="bi bi-envelope text-muted me-1.5"></i>{{ $company->email }}</div>
                                @if($company->whatsapp)
                                <div class="text-success small mt-1"><i class="bi bi-whatsapp text-success me-1.5"></i>{{ $company->whatsapp }}</div>
                                @endif
                            </td>

                            <!-- Industry Column -->
                            <td class="py-3">
                                <span class="badge bg-light text-secondary border px-2 py-1 text-capitalize">
                                    {{ $company->industry ?? 'N/A' }}
                                </span>
                                @if($company->foundation_date)
                                <div class="text-muted small mt-1">Est: {{ \Carbon\Carbon::parse($company->foundation_date)->format('Y') }}</div>
                                @endif
                            </td>

                            <!-- Status Column -->
                            <td>
                                <button type="button"
                                    id="companyStatusBtn-{{ $company->id }}"
                                    onclick="toggleCompanyStatus({{ $company->id }})"
                                    class="btn btn-sm fw-bold {{ $company->status === 1 ? 'btn-success text-white' : 'btn-danger text-white' }}"
                                    style="width: 90px; border-radius: 8px;">
                                    {{ $company->status === 1 ? 'Active' : 'Inactive' }}
                                </button>
                            </td>

                            <!-- Actions Column -->
                            <td class="py-3 text-center pe-4">
                                <div class="d-flex align-items-center justify-content-center gap-1.5">
                                    <!-- View -->
                                    <a href="{{ route('admin.companies.show', $company->id) }}" class="btn btn-outline-secondary btn-sm" title="View Profile">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <!-- Edit -->
                                    <a href="{{ route('admin.company-profile.admin.edit', $company->id) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-building fs-1 text-secondary opacity-50 d-block mb-3"></i>
                                    <p class="mb-1 fw-bold text-dark">No Companies Found</p>
                                    <span class="small">Try adjusting your search filters or add a new company.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Section -->
            @if(method_exists($companies, 'hasPages') && $companies->hasPages())
            <div class="card-footer bg-white border-top-0 py-3 d-flex justify-content-center">
                {{ $companies->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
<script>
    window.toggleCompanyStatus = async function(id) {
        const statusBtn = document.getElementById(`companyStatusBtn-${id}`);
        if (!statusBtn) return;

        // Temporarily disable the button during submission
        statusBtn.disabled = true;
        const originalText = statusBtn.textContent;
        statusBtn.textContent = 'Updating...';

        try {
            const response = await fetch(`/admin/companies/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                if (result.status === 1) {
                    statusBtn.className = 'btn btn-sm fw-bold btn-success text-white';
                    statusBtn.textContent = 'Active';
                } else {
                    statusBtn.className = 'btn btn-sm fw-bold btn-danger text-white';
                    statusBtn.textContent = 'Inactive';
                }
            } else {
                alert(result.message || 'Failed to update status.');
                statusBtn.textContent = originalText;
            }
        } catch (error) {
            console.error('Error toggling company status:', error);
            alert('Connection error. Please try again.');
            statusBtn.textContent = originalText;
        } finally {
            statusBtn.disabled = false;
        }
    };
</script>