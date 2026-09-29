@extends('layouts.backend')
@section('title', 'Roles')
@section('content')

<div class="container-fluid mt-4">
    <div class="card dashboard-card mb-4">

        {{-- Header --}}
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="card-title mb-0">
                <h5 class="fw-bold mb-0 text-dark">Role Management</h5>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-success px-3 ms-auto">
                <i class="bi bi-plus-lg me-1"></i> Create New
            </a>
        </div>

        <div class="card-body p-0">

            @if(session('success'))
                <div class="alert d-flex align-items-center mx-3 mt-3 mb-0 border-0 shadow-sm"
                     style="background:#e8f5e9;color:#2e7d32;">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div class="fw-semibold small">{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="alert d-flex align-items-center mx-3 mt-3 mb-0 border-0 shadow-sm"
                     style="background:#ffebee;color:#c62828;">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div class="fw-semibold small">{{ session('error') }}</div>
                </div>
            @endif

            <div class="table-responsive mt-3">
                <table class="table table-bordered custom-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px;text-align:center;">#</th>
                            <th>Role Name</th>
                            <th>Description</th>
                            <th style="text-align:center;">Permissions</th>
                            <th style="text-align:center;">Users</th>
                            <th style="width:130px;text-align:center;padding-right:1.5rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $i => $role)
                            <tr>
                                <td class="text-muted fw-semibold text-center" style="font-size:0.825rem;">{{ $i + 1 }}</td>

                                <td>
                                    <span class="fw-semibold text-dark" style="font-size:0.9rem;">{{ $role->name }}</span>
                                    <div class="text-muted" style="font-size:0.75rem;">{{ $role->slug }}</div>
                                </td>

                                <td class="text-muted small">{{ $role->description ?? '—' }}</td>

                                <td class="text-center">
                                    @if($role->is_super_admin)
                                        <span >All</span>
                                    @else
                                        <span >{{ $role->permissions_count }}</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span>{{ $role->users_count }}</span>
                                </td>

                              
                                <td style="padding-right:1.5rem;">
                                    <div class="d-flex gap-1 justify-content-center">

                                        <a href="{{ route('admin.roles.edit', $role->id) }}"
                                           class="btn btn-action btn-outline-primary" title="Edit Permissions">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        @if(!$role->is_super_admin)
                                            <button type="button"
                                                    class="btn btn-action btn-outline-danger btn-delete-role"
                                                    data-role-id="{{ $role->id }}"
                                                    data-role-name="{{ $role->name }}"
                                                    title="Delete Role">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-shield-x" style="font-size:3rem;opacity:0.5;"></i>
                                    <p class="mt-3 mb-1 fw-bold text-dark">No roles found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

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
                <h5 class="fw-bold text-dark mb-2">Delete Role?</h5>
                <p class="text-muted small mb-4">
                    Delete role <strong id="roleNameDisplay"></strong>? Users with this role will lose access.
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-4 py-2 fw-semibold text-secondary"
                        data-bs-dismiss="modal" style="border-radius:8px;font-size:0.875rem;">Cancel</button>
                    <button type="button" class="btn btn-danger px-4 py-2 fw-semibold text-white"
                        id="confirmDeleteBtn" style="border-radius:8px;font-size:0.875rem;">Delete Role</button>
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
    const roleNameDisplay = document.getElementById('roleNameDisplay');
    let currentDeleteRoleId = null;

    // Open delete modal
    document.querySelectorAll('.btn-delete-role').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            currentDeleteRoleId = this.dataset.roleId;
            const roleName = this.dataset.roleName;
            
            roleNameDisplay.textContent = roleName;
            deleteForm.action = `/admin/roles/${currentDeleteRoleId}`;
            deleteModal.show();
        });
    });

    // Confirm deletion
    confirmDeleteBtn.addEventListener('click', function() {
        if (currentDeleteRoleId) {
            deleteForm.submit();
        }
    });
});
</script>
@endpush