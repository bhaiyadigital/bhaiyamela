@extends('layouts.backend')
@section('title', 'Open Support Ticket')
@section('content')
<div class="container-fluid px-3 px-md-4 py-4" style="font-family: 'Outfit', sans-serif;">

    <div class="row align-items-center justify-content-between mb-4 g-3 border-bottom pb-4 border-light">
        <div class="col-12 col-md-auto">
            <h1 class="h3 mb-1 text-slate-900 fw-bold">Open Support Ticket</h1>
            <p class="text-muted small mb-0">Briefly explain your query. Our agent will respond as soon as possible.</p>
        </div>
        <div class="col-12 col-md-auto">
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                <i class="bi bi-arrow-left"></i> Back to Tickets
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 analytics-card p-4">
                <form action="{{ route('admin.tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">Subject / Title <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Inquiries regarding billing issue" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="Technical">Technical Support</option>
                                <option value="Billing">Billing & Sales</option>
                                <option value="General">General Inquiry</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-secondary">Priority <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select" required>
                                <option value="low">Low Priority</option>
                                <option value="medium" selected>Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                        <div class="col-12 flex flex-col gap-1.5 mt-2">
                            <label class="text-xs font-bold text-gray-500 uppercase pl-1">Attachment (Image/PDF)</label>
                            <input type="file" name="attachment" class="w-full bg-gray-50 border border-gray-200 px-5 py-3 rounded-2xl text-sm font-semibold outline-none">
                            <span class="text-[10px] text-gray-400 pl-1">Max file size: 5MB</span>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-secondary">Elaborate Your Problem <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="6" placeholder="Describe your question or issue in details..." required></textarea>
                        </div>
                        <div class="col-12 pt-2">
                            <button type="submit" class="btn btn-accent-success px-4 py-2.5 rounded-3 fw-semibold border-0">
                                <i class="bi bi-send-fill me-1.5"></i> Open Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style scoped>
    #main {
        background: #f8fafc !important;
    }

    .form-control,
    .form-select {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        padding: 10px 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
    }

    .btn-accent-success {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        color: #ffffff !important;
    }

    .card.analytics-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
    }
</style>
@endsection
