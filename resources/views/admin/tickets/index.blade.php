@extends('layouts.backend')
@section('title', 'Support Tickets')
@section('content')
<div class="container-fluid px-3 px-md-4 py-4" style="font-family: 'Outfit', sans-serif;">

    <div class="row align-items-center justify-content-between mb-4 g-3 border-bottom pb-4 border-light">
        <div class="col-12 col-md-auto">
            <h1 class="h3 mb-1 text-slate-900 fw-bold">Support Tickets</h1>
            <p class="text-muted small mb-0">Manage technical, billing, and general support inquiries.</p>
        </div>
        @if(!auth()->user()->hasRole('super-admin'))
        <div class="col-12 col-md-auto">
            <a href="{{ route('admin.tickets.create') }}" class="btn btn-accent-success btn-sm px-3 py-2 fw-semibold rounded-3 text-white transition-all">
                <i class="bi bi-plus-circle me-1.5"></i> Open New Ticket
            </a>
        </div>
        @endif
    </div>

    @if(session('success'))
    <div class="alert bg-emerald-soft border-0 mb-4 rounded-3 text-emerald fw-semibold py-2 px-3 small">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
    </div>
    @endif
    <!-- Filter Panel -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 analytics-card">
        <form action="{{ route('admin.tickets.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4 col-12">
                <select name="status" class="form-select form-select-sm">
                    <option value="all">All Statuses</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-4 col-12">
                <select name="priority" class="form-select form-select-sm">
                    <option value="all">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>
            <div class="col-md-2 col-12">
                <button type="submit" class="btn btn-primary btn-sm w-100 rounded-3 border-0 bg-accent-success" style="background-color: #10b981;">
                    <i class="bi bi-funnel"></i> Filter
                </button>
            </div>
        </form>
    </div>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden analytics-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13.5px;">
                <thead class="bg-light text-secondary text-uppercase small">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Subject</th>
                        @if(auth()->user()->hasRole('super-admin'))
                        <th class="py-3">Opened By</th>
                        @endif
                        <th class="py-3">Category</th>
                        <th class="py-3">Priority</th>
                        <th class="py-3">Read Status</th>

                        <th class="py-3">Status</th>
                        <th class="py-3">Date</th>
                        <th class="text-center pe-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($tickets as $ticket)
                    @php
                    $isUnread = (auth()->user()->hasRole('super-admin') && !$ticket->is_read_admin) ||
                    (!auth()->user()->hasRole('super-admin') && !$ticket->is_read_user);
                    @endphp
                    <tr>
                        <td class="ps-4 py-3 fw-bold">#{{ $ticket->id }}</td>
                        <td class="py-3 fw-semibold text-slate-900">{{ $ticket->subject }}</td>
                        @if(auth()->user()->hasRole('super-admin'))
                        <td class="py-3">{{ $ticket->user->name ?? 'User' }}</td>
                        @endif
                        <td class="py-3"><span class="badge bg-light text-dark border px-2 py-1">{{ $ticket->category }}</span></td>
                        <td class="py-3">
                            @if($ticket->priority === 'high')
                            <span class="badge bg-rose-soft border border-rose-soft px-2 py-1">High</span>
                            @elseif($ticket->priority === 'medium')
                            <span class="badge bg-amber-soft border border-amber-soft px-2 py-1">Medium</span>
                            @else
                            <span class="badge bg-emerald-soft border border-emerald-soft px-2 py-1">Low</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($isUnread)
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-2 py-1 rounded-pill d-inline-flex align-items-center gap-1">
                                <span class="spinner-grow spinner-grow-sm text-danger" role="status" style="width: 6px; height: 6px;"></span>
                                New Message
                            </span>
                            @else
                            <span class="badge bg-light text-muted border px-2 py-1 rounded-pill">Read</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($ticket->status === 'open')
                            <span class="badge bg-emerald-soft text-emerald px-2 py-1 rounded-pill">Open</span>
                            @elseif($ticket->status === 'replied')
                            <span class="badge bg-sky-soft text-sky px-2 py-1 rounded-pill">Replied</span>
                            @else
                            <span class="badge bg-light text-muted border px-2 py-1 rounded-pill">Closed</span>
                            @endif
                        </td>
                        <td class="py-3 text-muted">{{ $ticket->created_at->format('d M, Y') }}</td>
                        <td class="py-3 text-center pe-4">
                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-link btn-sm text-decoration-none fw-bold text-accent px-0">
                                View Conversation <i class="bi bi-arrow-right small ms-0.5"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-open fs-2 mb-2 text-secondary opacity-50 d-block"></i>
                            <span class="small">No support tickets found.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4">
            {{ $tickets->links() }}
        </div>
        @endif
    </div>
</div>

<style scoped>
    #main {
        background: #f8fafc !important;
    }

    .btn-accent-success {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
    }

    .btn-accent-success:hover {
        background-color: #059669 !important;
    }

    .card.analytics-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
    }

    .bg-emerald-soft {
        background: #ecfdf5;
        color: #059669;
    }

    .bg-sky-soft {
        background: #f0f9ff;
        color: #0284c7;
    }

    .bg-rose-soft {
        background: #fff1f2;
        color: #e11d48;
    }

    .bg-amber-soft {
        background: #fffbeb;
        color: #d97706;
    }

    .text-accent {
        color: #10b981;
    }
</style>
@endsection
