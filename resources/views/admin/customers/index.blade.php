@extends('layouts.backend')
@section('title', 'Customer List')

@section('content')
<div class="container-fluid mt-4">

    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="bi bi-people-fill me-2 text-success"></i>Registered Customers / Individuals
        </h5>
    </div>

    <!-- Filters and Search Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-2 align-items-center">
                
                <!-- Search Input -->
                <div class="col-md-6 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="form-control" placeholder="Search by name, email or phone...">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-funnel me-1"></i>Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-danger">
                            <i class="bi bi-x-lg me-1"></i>Clear
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <!-- Customers Data Table Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" style="font-size: .9rem;">
                    
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">ID</th>
                            <th>Customer Name</th>
                            <th>Email Address</th>
                            <th>Phone Number</th>
                            <th>Status</th>
                            <th class="pe-4" style="width: 200px;">Registered At</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <!-- ID -->
                                <td class="ps-4 font-bold text-muted">#{{ $customer->id }}</td>
                                
                                <!-- Name -->
                                <td class="fw-bold text-dark">
                                    <i class="bi bi-person-circle me-2 text-muted"></i>{{ $customer->name }}
                                </td>

                                <!-- Email -->
                                <td>{{ $customer->email }}</td>

                                <!-- Phone -->
                                <td class="fw-semibold">{{ $customer->phone ?? 'N/A' }}</td>

                                <!-- Interactive Click-to-Toggle Status Button -->
                                <td>
                                    <button type="button" 
                                            id="statusBtn-{{ $customer->id }}"
                                            onclick="toggleCustomerStatus({{ $customer->id }})"
                                            class="btn btn-sm fw-bold {{ $customer->status === 1 ? 'btn-success text-white' : 'btn-danger text-white' }}"
                                            style="width: 90px; border-radius: 8px;">
                                        {{ $customer->status === 1 ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>

                                <!-- Date Registered -->
                                <td class="text-muted small pe-4">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $customer->created_at->format('M d, Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-5 text-center text-muted fw-bold">
                                    <i class="bi bi-people fs-3 d-block mb-2"></i>
                                    No registered customers found.
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
        {{ $customers->appends(request()->query())->links('frontend.partials.custom_pagination') }}
    </div>

</div>

{{-- ── Pure AJAX JS for Customer Status Toggling (Bootstrap Compatible) ── --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // AJAX function to toggle customer active/inactive status in DB
    window.toggleCustomerStatus = async function(id) {
        const statusBtn = document.getElementById(`statusBtn-${id}`);
        if (!statusBtn) return;

        // Temporarily disable the button during submission
        statusBtn.disabled = true;
        const originalText = statusBtn.textContent;
        statusBtn.textContent = 'Updating...';

        try {
            const response = await fetch(`/admin/customers/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Apply successful styling depending on toggled status value (1 or 0)
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
            console.error('Error toggling customer status:', error);
            alert('Connection error. Please try again.');
            statusBtn.textContent = originalText;
        } finally {
            statusBtn.disabled = false;
        }
    };
});
</script>
@endsection
