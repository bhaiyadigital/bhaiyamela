@extends('layouts.backend')
@section('title', 'Company Profile Settings')
@section('content')

<div class="container-fluid mt-4">
    <form action="{{ $id !== null ? route('admin.company-profile.admin.update', $company->id) : route('admin.company-profile.update') }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            <div class="col-md-7">

                {{-- Company Identity --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-building me-2 text-primary"></i>Company Identity
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Company Name <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" class="form-control" required
                                       value="{{ old('company_name', $company->company_name) }}"
                                       placeholder="e.g. Apex Real Estate">
                                @error('company_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Slogan / Tagline</label>
                                <input type="text" name="tagline" class="form-control"
                                       value="{{ old('tagline', $company->tagline) }}"
                                       placeholder="e.g. Building dreams with trust">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required
                                       value="{{ old('email', $company->email) }}"
                                       placeholder="company@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" required
                                       value="{{ old('phone', $company->phone) }}"
                                       placeholder="+880 1700-000000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">WhatsApp Number</label>
                                <input type="text" name="whatsapp" class="form-control"
                                       value="{{ old('whatsapp', $company->whatsapp) }}"
                                       placeholder="+880 1700-000000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Website URL</label>
                                <input type="url" name="website_url" class="form-control"
                                       value="{{ old('website_url', $company->website_url) }}"
                                       placeholder="https://example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Foundation Date</label>
                                <input type="date" name="foundation_date" class="form-control"
                                       value="{{ old('foundation_date', $company->foundation_date) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Founder Name</label>
                                <input type="text" name="founder_name" class="form-control"
                                       value="{{ old('founder_name', $company->founder_name) }}"
                                       placeholder="e.g. John Doe">
                            </div>
                        </div>

                    </div>
                </div>

                {{-- About & Address --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-file-earmark-text me-2 text-primary"></i>About & Address
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">About Us</label>
                                <textarea name="about_us" class="form-control" rows="4"
                                          placeholder="Write some details about your company...">{{ old('about_us', $company->about_us) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Office Address</label>
                                <textarea name="address" class="form-control" rows="2"
                                          placeholder="Write corporate office address...">{{ old('address', $company->address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Legal & Metadata --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-shield-check me-2 text-primary"></i>Legal & Business Info
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Trade License No</label>
                                <input type="text" name="trade_license" class="form-control"
                                       value="{{ old('trade_license', $company->trade_license) }}"
                                       placeholder="e.g. TR-54215-XP">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">TIN / Tax ID</label>
                                <input type="text" name="tin_id" class="form-control"
                                       value="{{ old('tin_id', $company->tin_id) }}"
                                       placeholder="e.g. 5421-5215-5215">
                            </div>
                   
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Industry / Category</label>
                                <input type="text" name="industry" class="form-control"
                                       value="{{ old('industry', $company->industry) }}"
                                       placeholder="e.g. Real Estate, IT Agency">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Maps embed --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-map me-2 text-primary"></i>Google Map
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label fw-semibold" style="font-size:.875rem;">Map Embed Iframe Code</label>
                        <textarea name="maps_embed" class="form-control" rows="3"
                                  placeholder="Paste Google Maps embed iframe code here...">{{ old('maps_embed', $company->maps_embed) }}</textarea>
                        @if($company->maps_embed)
                            <div class="mt-2 rounded overflow-hidden" style="height:140px;">
                                {!! $company->maps_embed !!}
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Right column (ব্র্যান্ডিং এবং সোশ্যাল মিডিয়া) --}}
            <div class="col-md-5">

                {{-- Logo Upload --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-image me-2 text-primary"></i>Company Logo
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        @if($company->company_logo)
                            <div class="mb-3 p-3 bg-light rounded text-center" style="border:1px dashed #dee2e6;">
                                <img src="{{ asset('storage/' . $company->company_logo) }}"
                                     style="max-height:80px;max-width:100%;object-fit:contain;" alt="Logo">
                                <div class="text-muted mt-2" style="font-size:.75rem;">Current logo</div>
                            </div>
                        @endif

                        <input type="file" name="company_logo" class="form-control" accept="image/*"
                               onchange="previewImage(this, 'logo-preview')">
                        <small class="text-muted d-block mt-1">PNG / WebP recommended. Max 2MB.</small>
                        <div id="logo-preview" class="mt-2"></div>

                    </div>
                </div>

        

                {{-- Social Media Links --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-share me-2 text-primary"></i>Social Media Links
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold small"><i class="bi bi-facebook text-primary me-1"></i> Facebook URL</label>
                                <input type="url" name="facebook" class="form-control form-control-sm"
                                       value="{{ old('facebook', $socials['facebook']) }}" placeholder="https://facebook.com/company">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small"><i class="bi bi-linkedin text-info me-1"></i> LinkedIn URL</label>
                                <input type="url" name="linkedin" class="form-control form-control-sm"
                                       value="{{ old('linkedin', $socials['linkedin']) }}" placeholder="https://linkedin.com/company">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small"><i class="bi bi-twitter-x text-dark me-1"></i> Twitter / X URL</label>
                                <input type="url" name="twitter" class="form-control form-control-sm"
                                       value="{{ old('twitter', $socials['twitter']) }}" placeholder="https://twitter.com/company">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small"><i class="bi bi-instagram text-danger me-1"></i> Instagram URL</label>
                                <input type="url" name="instagram" class="form-control form-control-sm"
                                       value="{{ old('instagram', $socials['instagram']) }}" placeholder="https://instagram.com/company">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small"><i class="bi bi-youtube text-danger me-1"></i> YouTube Channel</label>
                                <input type="url" name="youtube" class="form-control form-control-sm"
                                       value="{{ old('youtube', $socials['youtube']) }}" placeholder="https://youtube.com/channel">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Save Button & Status Alerts --}}
                <div class="card dashboard-card">
                    <div class="card-body p-4">

                        @if(session('success'))
                            <div class="alert border-0 mb-3 d-flex align-items-center gap-2"
                                 style="background:#e8f5e9;color:#2e7d32;border-radius:8px;">
                                <i class="bi bi-check-circle-fill fs-5"></i>
                                <span class="fw-semibold small">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert border-0 mb-3 d-flex align-items-center gap-2"
                                 style="background:#ffebee;color:#c62828;border-radius:8px;">
                                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                                <span class="fw-semibold small">{{ session('error') }}</span>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-success w-100 fw-semibold py-3">
                            <i class="bi bi-check-lg me-1"></i> Save Profile Details
                        </button>

                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = '';
    if (input.files && input.files[0]) {
        const img    = document.createElement('img');
        img.src      = URL.createObjectURL(input.files[0]);
        img.className = 'img-thumbnail mt-2';
        
        if(previewId === 'cover-preview') {
            img.style.cssText = 'max-height:100px;width:100%;object-fit:cover;border-radius:6px;';
        } else {
            img.style.cssText = 'max-height:80px;max-width:100%;object-fit:contain;';
        }
        
        preview.appendChild(img);
    }
}
</script>
@endpush
