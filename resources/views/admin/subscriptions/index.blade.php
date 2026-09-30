@extends('layouts.backend')
@section('title', 'Subscriber List')

@section('content')
<div class="container-fluid mt-4">

    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="bi bi-envelope-paper-fill me-2 text-success"></i>Newsletter Subscribers
        </h5>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-1"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filter and Search Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="row g-2 align-items-center">

                <!-- Search Input -->
                <div class="col-md-6 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control" placeholder="Search by subscriber email...">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-funnel me-1"></i>Search
                    </button>
                    @if(request('search'))
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-danger">
                        <i class="bi bi-x-lg me-1"></i>Clear
                    </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <!-- Subscriber Data Table Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" style="font-size: .9rem;">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">ID</th>
                            <th>Subscriber Email</th>
                            <th>Subscribed At</th>
                            <th class="text-end pe-4" style="width: 120px;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($subscriptions as $sub)
                        <tr>
                            <!-- ID -->
                            <td class="ps-4 font-bold text-muted">#{{ $sub->id }}</td>

                            <!-- Email -->
                            <td class="fw-semibold text-dark">
                                <i class="bi bi-envelope me-2 text-muted"></i>{{ $sub->email }}
                            </td>

                            <!-- Date Joined -->
                            <td class="text-muted small">
                                <i class="bi bi-calendar-check me-1"></i>{{ $sub->created_at->format('M d, Y h:i A') }}
                            </td>

                            <!-- Action Buttons -->
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.subscriptions.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this subscriber permanently?');" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Subscriber">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-5 text-center text-muted fw-bold">
                                <i class="bi bi-folder-x fs-3 d-block mb-2"></i>
                                No newsletter subscribers found.
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
        {{ $subscriptions->appends(request()->query())->links('frontend.partials.custom_pagination') }}
    </div>

</div>
@endsection
