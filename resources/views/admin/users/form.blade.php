@extends('layouts.backend')
@section('title', isset($user) ? 'Edit User' : 'Create User')
@section('content')

<div class="container-fluid mt-4">
    <form action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}"
        method="POST">
        @csrf
        @if(isset($user)) @method('PUT') @endif

        <div class="card dashboard-card mb-4">

            {{-- Header --}}
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="fw-bold mb-0 text-dark">
                    {{ isset($user) ? 'Edit User' : 'Create New User' }}
                </h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary ms-auto">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card-body">
                <div class="row g-4">

                    {{-- Left column — user info --}}
                    <div class="col-md-7">
                        <div class="card border shadow-none" style="border-radius:12px;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom">
                                    <i class="bi bi-person me-2 text-primary"></i>User Information
                                </h6>

                                {{-- Name --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark" style="font-size:0.875rem;">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        value="{{ old('name', $user->name ?? '') }}" placeholder="e.g. John Doe" required>
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark" style="font-size:0.875rem;">
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        value="{{ old('email', $user->email ?? '') }}" placeholder="e.g. john@example.com" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark" style="font-size:0.875rem;">
                                        Status <span class="text-danger">*</span>
                                    </label>

                                    <select name="status" class="form-select {{ $errors->has('status') ? 'is-invalid' : '' }}" required>
                                        <option value="">Select Status</option>
                                        <option value="1" {{ old('status', $user->status ?? 1) == 1 ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0" {{ old('status', $user->status ?? 1) == 0 ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>

                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right column — roles --}}
                    <div class="col-md-5">
                        <div class="card border shadow-none" style="border-radius:12px;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom">
                                    <i class="bi bi-shield-lock me-2 text-primary"></i>Assign Role
                                </h6>

                                @php
                                $assignedRoleId = isset($user)
                                ? $user->roles->first()?->id
                                : null;
                                @endphp

                                @forelse($roles as $role)
                                <div class="role-card form-check mb-3 p-3 rounded d-flex align-items-start gap-3"
                                    data-role-id="{{ $role->id }}"
                                    style="background:{{ $role->is_super_admin ? '#fff5f5' : '#f8f9fa' }};
                            border:1px solid {{ $role->is_super_admin ? '#fecaca' : '#e9ecef' }};
                            border-radius:10px!important; cursor:pointer; transition: all 0.2s;">
                                    <input type="radio"
                                        class="form-check-input mt-1 role-radio"
                                        name="roles"
                                        value="{{ $role->id }}"
                                        id="role_{{ $role->id }}"
                                        {{ (old('roles', $assignedRoleId) == $role->id) ? 'checked' : '' }}>
                                    <label class="form-check-label w-100" for="role_{{ $role->id }}" style="cursor:pointer;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="fw-semibold text-dark" style="font-size:0.9rem;">
                                                {{ $role->name }}
                                            </span>
                                            @if($role->is_super_admin)
                                            <span class="badge badge-soft-danger" style="font-size:10px;">Super Admin</span>
                                            @endif
                                        </div>
                                        @if($role->description)
                                        <div class="text-muted mt-1" style="font-size:0.8rem;">{{ $role->description }}</div>
                                        @endif

                                        @if($role->permissions && $role->permissions->count())
                                        <div class="role-permissions mt-2 pt-2 border-top"
                                            id="perms_{{ $role->id }}"
                                            style="display:none;">
                                            <p class="text-muted mb-2" style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">
                                                Permissions
                                            </p>

                                            @foreach($role->permissions->groupBy('module') as $module => $perms)
                                            <div class="mb-2">
                                                {{-- Module name --}}
                                                <span class="text-muted" style="font-size:0.72rem; font-weight:600; text-transform:capitalize;">
                                                    <i class="bi bi-folder2 me-1"></i>{{ $module }}
                                                </span>
                                                <div class="d-flex flex-wrap gap-1 mt-1">
                                                    @foreach($perms as $permission)
                                                    <span class="badge bg-primary bg-opacity-10 text-primary"
                                                        style="font-size:0.72rem; font-weight:500;">
                                                        {{ $permission->action }}
                                                    </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <div class="role-permissions mt-2 pt-2 border-top"
                                            id="perms_{{ $role->id }}"
                                            style="display:none;">
                                            <p class="text-muted mb-0" style="font-size:0.75rem;">No permissions assigned.</p>
                                        </div>
                                        @endif
                                    </label>
                                </div>
                                @empty
                                <p class="text-muted small">No roles found. Create roles first.</p>
                                @endforelse

                                @error('roles')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="card-footer d-flex gap-2">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-check-lg me-1"></i>
                    {{ isset($user) ? 'Update User' : 'Create User' }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">Cancel</a>
            </div>

        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    // Password show/hide toggle
    document.getElementById('toggle-password')?.addEventListener('click', function() {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggle-password-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });

    // Role radio — permissions toggle + highlight
    function updateRoleUI() {
        document.querySelectorAll('.role-permissions').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.role-card').forEach(card => {
            card.style.borderColor = card.dataset.superAdmin === '1' ? '#fecaca' : '#e9ecef';
            card.style.boxShadow = 'none';
        });

        const selected = document.querySelector('.role-radio:checked');
        if (selected) {
            const roleId = selected.value;
            const perms = document.getElementById('perms_' + roleId);
            if (perms) perms.style.display = 'block';

            const card = selected.closest('.role-card');
            if (card) {
                card.style.borderColor = '#6366f1';
                card.style.boxShadow = '0 0 0 3px rgba(99,102,241,0.15)';
            }
        }
    }

    // Page load এ (edit mode তে already selected থাকলে)
    document.addEventListener('DOMContentLoaded', updateRoleUI);

    // Change এ
    document.querySelectorAll('.role-radio').forEach(radio => {
        radio.addEventListener('change', updateRoleUI);
    });

    // Card click এ (label ছাড়া বাকি অংশে click করলেও কাজ করবে)
    document.querySelectorAll('.role-card').forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('.role-radio');
            if (radio) {
                radio.checked = true;
                updateRoleUI();
            }
        });
    });
</script>
@endpush
