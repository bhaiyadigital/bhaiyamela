@extends('layouts.backend')

@section('title', 'Buyer Requirements')

@section('content')
<div class="container-fluid mt-4">

    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="bi bi-list-task me-2 text-success"></i>Buyer Requirements / Leads
        </h5>
    </div>

    <!-- Filters and Search Bar -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.requirements.index') }}" method="GET" class="row g-2 align-items-center">

                <!-- Search Input -->
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Search by name, email, phone or location...">
                    </div>
                </div>

                <!-- Status Filter Dropdown -->
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="matched" {{ request('status') === 'matched' ? 'selected' : '' }}>Matched</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    @if(request('search') || request('status'))
                    <a href="{{ route('admin.requirements.index') }}" class="btn btn-outline-danger">
                        <i class="bi bi-x-lg me-1"></i>Clear
                    </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <!-- Requirements Data Table Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" style="font-size: .9rem;">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">ID</th>
                            <th>Buyer Info</th>
                            <th>Requirements</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($requirements as $req)
                        @php
                        $status = $req->status ?? 'pending';
                        // Bootstrap Badge Colors Mapping
                        $badgeClass = match($status) {
                        'contacted' => 'bg-info text-white',
                        'matched' => 'bg-success text-white',
                        'closed' => 'bg-secondary text-white',
                        default => 'bg-warning text-dark', // pending
                        };
                        @endphp
                        <tr>
                            <!-- ID -->
                            <td class="ps-4 font-bold text-muted">#{{ $req->id }}</td>

                            <!-- Buyer Personal Info -->
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="fw-bold text-dark"><i class="bi bi-person me-1 text-muted"></i>{{ $req->name }}</span>
                                    <span class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $req->email }}</span>
                                    <span class="text-muted small font-semibold"><i class="bi bi-telephone me-1"></i>{{ $req->phone }}</span>
                                </div>
                            </td>

                            <!-- Property Requirements Info -->
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="text-dark fw-bold">
                                        <span class="badge bg-light text-dark border me-1 text-uppercase" style="font-size: .7rem;">{{ $req->purpose }}</span>
                                        {{ $req->property_type }}
                                    </span>
                                    <span class="text-muted small">Size: {{ $req->size }} Sqft | City: {{ $req->city }}</span>
                                    @if($req->location)
                                    <span class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $req->location }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Interactive Bootstrap Dropdown for Status Change -->
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle {{ $badgeClass }}"
                                        type="button"
                                        id="statusBtn-{{ $req->id }}"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        {{ ucfirst($status) }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="statusBtn-{{ $req->id }}">
                                        <li><button class="dropdown-item fw-bold text-warning" type="button" onclick="updateRequirementStatus({{ $req->id }}, 'pending')">Pending</button></li>
                                        <li><button class="dropdown-item fw-bold text-info" type="button" onclick="updateRequirementStatus({{ $req->id }}, 'contacted')">Contacted</button></li>
                                        <li><button class="dropdown-item fw-bold text-success" type="button" onclick="updateRequirementStatus({{ $req->id }}, 'matched')">Matched</button></li>
                                        <li><button class="dropdown-item fw-bold text-secondary" type="button" onclick="updateRequirementStatus({{ $req->id }}, 'closed')">Closed</button></li>
                                    </ul>
                                </div>
                            </td>

                            <!-- Submitted Date -->
                            <td class="text-muted small">{{ $req->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-5 text-center text-muted fw-bold">
                                <i class="bi bi-folder-x fs-3 d-block mb-2"></i>
                                No buyer requirements found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Custom Pagination Footer -->
    <div class="mt-4 flex justify-content-center">
        {{ $requirements->appends(request()->query())->links('frontend.partials.custom_pagination') }}
    </div>

</div>

{{-- ── Pure JS for AJAX Status Updates (Completely Bootstrap Compatible) ── --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.updateRequirementStatus = async function(id, newStatus) {
            const statusBtn = document.getElementById(`statusBtn-${id}`);
            if (!statusBtn) return;

            // ⚠️ [NEW] Manually force close the Bootstrap dropdown menu instantly on click
            const parentDropdown = statusBtn.closest('.dropdown');
            if (parentDropdown) {
                const menu = parentDropdown.querySelector('.dropdown-menu');
                if (menu) menu.classList.remove('show');
                statusBtn.classList.remove('show');
                statusBtn.removeAttribute('aria-expanded');
            }

            // Temporarily disable button to prevent multiple submissions
            statusBtn.disabled = true;
            const originalText = statusBtn.textContent;
            statusBtn.textContent = 'Updating...';

            try {
                const response = await fetch(`/admin/requirements/${id}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Determine appropriate bootstrap color classes based on updated status
                    let badgeClass = 'bg-warning text-dark';

                    if (result.status === 'contacted') {
                        badgeClass = 'bg-info text-white';
                    } else if (result.status === 'matched') {
                        badgeClass = 'bg-success text-white';
                    } else if (result.status === 'closed') {
                        badgeClass = 'bg-secondary text-white';
                    }

                    // Apply updated Bootstrap class and capitalized text
                    statusBtn.className = `btn btn-sm dropdown-toggle ${badgeClass}`;
                    statusBtn.textContent = result.status.charAt(0).toUpperCase() + result.status.slice(1);
                } else {
                    alert(result.message || 'Failed to update status.');
                    statusBtn.textContent = originalText;
                }
            } catch (error) {
                console.error('Error updating status:', error);
                alert('Connection error. Please try again.');
                statusBtn.textContent = originalText;
            } finally {
                statusBtn.disabled = false;
            }
        };
    });
</script>
@endsection