@extends('layouts.backend')
@section('title', 'Users')
@section('content')

<div class="container-fluid mt-4">
    <div class="card dashboard-card mb-4">

        {{-- Header --}}
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="card-title mb-0">
                <h5 class="fw-bold mb-0 text-dark">User Management</h5>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap ms-auto">

                <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex gap-2">

                    {{-- Search --}}
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0"
                            placeholder="Search name or email..." value="{{ request('search') }}" style="width:350px;">
                    </div>

                    {{-- Role filter --}}
                    <select name="role" class="form-select form-select-sm" style="width:140px;">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->slug }}" {{ request('role') === $role->slug ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>

                    <button class="btn btn-sm btn-outline-secondary px-3" type="submit">Filter</button>

                    @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-x-lg"></i>
                    </a>
                    @endif
                </form>
                @if(auth()->user()->hasPermission('users.create'))

                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-success px-3">
                    <i class="bi bi-plus-lg me-1"></i> Create New
                </a>
                @endif
            </div>
        </div>

        <div class="card-body p-0">

            {{-- Alerts --}}
            @if(session('success'))
            <div class="alert d-flex align-items-center mx-3 mt-3 mb-0 border-0 shadow-sm"
                style="background-color:#e8f5e9; color:#2e7d32;">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div class="fw-semibold small">{{ session('success') }}</div>
            </div>
            @endif
            @if(session('error'))
            <div class="alert d-flex align-items-center mx-3 mt-3 mb-0 border-0 shadow-sm"
                style="background-color:#ffebee; color:#c62828;">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div class="fw-semibold small">{{ session('error') }}</div>
            </div>
            @endif

            {{-- Table --}}
            <div class="table-responsive mt-3">
                <table class="table table-bordered custom-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px; text-align:center;">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th style="width:130px; text-align:center; padding-right:1.5rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $i => $user)
                        <tr>
                            <td class="text-muted fw-semibold text-center" style="font-size:0.825rem;">
                                {{ $users->firstItem() + $i }}
                            </td>

                            {{-- Avatar + name --}}
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                        style="width:34px;height:34px;font-size:13px;flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold text-dark" style="font-size:0.9rem;">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                        <span class="badge badge-soft-primary ms-1" style="font-size:10px;">You</span>
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <td class="text-muted ">{{ $user->email }}</td>

                            {{-- Roles --}}
                            <td>
                                @forelse($user->roles as $role)
                                <span class="fw-semibold text-dark" style="font-size:11px;">
                                    {{ $role->name }}
                                </span>
                                @empty
                                <span class="text-muted small">No role</span>
                                @endforelse
                            </td>

                            {{-- Actions --}}
                            <td style="padding-right:1.5rem;">
                                <div class="d-flex gap-1 justify-content-center">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="btn btn-action btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- Delete — cannot delete self --}}
                                    @if($user->id !== auth()->id())
                                    <button type="button"
                                        class="btn btn-action btn-outline-danger btn-delete-user"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        title="Delete">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5 border-bottom">
                                <div class="py-4">
                                    <i class="bi bi-people text-muted" style="font-size:3rem;opacity:0.7;"></i>
                                    <p class="mt-3 mb-1 fw-bold text-dark">No users found</p>
                                    <p class="small text-muted">Try modifying your search or filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
            <div class="px-4 py-3 border-top d-flex justify-content-end bg-light">
                {{ $users->withQueryString()->links() }}
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius:16px;">
            <div class="modal-body text-center p-4">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                    style="width:72px;height:72px;font-size:32px;background-color:#ffe0e0;color:#dc3545;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Delete User?</h5>
                <p class="text-muted small mb-4">
                    Are you sure you want to delete <strong id="userNameDisplay"></strong>? This action cannot be undone.
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-4 py-2 fw-semibold text-secondary"
                        data-bs-dismiss="modal" style="border-radius:8px;font-size:0.875rem;">Cancel</button>
                    <button type="button" class="btn btn-danger px-4 py-2 fw-semibold text-white"
                        id="confirmDeleteBtn" style="border-radius:8px;font-size:0.875rem;">Delete User</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden form for deletion --}}
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const userNameDisplay = document.getElementById('userNameDisplay');
    let currentDeleteUserId = null;

    // Open delete modal
    document.querySelectorAll('.btn-delete-user').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            currentDeleteUserId = this.dataset.userId;
            const userName = this.dataset.userName;
            
            userNameDisplay.textContent = userName;
            deleteForm.action = `/admin/users/${currentDeleteUserId}`;
            deleteModal.show();
        });
    });

    // Confirm deletion
    confirmDeleteBtn.addEventListener('click', function() {
        if (currentDeleteUserId) {
            deleteForm.submit();
        }
    });
});
</script>
@endpush
