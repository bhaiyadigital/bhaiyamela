@extends('layouts.backend')
@section('title', 'Settings')
@section('content')

<div class="container-fluid mt-4">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- Left column --}}
            <div class="col-md-7">

                {{-- Site identity --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-globe me-2 text-primary"></i>Site Identity
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Site Name</label>
                                <input type="text" name="site_name" class="form-control"
                                       value="{{ old('site_name', $setting->site_name) }}"
                                       placeholder="e.g. MyCompany">
                                @error('site_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Slogan / Tagline</label>
                                <input type="text" name="site_slogan" class="form-control"
                                       value="{{ old('site_slogan', $setting->site_slogan) }}"
                                       placeholder="e.g. Building dreams since 1990">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', $setting->email) }}"
                                       placeholder="info@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone', $setting->phone) }}"
                                       placeholder="+880 1700-000000">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Address</label>
                                <textarea name="address" class="form-control" rows="2"
                                          placeholder="Office address...">{{ old('address', $setting->address) }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- SEO & Legal --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-search me-2 text-primary"></i>SEO & Legal
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        <div class="row g-3">

                            {{-- Index / NoIndex --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">
                                    Search Engine Index <span class="text-danger">*</span>
                                </label>
                                <select name="meta_index" class="form-select">
                                    <option value="index"   {{ old('meta_index', $setting->meta_index) === 'index'   ? 'selected' : '' }}>
                                        index — Allow indexing
                                    </option>
                                    <option value="noindex" {{ old('meta_index', $setting->meta_index) === 'noindex' ? 'selected' : '' }}>
                                        noindex — Block indexing
                                    </option>
                                </select>
                                <small class="text-muted">Applied as default to all pages.</small>
                            </div>

                            {{-- Privacy Policy URL --}}
                            <div class="col-md-8">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Privacy Policy URL</label>
                                <input type="url" name="privacy_policy_url" class="form-control"
                                       value="{{ old('privacy_policy_url', $setting->privacy_policy_url) }}"
                                       placeholder="https://example.com/privacy-policy">
                                @error('privacy_policy_url') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Terms URL --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Terms & Conditions URL</label>
                                <input type="url" name="terms_url" class="form-control"
                                       value="{{ old('terms_url', $setting->terms_url) }}"
                                       placeholder="https://example.com/terms">
                                @error('terms_url') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            {{-- Footer credit --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size:.875rem;">Footer Credit</label>
                                <input type="text" name="footer_credit" class="form-control"
                                       value="{{ old('footer_credit', $setting->footer_credit) }}"
                                       placeholder="© 2025 MyCompany. All rights reserved.">
                            </div>

                        </div>

                    </div>
                </div>

                {{-- Map embed --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-map me-2 text-primary"></i>Google Map
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label fw-semibold" style="font-size:.875rem;">Map Embed Code</label>
                        <textarea name="map_embed" class="form-control" rows="3"
                                  placeholder="Paste Google Maps embed iframe code here...">{{ old('map_embed', $setting->map_embed) }}</textarea>
                        @if($setting->map_embed)
                            <div class="mt-2 rounded overflow-hidden" style="height:140px;">
                                {!! $setting->map_embed !!}
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Right column --}}
            <div class="col-md-5">

                {{-- Logo --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-image me-2 text-primary"></i>Logo
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        @if($setting->logo)
                            <div class="mb-3 p-3 bg-light rounded text-center" style="border:1px dashed #dee2e6;">
                                <img src="{{ asset('storage/' . $setting->logo) }}"
                                     style="max-height:80px;max-width:100%;object-fit:contain;" alt="Logo">
                                <div class="text-muted mt-2" style="font-size:.75rem;">Current logo</div>
                            </div>
                        @endif

                        <input type="file" name="logo" id="logo-input"
                               class="form-control {{ $errors->has('logo') ? 'is-invalid' : '' }}"
                               accept="image/*"
                               onchange="previewImage(this, 'logo-preview')">
                        @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">PNG / SVG recommended. Max 2MB.</small>
                        <div id="logo-preview" class="mt-2"></div>

                    </div>
                </div>

                {{-- Favicon --}}
                <div class="card dashboard-card mb-4">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-star me-2 text-primary"></i>Favicon
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        @if($setting->favicon)
                            <div class="mb-3 p-3 bg-light rounded text-center" style="border:1px dashed #dee2e6;">
                                <img src="{{ asset('storage/' . $setting->favicon) }}"
                                     style="width:32px;height:32px;object-fit:contain;" alt="Favicon">
                                <div class="text-muted mt-2" style="font-size:.75rem;">Current favicon</div>
                            </div>
                        @endif

                        <input type="file" name="favicon" id="favicon-input"
                               class="form-control {{ $errors->has('favicon') ? 'is-invalid' : '' }}"
                               accept="image/*"
                               onchange="previewImage(this, 'favicon-preview')">
                        @error('favicon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">ICO / PNG 32×32 recommended. Max 512KB.</small>
                        <div id="favicon-preview" class="mt-2"></div>

                    </div>
                </div>

                {{-- Save --}}
                <div class="card dashboard-card">
                    <div class="card-body p-4">

                        @if(session('success'))
                            <div class="alert border-0 mb-3 d-flex align-items-center gap-2"
                                 style="background:#e8f5e9;color:#2e7d32;border-radius:8px;">
                                <i class="bi bi-check-circle-fill fs-5"></i>
                                <span class="fw-semibold small">{{ session('success') }}</span>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-success w-100 fw-semibold">
                            <i class="bi bi-check-lg me-1"></i> Save Settings
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
        img.className = 'img-thumbnail mt-1';
        img.style.cssText = 'max-height:80px;max-width:100%;object-fit:contain;';
        preview.appendChild(img);
    }
}
</script>
@endpush