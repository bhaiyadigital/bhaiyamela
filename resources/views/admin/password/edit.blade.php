@extends('layouts.backend')
@section('title', 'Update Pasword')
@section('content')
<div class="container-fluid px-4 py-4" style="font-family: 'Outfit', sans-serif;">
    
    <!-- Top Header -->
    <div class="row align-items-center justify-content-between mb-4 g-3">
        <div class="col-12 col-md-auto">
            <h1 class="h3 mb-0 text-dark font-weight-bold">Change Password</h1>
            <p class="text-muted mb-0 small">Update your account password regularly to keep it secure.</p>
        </div>
        <div class="col-12 col-md-auto">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <!-- Session Success Alert -->
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <!-- Session Error Alert -->
            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <!-- Form Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Current Password Input -->
                        <div class="mb-4">
                            <label for="current_password" class="form-label text-dark font-weight-bold small">Current Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <input type="password" 
                                       name="current_password" 
                                       id="current_password" 
                                       class="form-control border-start-0 text-base @error('current_password') is-invalid @enderror" 
                                       placeholder="Enter current password" 
                                       required>
                                @error('current_password')
                                    <span class="invalid-feedback ps-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- New Password Input -->
                        <div class="mb-4">
                            <label for="password" class="form-label text-dark font-weight-bold small">New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="form-control border-start-0 text-base @error('password') is-invalid @enderror" 
                                       placeholder="Enter new password" 
                                       required>
                                @error('password')
                                    <span class="invalid-feedback ps-2" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-text text-muted small mt-1.5" style="font-size: 11px;">
                                Password must be at least 8 characters long and contain numbers or symbols.
                            </div>
                        </div>

                        <!-- Confirm New Password Input -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label text-dark font-weight-bold small">Confirm New Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </span>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="form-control border-start-0 text-base" 
                                       placeholder="Confirm new password" 
                                       required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success text-white d-inline-flex align-items-center justify-content-center gap-2 py-2.5" style="background-color: #34a487; border-color: #34a487; font-size: 14px; font-weight: 600;">
                                <i class="bi bi-save"></i> Update Password
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection