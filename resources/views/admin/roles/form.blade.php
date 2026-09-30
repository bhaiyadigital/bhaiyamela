@extends('layouts.backend')
@section('title', isset($role) ? 'Edit Role' : 'Create Role')
@section('content')

<div class="container-fluid mt-4 mb-4">
    <form action="{{ isset($role) ? route('admin.roles.update', $role->id) : route('admin.roles.store') }}"
          method="POST">
        @csrf
        @if(isset($role)) @method('PUT') @endif

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    {{ isset($role) ? 'Edit Role' : 'Create New Role' }}
                </h3>
                @if(isset($role))
                    <p class="text-muted small mb-0">
                        <span class="badge bg-light text-dark">{{ $role->name }}</span>
                        @if($role->is_super_admin)
                            <span class="badge bg-warning text-dark ms-2">Super Admin</span>
                        @endif
                    </p>
                @endif
            </div>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Roles
            </a>
        </div>

        <div class="row g-4">

            {{-- Left Column: Role Info --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-4">
                        
                        {{-- Card Title --}}
                        <h6 class="fw-bold text-dark mb-4 pb-3 border-bottom d-flex align-items-center gap-2">
                            <i class="bi bi-shield-fill text-primary"></i>
                            <span>Role Information</span>
                        </h6>

                        {{-- Role Name Input --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">
                                Role Name
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="role-name"
                                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   value="{{ old('name', $role->name ?? '') }}"
                                   placeholder="e.g. Editor, Moderator"
                                   {{ isset($role) && $role->is_super_admin ? 'readonly' : '' }}
                                   required>
                            @error('name')
                                <div class="invalid-feedback d-block small mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                Human-readable name for this role
                            </small>
                        </div>

                        {{-- Slug Input --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">
                                Slug
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="slug" 
                                   id="role-slug"
                                   class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}"
                                   value="{{ old('slug', $role->slug ?? '') }}"
                                   placeholder="e.g. editor, moderator"
                                   {{ isset($role) && $role->is_super_admin ? 'readonly' : '' }}
                                   required>
                            @error('slug')
                                <div class="invalid-feedback d-block small mt-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                System identifier (lowercase, hyphens only)
                            </small>
                        </div>

                        {{-- Description Input --}}
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">
                                Description
                            </label>
                            <textarea name="description" 
                                      class="form-control"
                                      rows="4"
                                      placeholder="Describe the purpose and responsibilities of this role...">{{ old('description', $role->description ?? '') }}</textarea>
                            <small class="text-muted d-block mt-2">
                                Optional. Helps team members understand this role's purpose
                            </small>
                        </div>

                        {{-- Super Admin Notice --}}
                        @if(isset($role) && $role->is_super_admin)
                            <div class="alert alert-warning border-0 mt-4 mb-0" style="border-radius: 8px; background-color: #fff8e1;">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-star-fill flex-shrink-0 text-warning"></i>
                                    <small>
                                        <strong>Super Admin Role</strong><br>
                                        This role has unrestricted access to all features and cannot be modified.
                                    </small>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- Right Column: Permissions Grid --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body p-4">

                        {{-- Permissions Header --}}
                        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-key-fill text-primary"></i>
                                <span>Permissions</span>
                                <span class="badge bg-light text-dark ms-2">
                                    <span id="active-perms-count">0</span> Active
                                </span>
                            </h6>

                            {{-- Select/Deselect Controls --}}
                            @if(!isset($role) || !$role->is_super_admin)
                                <div class="d-flex gap-2">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success"
                                            id="select-all-perms"
                                            title="Enable all permissions">
                                        <i class="bi bi-check-all me-1"></i>All
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-secondary"
                                            id="deselect-all-perms"
                                            title="Disable all permissions">
                                        <i class="bi bi-x-lg me-1"></i>None
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- Permissions Table --}}
                        <div class="permissions-container">
                            @php
                                $assignedIds = isset($role)
                                    ? $role->permissions->pluck('id')->toArray()
                                    : [];

                                $actions = ['view', 'create', 'edit', 'delete'];
                                $viewOnlyModules = ['message'];
                            @endphp

                            {{-- Table Header --}}
                            <div class="permissions-header d-none d-md-grid mb-3 px-3 py-2" 
                                 style="display: grid; grid-template-columns: 2fr repeat(4, 1fr); gap: 1rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em;">
                                <div class="text-muted fw-semibold">Module</div>
                                @foreach($actions as $action)
                                    <div class="text-muted fw-semibold text-center">{{ ucfirst($action) }}</div>
                                @endforeach
                            </div>

                            {{-- Module Rows --}}
                            @foreach($permissions as $moduleName => $modulePerms)
                                @php
                                    $byAction = $modulePerms->keyBy('action');
                                    $isViewOnly = in_array($moduleName, $viewOnlyModules);
                                @endphp
                                
                                <div class="permission-module-row mb-2 p-3" 
                                     style="border-radius: 8px; display: grid; grid-template-columns: 2fr repeat(4, 1fr); gap: 1rem; align-items: center; background: #f8f9fa; border: 1px solid #e9ecef; transition: all 0.2s ease;">

                                    {{-- Module Name with Select All --}}
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="checkbox"
                                               class="form-check-input module-check-all"
                                               data-module="{{ $moduleName }}"
                                               {{ !isset($role) || !$role->is_super_admin ? '' : 'checked disabled' }}
                                               style="width: 18px; height: 18px; cursor: pointer;">
                                        <span class="fw-semibold text-dark" style="font-size: 0.9rem;">
                                            {{ ucfirst(str_replace(['-','_'], ' ', $moduleName)) }}
                                        </span>
                                    </div>

                                    {{-- Action Toggles --}}
                                    @foreach($actions as $action)
                                        @if($isViewOnly && in_array($action, ['create', 'edit', 'delete']))
                                            <div class="text-center">
                                                <span class="text-muted small">—</span>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                @if($perm = $byAction->get($action))
                                                    @php
                                                        $checked = isset($role) && $role->is_super_admin
                                                            ? true
                                                            : in_array($perm->id, old('permissions', $assignedIds));
                                                    @endphp

                                                    @if(isset($role))
                                                        {{-- Edit mode: AJAX toggle --}}
                                                        <button type="button"
                                                                class="btn-perm-toggle {{ $checked ? 'active' : '' }}"
                                                                data-perm-id="{{ $perm->id }}"
                                                                data-role-id="{{ $role->id }}"
                                                                data-module="{{ $moduleName }}"
                                                                {{ $role->is_super_admin ? 'disabled' : '' }}
                                                                title="Toggle {{ $moduleName }} {{ $action }} permission">
                                                            <i class="bi {{ $checked ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                        </button>
                                                        <input type="checkbox"
                                                               name="permissions[]"
                                                               value="{{ $perm->id }}"
                                                               class="perm-checkbox d-none"
                                                               data-module="{{ $moduleName }}"
                                                               {{ $checked ? 'checked' : '' }}>
                                                    @else
                                                        {{-- Create mode: checkbox + toggle --}}
                                                        <input type="checkbox"
                                                               name="permissions[]"
                                                               value="{{ $perm->id }}"
                                                               class="perm-checkbox d-none"
                                                               data-module="{{ $moduleName }}"
                                                               {{ $checked ? 'checked' : '' }}>
                                                        <button type="button"
                                                                class="btn-perm-toggle {{ $checked ? 'active' : '' }}"
                                                                data-perm-id="{{ $perm->id }}"
                                                                data-module="{{ $moduleName }}"
                                                                title="Toggle {{ $moduleName }} {{ $action }} permission">
                                                            <i class="bi {{ $checked ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach

                                </div>
                            @endforeach

                        </div>

                        {{-- Empty State (if no permissions) --}}
                        @if($permissions->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-3 mb-0">No permissions available</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

        {{-- Form Actions --}}
        <div class="d-flex gap-2 mt-4 mb-4">
            <button type="submit" class="btn btn-success px-5">
                <i class="bi bi-check-circle me-2"></i>
                {{ isset($role) ? 'Update Role' : 'Create Role' }}
            </button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary px-5">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection

@push('styles')
<style>
    /* ─── Permission Toggle Button ─────────────────────────────────── */
    .btn-perm-toggle {
        background: none;
        border: none;
        padding: 0.25rem 0.5rem;
        font-size: 1.75rem;
        line-height: 1;
        color: #dee2e6;
        cursor: pointer;
        transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-perm-toggle.active {
        color: #198754;
    }

    .btn-perm-toggle:not(:disabled):hover {
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.08);
        transform: scale(1.1);
    }

    .btn-perm-toggle:disabled {
        color: #198754;
        cursor: not-allowed;
        opacity: 0.65;
    }

    /* ─── Module Row Styles ─────────────────────────────────────────── */
    .permission-module-row {
        transition: all 0.2s ease;
    }

    .permission-module-row:hover {
        background-color: #f1f3f5 !important;
        border-color: #dee2e6 !important;
    }

    .permission-module-row:nth-child(odd) {
        background-color: #ffffff;
        border-color: #e9ecef;
    }

    .permission-module-row:nth-child(even) {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .permission-module-row:nth-child(even):hover {
        background-color: #f1f3f5 !important;
    }

    /* ─── Responsive Design ─────────────────────────────────────────── */
    @media (max-width: 768px) {
        .permission-module-row {
            grid-template-columns: 1fr !important;
            gap: 0.75rem;
        }

        .permissions-header {
            display: none !important;
        }

        .permission-module-row::before {
            content: attr(data-label);
            grid-column: 1;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6c757d;
            font-weight: 600;
        }
    }

    /* ─── Badge Animations ─────────────────────────────────────────── */
    .badge {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* ─── Input Focus States ─────────────────────────────────────────── */
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    /* ─── Card Styling ─────────────────────────────────────────────── */
    .card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
    }

    .card:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12) !important;
        transition: box-shadow 0.3s ease;
    }

</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const isEditMode = {{ isset($role) ? 'true' : 'false' }};
    const csrfToken  = '{{ csrf_token() }}';

    // ── Update active permissions count ───────────────────────────────
    function updatePermissionCount() {
        const activeCount = document.querySelectorAll('.btn-perm-toggle.active:not(:disabled)').length;
        const countBadge = document.getElementById('active-perms-count');
        if (countBadge) {
            countBadge.textContent = activeCount;
        }
    }

    // ── Toggle button click ───────────────────────────────────────────
    document.querySelectorAll('.btn-perm-toggle:not(:disabled)').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const permId    = this.dataset.permId;
            const roleId    = this.dataset.roleId;
            const isActive  = this.classList.contains('active');
            const icon      = this.querySelector('i');
            const checkbox  = document.querySelector(`.perm-checkbox[value="${permId}"]`);

            if (isEditMode && roleId) {
                // AJAX toggle for edit mode
                fetch(`/admin/roles/${roleId}/toggle-permission`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ permission_id: permId }),
                })
                .then(r => r.json())
                .then(data => {
                    applyToggle(this, icon, checkbox, data.active);
                    updateModuleCheckAll(this.dataset.module);
                    updatePermissionCount();
                })
                .catch(err => console.error('Permission toggle error:', err));
            } else {
                // Instant toggle for create mode
                applyToggle(this, icon, checkbox, !isActive);
                updateModuleCheckAll(this.dataset.module);
                updatePermissionCount();
            }
        });
    });

    function applyToggle(btn, icon, checkbox, active) {
        if (active) {
            btn.classList.add('active');
            icon.classList.replace('bi-toggle-off', 'bi-toggle-on');
            if (checkbox) checkbox.checked = true;
        } else {
            btn.classList.remove('active');
            icon.classList.replace('bi-toggle-on', 'bi-toggle-off');
            if (checkbox) checkbox.checked = false;
        }
    }

    // ── Module "select all" checkbox ────────────────────────────────
    document.querySelectorAll('.module-check-all').forEach(function (chk) {
        chk.addEventListener('change', function () {
            const module  = this.dataset.module;
            const active  = this.checked;

            document.querySelectorAll(`.btn-perm-toggle[data-module="${module}"]:not(:disabled)`).forEach(function (btn) {
                const isCurrentlyActive = btn.classList.contains('active');
                if (isCurrentlyActive !== active) {
                    btn.click();
                }
            });
        });
    });

    // Sync module checkbox state based on its toggles
    function updateModuleCheckAll(module) {
        const allToggles     = document.querySelectorAll(`.btn-perm-toggle[data-module="${module}"]:not(:disabled)`);
        const activeToggles  = document.querySelectorAll(`.btn-perm-toggle[data-module="${module}"].active:not(:disabled)`);
        const moduleChk      = document.querySelector(`.module-check-all[data-module="${module}"]`);
        if (!moduleChk) return;
        moduleChk.checked       = activeToggles.length === allToggles.length;
        moduleChk.indeterminate = activeToggles.length > 0 && activeToggles.length < allToggles.length;
    }

    // Init module checkboxes state on page load
    document.querySelectorAll('.module-check-all').forEach(function (chk) {
        updateModuleCheckAll(chk.dataset.module);
    });

    // ── Select all / deselect all buttons ────────────────────────────
    document.getElementById('select-all-perms')?.addEventListener('click', function () {
        document.querySelectorAll('.btn-perm-toggle:not(:disabled):not(.active)').forEach(btn => btn.click());
    });

    document.getElementById('deselect-all-perms')?.addEventListener('click', function () {
        document.querySelectorAll('.btn-perm-toggle.active:not(:disabled)').forEach(btn => btn.click());
    });

    // ── Auto-generate slug from name ────────────────────────────────
    document.getElementById('role-name')?.addEventListener('input', function () {
        const slugField = document.getElementById('role-slug');
        if (!slugField.dataset.manuallyEdited) {
            slugField.value = this.value
                .toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^a-z0-9-]/g, '')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
        }
    });

    document.getElementById('role-slug')?.addEventListener('input', function () {
        this.dataset.manuallyEdited = 'true';
    });

    // Initialize permission count on load
    updatePermissionCount();

});
</script>
@endpush
