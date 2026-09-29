@extends('layouts.backend')

@section('title', 'List ' . ucwords(str_replace(['-', '_'], ' ', $module)))

@section('content')

<style>
    .dashboard-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.04);
        background: #ffffff;
        overflow: hidden;
    }

    .dashboard-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f3f5;
        padding: 1.25rem 1.5rem;
    }

    .form-control,
    .form-select {
        border-color: #e9ecef;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.25s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
    }

    .custom-table {
        border-color: #dee2e6 !important;
    }

    .custom-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.725rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        background-color: #f8f9fa;
        padding: 1rem;
        border-bottom: 2px solid #dee2e6 !important;
    }

    .custom-table td {
        padding: 0.85rem 1rem;
        font-size: 0.875rem;
        border-color: #dee2e6 !important;
    }

    .custom-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .custom-table tbody tr:hover {
        background-color: #fafbfc;
    }

    .badge-soft-success {
        background: #e6f9ec;
        color: #0f5132;
        border: 1px solid #d1f7db;
    }

    .badge-soft-warning {
        background: #fffbeb;
        color: #664d03;
        border: 1px solid #fff3cd;
    }

    .badge-soft-secondary {
        background: #f8f9fa;
        color: #41464b;
        border: 1px solid #e2e6ea;
    }

    .badge-soft-danger {
        background: #fdf2f2;
        color: #842029;
        border: 1px solid #f8d7da;
    }

    .btn-status-toggle {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 30px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .btn-status-toggle:hover {
        opacity: 0.85;
        transform: translateY(-1px);
    }

    .thumb-img {
        width: 60px;
        height: 45px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
    }

    .thumb-img:hover {
        transform: scale(1.08);
    }

    .thumb-video {
        width: 80px;
        height: 50px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .btn-action:hover {
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid mt-4">
    <div class="card dashboard-card mb-4">

        {{-- Header --}}
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="card-title mb-0">
                <h5 class="fw-bold mb-0 text-dark">
                    List of {{ $config['module_name']['label'] ?? ucwords(str_replace(['-', '_'], ' ', $module)) }}
                </h5>
            </div>

            <div class="d-flex align-items-center flex-nowrap gap-2 ms-auto">

                <form method="GET"
                    action="{{ route('admin.contents.index', $module) }}"
                    class="d-flex align-items-center gap-2 mb-0">

                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                            name="search"
                            class="form-control form-control-sm border-start-0"
                            placeholder="Search..."
                            value="{{ request('search') }}"
                            style="width:350px;">
                    </div>
                    @if(auth()->user()->hasRole('super-admin'))
                    <select name="company_id" class="form-select form-select-sm" style="width:160px;">
                        <option value="">All Companies</option>
                        @foreach(\App\Models\Company::all() as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                        @endforeach
                    </select>
                    @endif
                    <select name="status" class="form-select form-select-sm" style="width:130px;">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Scheduled</option>
                        <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Trash</option>
                    </select>

                    <button class="btn btn-sm btn-outline-secondary px-3" type="submit">
                        Filter
                    </button>

                    @if(request('search') || request('status') !== null)
                    <a href="{{ route('admin.contents.index', $module) }}"
                        class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-x-lg"></i>
                    </a>
                    @endif
                </form>

                @if(auth()->user()->hasPermission($module . '.create'))
                <a href="{{ route('admin.contents.create', $module) }}"
                    class="btn btn-sm btn-success px-3">
                    <i class="bi bi-plus-lg me-1"></i> Create New
                </a>
                @endif

            </div>
        </div>

        <div class="card-body p-0">

            {{-- Session alerts --}}
            @if(session('success'))
            <div class="alert alert-success d-flex align-items-center mx-3 mt-3 mb-0 border-0 shadow-sm" style="background-color:#e8f5e9; color:#2e7d32;">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div class="fw-semibold small">{{ session('success') }}</div>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center mx-3 mt-3 mb-0 border-0 shadow-sm" style="background-color:#ffebee; color:#c62828;">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div class="fw-semibold small">{{ session('error') }}</div>
            </div>
            @endif

            <form id="bulk-form" action="{{ route('admin.contents.bulk', $module) }}" method="POST">
                @csrf
                <div class="alert alert-primary d-flex align-items-center justify-content-between mx-3 mt-3 mb-0 border-0 shadow-sm d-none"
                    id="bulk-bar"
                    style="display:none; background-color:#eff6ff; border: 1px solid #dbeafe !important; border-radius: 10px;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle text-primary fs-5"></i>
                        <span class="text-primary fw-semibold small" id="bulk-count">0 selected</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <select name="action" class="form-select form-select-sm" style="width:160px;" required>
                            <option value="">-- Bulk Action --</option>
                            <option value="activate">Active</option>
                            <option value="deactivate">Inactive</option>
                            <option value="trash">Move to Trash</option>
                            <option value="delete">Permanent Delete</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-primary px-3 btn-confirm"
                            data-title="Apply Bulk Action"
                            data-message="Are you sure you want to apply this bulk action to all selected items?"
                            data-type="primary"
                            data-submit-form="#bulk-form">Apply</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="bulk-clear">Clear</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive mt-3">
                <table class="table table-bordered custom-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:36px; padding-left: 1.5rem; text-align: center;">
                                <input type="checkbox" id="check-all" class="form-check-input" title="Select all">
                            </th>
                            <th style="width:32px; text-align: center;">
                                <i class="bi bi-arrows-move text-muted" title="Drag to reorder"></i>
                            </th>

                            <th style="width: 50px; text-align: center;">#</th>
                            @if(auth()->user()->hasRole('super-admin'))
                            <th>Company</th>
                            @endif
                            @foreach($config as $field => $data)
                            @if($field === 'module_name') @continue @endif
                            @if(!empty($data['show_in_table']))
                            <th>{{ $data['label'] }}</th>
                            @endif
                            @endforeach

                            <th style="width:150px; text-align: center; padding-right: 1.5rem;">Actions</th>
                        </tr>
                    </thead>

                    <tbody id="sortable-rows">
                        @forelse($records as $i => $item)
                        <tr data-id="{{ $item->id }}">

                            <td style="padding-left: 1.5rem; text-align: center;">
                                <input type="checkbox" value="{{ $item->id }}" class="form-check-input row-check">
                            </td>

                            {{-- Drag handle --}}
                            <td class="drag-handle text-muted text-center" style="cursor:grab; opacity: 0.6;" title="Drag to reorder">
                                <i class="bi bi-grip-vertical fs-5"></i>
                            </td>

                            <td class="text-muted fw-semibold text-center" style="font-size: 0.825rem;">{{ $records->firstItem() + $i }}</td>
                            @if(auth()->user()->hasRole('super-admin'))
                            <td>
                                @if($item->company)
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-dark" style="font-size: 0.875rem;">
                                        <i class="bi bi-building me-1 text-secondary"></i>
                                        {{ $item->company->company_name }}
                                    </span>
                                    @if($item->company->phone)
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-telephone me-1"></i> {{ $item->company->phone }}
                                    </small>
                                    @endif
                                </div>
                                @else
                                <span class="badge bg-light text-secondary border" style="font-size: 0.75rem;">
                                    <i class="bi bi-globe me-1"></i> Global / Admin
                                </span>
                                @endif
                            </td>
                            @endif

                            {{-- Dynamic columns --}}
                            @foreach($config as $field => $data)
                            @if($field === 'module_name') @continue @endif
                            @if(empty($data['show_in_table'])) @continue @endif
                            @php $type = $data['type'] ?? 'text'; @endphp
                            <td>
                                @if($type === 'image')
                                @if($item->$field)
                                <img src="{{ asset('storage/' . $item->$field) }}" class="thumb-img" alt="">
                                @else
                                <span class="text-muted small">—</span>
                                @endif

                                @elseif($type === 'video')
                                @if($item->$field)
                                <div class="thumb-video">
                                    <video style="width:100%; height:100%; object-fit:cover;" muted>
                                        <source src="{{ asset('storage/' . $item->$field) }}">
                                    </video>
                                </div>
                                @else
                                <span class="text-muted small">—</span>
                                @endif

                                @elseif($field === 'status')
                                <button type="button"
                                    class="btn-status-toggle {{ $item->status == 1 ? 'badge-soft-success' : ($item->status == 2 ? 'badge-soft-warning' : 'badge-soft-secondary') }} btn-toggle-status"
                                    data-id="{{ $item->id }}"
                                    data-module="{{ $module }}">
                                    {{ $item->status_label }}
                                </button>
                                @elseif($field === 'admin_approved')
                                @if(auth()->user()->hasRole('super-admin'))
                                <div class="dropdown d-inline-block">
                                    <button type="button"
                                        id="statusDropdown-{{ $item->id }}"
                                        class="btn btn-sm dropdown-toggle {{ $item->admin_approved == 1 ? 'badge-soft-success' : ($item->admin_approved == 2 ? 'badge-soft-danger' : 'badge-soft-warning') }}"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        {{ $item->admin_approved == 1 ? "Approved" : ($item->admin_approved == 2 ? "Rejected" : "Pending") }}
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item btn-toggle-admin-status" href="#" data-id="{{ $item->id }}" data-module="{{ $module }}" data-status="0">Pending</a></li>
                                        <li><a class="dropdown-item btn-toggle-admin-status" href="#" data-id="{{ $item->id }}" data-module="{{ $module }}" data-status="1">Approved</a></li>
                                        <li><a class="dropdown-item btn-toggle-admin-status" href="#" data-id="{{ $item->id }}" data-module="{{ $module }}" data-status="2">Rejected</a></li>
                                    </ul>
                                </div>
                                @else
                                <span class="badge {{ $item->admin_approved == 1 ? 'badge-soft-success' : ($item->admin_approved == 2 ? 'badge-soft-danger' : 'badge-soft-warning') }}">
                                    {{ $item->admin_approved == 1 ? "Approved" : ($item->admin_approved == 2 ? "Rejected" : "Pending") }}
                                </span>
                                @endif

                                @elseif($type === 'datetime' || in_array($field, ['start_date', 'end_date', 'published_at']))
                                <span class="text-muted small">
                                    {{ $item->$field ? \Carbon\Carbon::parse($item->$field)->format('d M Y, h:i A') : '—' }}
                                </span>

                                @elseif($type === 'tag')
                                @if($item->$field)
                                @foreach(explode(',', $item->$field) as $tag)
                                <span class="badge badge-soft-secondary me-1" style="font-size: 11px;">{{ trim($tag) }}</span>
                                @endforeach
                                @else
                                <span class="text-muted small">—</span>
                                @endif

                                @else
                                @if(is_array($item->$field))
                                {{ Str::limit(implode(', ', $item->$field), 50) ?: '—' }}
                                @else
                                {{ Str::limit($item->$field, 50) ?: '—' }}
                                @endif
                                @endif
                            </td>
                            @endforeach

                            {{-- Actions --}}
                            <td style="padding-right: 1.5rem;">
                                <div class="d-flex gap-1 justify-content-center flex-wrap">

                                    {{-- Edit --}}
                                    @if(auth()->user()->hasPermission($module . '.edit'))
                                    <a href="{{ route('admin.contents.edit', [$module, $item->id]) }}"
                                        class="btn btn-action btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endif

                                    @if($item->status == 3)
                                    {{-- Restore --}}
                                    <form action="{{ route('admin.contents.restore', [$module, $item->id]) }}"
                                        method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="button" class="btn btn-action btn-outline-warning btn-confirm"
                                            data-title="Restore Content"
                                            data-message="Are you sure you want to restore this record back to index?"
                                            data-type="warning"
                                            title="Restore">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>

                                    {{-- Permanent Delete --}}
                                    @if(auth()->user()->hasPermission($module . '.delete'))
                                    <form action="{{ route('admin.contents.destroy', [$module, $item->id]) }}"
                                        method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-action btn-outline-danger btn-confirm"
                                            data-title="Delete Permanently"
                                            data-message="Are you sure you want to permanently delete this content? This action cannot be undone."
                                            data-type="danger"
                                            title="Delete permanently">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @else
                                    {{-- Move to Trash --}}
                                    @if(auth()->user()->hasPermission($module . '.delete'))
                                    <form action="{{ route('admin.contents.trash', [$module, $item->id]) }}"
                                        method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="button" class="btn btn-action btn-outline-danger btn-confirm"
                                            data-title="Move to Trash"
                                            data-message="Are you sure you want to move this record to trash folder?"
                                            data-type="danger"
                                            title="Move to trash">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @endif
                                    @php
                                    // Generate dynamic frontend preview URLs based on active module and slug
                                    $previewUrl = '#';

                                    if ($module === 'meta_info') {
                                    $previewUrl = match($item->slug) {
                                    'home' => route('web.home') . '?preview=1',
                                    'projects' => route('web.project') . '?preview=1',
                                    'developers' => route('developers.index') . '?preview=1',
                                    'area-guide' => route('area-guides.index') . '?preview=1',
                                    'partner' => route('loans.index') . '?preview=1',
                                     default => route('web.page', $item->slug) . '?preview=1' // Dynamic page routes
                                    };
                                    } elseif ($module === 'project') {

                                    $previewUrl = route('project.details', $item->slug) . '?preview=1';
                                    } elseif ($module === 'area_guide') {

                                    $previewUrl = route('area-guides.show', $item->slug) . '?preview=1';
                                    }elseif ($module === 'blog') {

                                    $previewUrl = route('web.blog.details', $item->slug) . '?preview=1';
                                    }
                                    @endphp

                                    @if($previewUrl !== '#')
                                    <!-- Bootstrap Styled Live Preview Button (Opens in a new tab) -->
                                    <a href="{{ $previewUrl }}" target="_blank" class="btn btn-sm btn-outline-info" title="Preview Live Page">
                                        <i class="bi bi-eye-fill me-1"></i>
                                    </a>
                                    @endif

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="99" class="text-center text-muted py-5 border-bottom">
                                <div class="py-4">
                                    <i class="bi bi-folder-x text-muted" style="font-size: 3rem; opacity: 0.7;"></i>
                                    <p class="mt-3 mb-1 fw-bold text-dark">No records found</p>
                                    <p class="small text-muted">Try modifying your search query or filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


            {{-- Pagination --}}
            @if($records->hasPages())
            <div class="px-4 py-3 border-top d-flex justify-content-end bg-light">
                {{ $records->withQueryString()->links() }}
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Confirm Modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-body text-center p-4">
                <div id="confirmModalIconContainer"
                    class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle"
                    style="width: 72px; height: 72px; font-size: 32px; border: 1px solid;">
                    <i id="confirmModalIcon" class="bi"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2" id="confirmModalTitle"></h5>
                <p class="text-muted small mb-4" id="confirmModalMessage"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light px-4 py-2 fw-semibold text-secondary"
                        data-bs-dismiss="modal" style="border-radius: 8px; font-size: 0.875rem;">Cancel</button>
                    <button type="button" class="btn px-4 py-2 fw-semibold text-white"
                        id="confirmModalBtn" style="border-radius: 8px; font-size: 0.875rem;"></button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ── Inline status toggle (AJAX) ──────────────────────────────────────
        document.querySelectorAll('.btn-toggle-status').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const mod = this.dataset.module;
                const el = this;
                fetch(`/admin/contents/${mod}/${id}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(r => r.json())
                    .then(data => {
                        el.textContent = data.status_label;
                        el.classList.remove('badge-soft-success', 'badge-soft-secondary', 'badge-soft-warning');
                        if (data.status == 1) el.classList.add('badge-soft-success');
                        else if (data.status == 2) el.classList.add('badge-soft-warning');
                        else el.classList.add('badge-soft-secondary');
                    });
            });
        });

        // ── Drag-to-reorder (SortableJS) ─────────────────────────────────────
        const tbody = document.getElementById('sortable-rows');

        if (tbody) {
            new Sortable(tbody, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function() {
                    const order = Array.from(tbody.querySelectorAll('tr[data-id]'))
                        .map(tr => tr.dataset.id);
                    const reorderUrl = "{{ route('admin.contents.reorder', $module) }}";

                    fetch(reorderUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            order
                        }),
                    });
                }
            });
        }

        // ── Bulk select ───────────────────────────────────────────────────────
        const checkAll = document.getElementById('check-all');
        const bulkBar = document.getElementById('bulk-bar');
        const bulkCount = document.getElementById('bulk-count');
        const bulkClear = document.getElementById('bulk-clear');

        function updateBulkBar() {
            const checkedCount = document.querySelectorAll('.row-check:checked').length;
            const bulkBar = document.getElementById('bulk-bar');
            const bulkCount = document.getElementById('bulk-count');

            if (checkedCount > 0) {
                bulkBar.classList.remove('d-none');
                bulkBar.style.display = 'flex';
                bulkCount.textContent = checkedCount + ' items selected';
            } else {
                bulkBar.classList.add('d-none');
                bulkBar.style.display = 'none';
            }
        }

        checkAll.addEventListener('change', function() {
            document.querySelectorAll('.row-check').forEach(c => c.checked = this.checked);
            updateBulkBar();
        });

        document.querySelectorAll('.row-check').forEach(c => {
            c.addEventListener('change', updateBulkBar);
        });

        bulkClear.addEventListener('click', function() {
            document.querySelectorAll('.row-check').forEach(c => c.checked = false);
            checkAll.checked = false;
            updateBulkBar();
        });

        // ── Confirm Modal ─────────────────────────────────────────────────────
        let activeFormToSubmit = null;

        document.querySelectorAll('.btn-confirm').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const title = this.dataset.title || 'Are you sure?';
                const message = this.dataset.message || 'Do you want to proceed?';
                const type = this.dataset.type || 'warning';
                const formSelector = this.dataset.submitForm;

                activeFormToSubmit = formSelector ?
                    document.querySelector(formSelector) :
                    this.closest('form');

                document.getElementById('confirmModalTitle').textContent = title;
                document.getElementById('confirmModalMessage').textContent = message;

                const iconContainer = document.getElementById('confirmModalIconContainer');
                const icon = document.getElementById('confirmModalIcon');
                const confirmBtn = document.getElementById('confirmModalBtn');

                iconContainer.className = 'mb-3 d-inline-flex align-items-center justify-content-center rounded-circle';
                confirmBtn.className = 'btn px-4 py-2 fw-semibold text-white';

                if (type === 'danger') {
                    iconContainer.classList.add('bg-danger-subtle', 'text-danger');
                    iconContainer.style.background = '#fdf2f2';
                    iconContainer.style.borderColor = '#fde8e8';
                    icon.className = 'bi bi-trash3-fill';
                    confirmBtn.classList.add('btn-danger');
                    confirmBtn.textContent = 'Delete';
                } else if (type === 'primary') {
                    iconContainer.classList.add('bg-primary-subtle', 'text-primary');
                    iconContainer.style.background = '#eff6ff';
                    iconContainer.style.borderColor = '#dbeafe';
                    icon.className = 'bi bi-info-circle-fill';
                    confirmBtn.classList.add('btn-primary');
                    confirmBtn.textContent = 'Apply';
                } else {
                    iconContainer.classList.add('bg-warning-subtle', 'text-warning');
                    iconContainer.style.background = '#fffbeb';
                    iconContainer.style.borderColor = '#fff3cd';
                    icon.className = 'bi bi-exclamation-triangle-fill';
                    confirmBtn.classList.add('btn-warning');
                    confirmBtn.textContent = 'Restore';
                }

                new bootstrap.Modal(document.getElementById('confirmModal')).show();
            });
        });

        // Modal confirm — bulk হলে checked ids inject করো
        document.getElementById('confirmModalBtn').addEventListener('click', function() {
            if (!activeFormToSubmit) return;

            if (activeFormToSubmit.id === 'bulk-form') {
                // আগের hidden input গুলো clear করো
                activeFormToSubmit.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
                // checked checkbox গুলো inject করো
                document.querySelectorAll('.row-check:checked').forEach(function(chk) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = chk.value;
                    activeFormToSubmit.appendChild(input);
                });
            }

            activeFormToSubmit.submit();
            activeFormToSubmit = null;
        });

        // Modal বন্ধ হলে reset
        document.getElementById('confirmModal').addEventListener('hidden.bs.modal', function() {
            activeFormToSubmit = null;
        });

    });
    document.addEventListener('click', function(e) {
        const toggleBtn = e.target.closest('.btn-toggle-admin-status');

        if (toggleBtn) {
            e.preventDefault();

            const id = toggleBtn.getAttribute('data-id');
            const module = toggleBtn.getAttribute('data-module');
            const status = toggleBtn.getAttribute('data-status');
            const dropdownButton = document.getElementById(`statusDropdown-${id}`);

            fetch(`/admin/contents/${module}/${id}/toggle-admin-approval`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        dropdownButton.classList.remove('badge-soft-success', 'badge-soft-danger', 'badge-soft-warning');

                        let btnClass = 'badge-soft-warning';
                        let btnText = 'Pending';

                        if (data.status == 1) {
                            btnClass = 'badge-soft-success';
                            btnText = 'Approved';
                        } else if (data.status == 2) {
                            btnClass = 'badge-soft-danger';
                            btnText = 'Rejected';
                        }

                        dropdownButton.classList.add(btnClass);
                        dropdownButton.textContent = btnText;
                    }
                })
                .catch(err => {
                    console.error('Error toggling approval status:', err);
                });
        }
    });
</script>
@endpush