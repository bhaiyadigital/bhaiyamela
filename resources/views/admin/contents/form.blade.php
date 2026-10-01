@extends('layouts.backend')

@section('title', (isset($content) ? 'Edit' : 'Create') . ' ' . ucwords(str_replace(['-', '_'], ' ', $module)))

@section('content')

<style>
    .sortable-item {
        cursor: grab;
    }

    .sortable-item:active {
        cursor: grabbing;
    }

    .sortable-item.sortable-ghost {
        opacity: 0.3;
        transform: scale(0.95);
    }

    .media-dropzone {
        border: 3px dotted #8fa4b8;
        border-radius: 12px;
        padding: 28px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        background: #f8faff;
        position: relative;
    }

    .media-dropzone:hover,
    .media-dropzone.dragover {
        border-color: #0d6efd;
        background: #eff5ff;
    }

    .media-dropzone .icon {
        font-size: 2.2rem;
        color: #8fa4b8;
        margin-bottom: 6px;
        transition: color 0.3s ease;
    }

    .media-dropzone:hover .icon,
    .media-dropzone.dragover .icon {
        color: #0d6efd;
    }

    .preview-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .preview-item {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        background: #f1f1f1;
        border: 1px solid #ddd;
    }

    .preview-item img,
    .preview-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .preview-item.video-preview {
        background: #000;
        width: 120px;
        height: 90px;
    }

    .preview-item .btn-remove {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        transition: all 0.2s;
        z-index: 10;
        font-size: 11px;
    }

    .preview-item .btn-remove:hover {
        background: #dc3545;
        transform: scale(1.1);
    }

    .preview-item .badge-order {
        position: absolute;
        top: 5px;
        left: 5px;
        font-size: 10px;
        padding: 3px 5px;
        z-index: 10;
    }

    .badge-tag {
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .badge-tag i:hover {
        color: #ffc107 !important;
    }

    .form-section {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        margin-bottom: 1.25rem;
        overflow: hidden;
    }

    .form-section-header {
        background: #f8f9fa;
        padding: 10px 16px;
        border-bottom: 1px solid #e9ecef;
        font-size: 0.82rem;
        font-weight: 600;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-section-body {
        padding: 1rem 1rem 0.25rem;
    }
</style>

<div class="container-fluid mt-2">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form
        action="{{ isset($content) ? route('admin.contents.update', [$module, $content->id]) : route('admin.contents.store', $module) }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf
        @if(isset($content)) @method('PUT') @endif

        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0 text-dark">
                {{ isset($content) ? 'Edit' : 'Create' }}
                {{ $config['module_name']['label'] ?? ucwords(str_replace(['-','_'],' ', $module)) }}
            </h5>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check2-circle me-1"></i>
                    {{ isset($content) ? 'Update' : 'Save' }}
                </button>
                <a href="{{ route('admin.contents.index', $module) }}" class="btn btn-outline-secondary px-3">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

        <div class="row g-3 align-items-start">

            {{-- ════════════════════════════════════════
                 LEFT COLUMN
            ════════════════════════════════════════ --}}
            <div class="col-xl-8 col-lg-7">

                @php
                $leftTypes = ['text','slug','url','number','textarea','editor','select','features','extra','parent_id','datetime','select_status'];
                $seoFields = ['meta_title','meta_description','meta_keywords'];
                $hasLeftFields = false;
                @endphp

                @foreach($config as $field => $data)
                @if($field === 'module_name' || $field === 'sort_order' || $field === 'admin_approved' || in_array($field, $seoFields)) @continue @endif
                @if(in_array($data['type'] ?? 'text', $leftTypes)) @php $hasLeftFields = true; @endphp @endif
                @endforeach

                @if($hasLeftFields)
                <div class="card card-outline card-success mb-0">
                    <div class="card-body pb-1">

                        @if(auth()->user()->hasRole('super-admin'))
                        <div class="mb-4">
                            <label for="company_id" class="form-label fw-semibold" style="font-size:.875rem;">Assign to Company (Optional)</label>
                            <select name="company_id" id="company_id" class="form-select">
                                <option value="">Global/No Company </option>
                                @foreach(\App\Models\Company::all() as $company)
                                <option value="{{ $company->id }}" {{ (isset($content) && $content->company_id == $company->id) ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        @foreach($config as $field => $data)
                        @if($field === 'module_name' || $field === 'sort_order' || $field === 'admin_approved' ||$field=='prev_slug' || in_array($field, $seoFields)) @continue @endif
                        @php
                        $type = $data['type'] ?? 'text';
                        $label = $data['label'] ?? ucwords(str_replace(['-','_'],' ', $field));
                        $required = !empty($data['required']) ? 'required' : '';
                        $old = old($field, isset($content) ? $content->$field : '');
                        $hasError = $errors->has($field);
                        @endphp

                        @if(!in_array($type, $leftTypes)) @continue @endif

                        <div class="mb-3">
                            <label for="{{ $field }}" class="form-label fw-semibold" style="font-size:.875rem;">
                                {{ $label }}
                                @if(!empty($data['required'])) <span class="text-danger">*</span> @endif
                            </label>

                            @if($type === 'text')
                            @if($field === 'title' && isset($config['slug']))
                            <div class="input-group">
                                <input type="text" class="form-control {{ $hasError ? 'is-invalid':'' }}" id="{{ $field }}" name="{{ $field }}" value="{{ $old }}" {{ $required }}>
                                <button type="button" class="btn btn-outline-secondary btn-generate-slug"><i class="bi bi-link-45deg"></i> Slug</button>
                            </div>
                            @else
                            <input type="text" class="form-control {{ $hasError ? 'is-invalid':'' }}" id="{{ $field }}" name="{{ $field }}" value="{{ $old }}" {{ $required }}>
                            @endif

                            @elseif($type === 'slug' || $field === 'slug')
                            <input type="text" class="form-control {{ $hasError ? 'is-invalid' : '' }}"
                                id="{{ $field }}" name="{{ $field }}"
                                value="{{ $old }}" {{ $required }}>
                            @if (isset($content) && $content->prev_slug)
                            <small class="text-muted">Previous: {{ $content->prev_slug }}</small>
                            @endif

                            @elseif($type === 'url')
                            <input type="url" class="form-control {{ $hasError ? 'is-invalid':'' }}" id="{{ $field }}" name="{{ $field }}" value="{{ $old }}" {{ $required }}>

                            @elseif($type === 'number')
                            <input type="number" class="form-control {{ $hasError ? 'is-invalid':'' }}" id="{{ $field }}" name="{{ $field }}" value="{{ $old }}" {{ $required }}>

                            @elseif($type === 'textarea')
                            <textarea class="form-control {{ $hasError ? 'is-invalid':'' }}" id="{{ $field }}" name="{{ $field }}" rows="3" {{ $required }}>{{ $old }}</textarea>

                            @elseif($type === 'editor')
                            <textarea class="form-control ckeditor {{ $hasError ? 'is-invalid':'' }}" id="{{ $field }}" name="{{ $field }}" rows="6">{{ $old }}</textarea>

                            @elseif($type === 'datetime')
                            <input type="datetime-local" class="form-control {{ $hasError ? 'is-invalid':'' }}" id="{{ $field }}" name="{{ $field }}" value="{{ $old ? \Carbon\Carbon::parse($old)->format('Y-m-d\TH:i') : '' }}" {{ $required }}>

                            @elseif($type === 'select' && $field === 'status')
                            <select name="{{ $field }}" id="{{ $field }}"
                                class="form-select {{ $hasError ? 'is-invalid':'' }}" {{ $required }}
                                onchange="toggleScheduledAt(this.value)">
                                <option value="1" {{ (string)$old==='1'?'selected':'' }}>Active</option>
                                <option value="0" {{ (string)$old==='0'?'selected':'' }}>Inactive</option>
                                <option value="2" {{ (string)$old==='2'?'selected':'' }}>Scheduled</option>
                                <option value="3" {{ (string)$old==='3'?'selected':'' }}>Trash</option>
                            </select>

                            {{-- Scheduled At Field --}}
                            <div id="scheduled_at_wrapper" class="mt-2"
                                style="display: {{ (string) $old =='2' ? 'block' : 'none' }};">
                                <label for="scheduled_at" class="form-label">
                                    Scheduled At <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local"
                                    name="scheduled_at"
                                    id="scheduled_at"
                                    class="form-control {{ $errors->has('scheduled_at') ? 'is-invalid' : '' }}"
                                    value="{{ old('scheduled_at', isset($content) ? $content->scheduled_at : '') }}">

                                @error('scheduled_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @elseif($field === 'features')
                            @php
                            $predefinedKeys = $data['keys'] ?? [];
                            $rawFeatures = isset($content) ? ($content->features ?? []) : [];
                            $featureItems = [];

                            // যদি ফর্ম সাবমিট করার পর কোনো ভ্যালিডেশন এরর আসে (old input রিড করতে)
                            if (old('feature_keys')) {
                            foreach (old('feature_keys') as $index => $k) {
                            $featureItems[] = [
                            'key' => $k,
                            'val' => old('feature_values')[$index] ?? ''
                            ];
                            }
                            } else {
                            // সাধারণ সময়ে এডিট করার ক্ষেত্রে
                            foreach ($rawFeatures as $key => $val) {
                            if (is_array($val) && isset($val['key'])) {
                            // নতুন সেভ হওয়া 'Array of Objects' ফরম্যাট রিড করবে
                            $featureItems[] = [
                            'key' => $val['key'],
                            'val' => $val['value'] ?? ''
                            ];
                            } elseif (is_numeric($key) && !is_array($val)) {
                            $featureItems[] = [
                            'key' => $val,
                            'val' => ''
                            ];
                            } else {
                            // পূর্বের সেভ করা নরমাল 'Associative Array' ফরম্যাট রিড করবে
                            $featureItems[] = [
                            'key' => $key,
                            'val' => $val
                            ];
                            }
                            }
                            }
                            @endphp

                            <div id="features-wrapper" class="mb-2">
                                @forelse($featureItems as $item)
                                @php
                                $fKey = $item['key'];
                                $fVal = $item['val'];
                                $isCustomKey = !empty($predefinedKeys) && !in_array($fKey, $predefinedKeys);
                                @endphp
                                <div class="input-group mb-2 feature-row">
                                    <span class="input-group-text drag-handle" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span>

                                    @if(!empty($predefinedKeys))
                                    <select class="form-select feature-key-select" name="{{ $isCustomKey ? '' : 'feature_keys[]' }}" style="max-width: 200px;">
                                        <option value="">-- Select Key --</option>
                                        @foreach($predefinedKeys as $pKey)
                                        <option value="{{ $pKey }}" {{ $fKey === $pKey ? 'selected' : '' }}>{{ $pKey }}</option>
                                        @endforeach
                                        <option value="custom" {{ $isCustomKey ? 'selected' : '' }}>Custom...</option>
                                    </select>
                                    <input type="text" class="form-control custom-key-input {{ $isCustomKey ? '' : 'd-none' }}"
                                        name="{{ $isCustomKey ? 'feature_keys[]' : '' }}"
                                        placeholder="Custom Key Name"
                                        value="{{ $isCustomKey ? $fKey : '' }}">
                                    @else
                                    <input type="text" class="form-control" name="feature_keys[]" placeholder="Key (e.g. WiFi)" value="{{ $fKey }}">
                                    @endif

                                    <input type="text" class="form-control" name="feature_values[]" placeholder="Value (e.g. Free)" value="{{ $fVal }}">
                                    <button type="button" class="btn btn-outline-danger btn-remove-feature">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                @empty
                                <div class="input-group mb-2 feature-row">
                                    <span class="input-group-text drag-handle" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span>

                                    @if(!empty($predefinedKeys))
                                    <select class="form-select feature-key-select" name="feature_keys[]" style="max-width: 200px;">
                                        <option value="">-- Select Key --</option>
                                        @foreach($predefinedKeys as $pKey)
                                        <option value="{{ $pKey }}">{{ $pKey }}</option>
                                        @endforeach
                                        <option value="custom">Custom...</option>
                                    </select>
                                    <input type="text" class="form-control custom-key-input d-none" placeholder="Custom Key Name">
                                    @else
                                    <input type="text" class="form-control" name="feature_keys[]" placeholder="Key (e.g. WiFi)">
                                    @endif
                                    <input type="text" class="form-control" name="feature_values[]" placeholder="Value (e.g. Free)">
                                    <button type="button" class="btn btn-outline-danger btn-remove-feature">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                @endforelse
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-add-feature">
                                <i class="bi bi-plus-circle"></i> Add Feature
                            </button>

                            <template id="feature-row-template">
                                <div class="input-group mb-2 feature-row">
                                    <span class="input-group-text drag-handle" style="cursor: grab;"><i class="bi bi-grip-vertical"></i></span>

                                    @if(!empty($predefinedKeys))
                                    <select class="form-select feature-key-select" name="feature_keys[]" style="max-width: 200px;">
                                        <option value="">-- Select Key --</option>
                                        @foreach($predefinedKeys as $pKey)
                                        <option value="{{ $pKey }}">{{ $pKey }}</option>
                                        @endforeach
                                        <option value="custom">Custom...</option>
                                    </select>
                                    <input type="text" class="form-control custom-key-input d-none" placeholder="Custom Key Name">
                                    @else
                                    <input type="text" class="form-control" name="feature_keys[]" placeholder="Key (e.g. WiFi)">
                                    @endif
                                    <input type="text" class="form-control" name="feature_values[]" placeholder="Value (e.g. Free)">
                                    <button type="button" class="btn btn-outline-danger btn-remove-feature"><i class="bi bi-trash"></i></button>
                                </div>
                            </template>
                            @elseif($type === 'select_status')
                                <select name="{{ $field }}" id="{{ $field }}" class="form-select">
                                    <option value="1" {{ (string)$old === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ (string)$old === '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            @elseif($type === 'extra' || $field === 'extra')
                                @php
                                    $extraVal = isset($content) ? $content->extra : [];
                                    // Handles raw JSON string or PHP array fallback smoothly
                                    if (is_string($extraVal)) {
                                        $extraVal = json_decode($extraVal, true) ?? [];
                                    }
                                    $extraVal = is_array($extraVal) ? $extraVal : [];

                                    // Predefined real-estate features list
                                    $predefinedFeatures = [
                                        'Security', 'Lift', 'Fire exit', 'WASA connection', 'Self Water supply', 
                                        'Hot water', 'Cylinder Gas', 'Electricity', 'Generator', 'Intercom', 
                                        'CCTV', 'Wi-Fi connectivity', 'Satellite or cable TV', 'Electronic security', 
                                        'Garden', 'Solar panels', 'Servant Room', 'Servant Toilet', 'Fire Protection'
                                    ];
                                @endphp
                                
                                <!-- Compact Bootstrap Grid for Checkboxes (Self-contained and layout friendly) -->
                                <div class="row g-2 mt-1">
                                    @foreach($predefinedFeatures as $feature)
                                        <div class="col-sm-6 col-md-4 col-lg-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="extra[]" value="{{ $feature }}" 
                                                       id="extra-{{ \Illuminate\Support\Str::slug($feature) }}"
                                                       {{ in_array($feature, $extraVal) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold text-dark" style="font-size: .8rem; cursor: pointer;" for="extra-{{ \Illuminate\Support\Str::slug($feature) }}">
                                                    {{ $feature }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>                            {{-- Parent / Category Selection --}}
                            @elseif($field === 'parent_id')
                            <select name="parent_id" id="parent_id" class="form-select {{ $hasError?'is-invalid':'' }}" {{ $required }}>
                                <option value="">-- Select Category --</option>
                                @foreach(\App\Models\Content::module($data['parent_module'] ?? 'category')->active()->sorted()->get() as $parent)
                                <option value="{{ $parent->id }}" {{ (string)($old?:request('parent'))===(string)$parent->id?'selected':'' }}>{{ $parent->title }}</option>
                                @endforeach
                            </select>

                            {{-- [ADD] Destination Selection Support --}}
                            @elseif($field === 'destination_id')
                            <select name="destination_id" id="destination_id" class="form-select {{ $hasError?'is-invalid':'' }}" {{ $required }}>
                                <option value="">-- Select Destination --</option>
                                @foreach(\App\Models\Content::module($data['destination_module'] ?? 'destination')->active()->sorted()->get() as $destination)
                                <option value="{{ $destination->id }}" {{ (string)($old?:request('destination'))===(string)$destination->id?'selected':'' }}>{{ $destination->title }}</option>
                                @endforeach
                            </select>
                            @endif

                            @error($field)
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- ════════════════════════════════════════
                 RIGHT COLUMN
            ════════════════════════════════════════ --}}
            <div class="col-xl-4 col-lg-5">

                {{-- ── Thumbnail / Single Image ── --}}
                @foreach($config as $field => $data)
                @if($field==='module_name' || ($data['type']??'') !== 'image') @continue @endif
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="bi bi-image me-1 text-primary"></i>{{ $data['label'] ?? 'Image' }}
                        @if(!empty($data['required'])) <span class="text-danger">*</span> @endif
                    </div>
                    <div class="form-section-body">
                        <input type="hidden" name="hidden_{{ $field }}" id="hidden-{{ $field }}" value="{{ isset($content) ? ($content->$field ?? '') : '' }}">
                        <div class="{{ (isset($content) && $content->$field) ? '' : 'd-none' }}" id="preview-wrap-{{ $field }}">
                            <div class="preview-item mb-2" style="width:100%;height:160px;">
                                <img src="{{ isset($content) && $content->$field ? asset('storage/'.$content->$field) : '' }}" id="preview-img-{{ $field }}" style="width:100%;height:100%;object-fit:cover;">
                                <button type="button" class="btn-remove" onclick="clearSingleMedia('{{ $field }}', 'image')"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        <div class="media-dropzone py-3" id="dropzone-{{ $field }}">
                            <i class="bi bi-cloud-arrow-up icon d-block"></i>
                            <span class="fw-semibold text-dark small">Drag & drop or click to upload</span>
                        </div>
                        <input type="file" id="{{ $field }}" name="{{ $field }}" class="d-none media-input" accept="image/*" data-type="single-image">
                    </div>
                    {{-- Error show --}}
                    @error($field)
                    <div class="text-danger small mt-2">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                    @enderror
                </div>
                @endforeach

                {{-- ── Multiple Images ── --}}
                @foreach($config as $field => $data)
                @if($field==='module_name' || ($data['type']??'') !== 'image_multiple') @continue @endif
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="bi bi-images me-1 text-primary"></i>{{ $data['label'] ?? 'Images' }}
                    </div>
                    <div class="form-section-body">
                        <input type="hidden" name="{{ $field }}_order" id="{{ $field }}_order" value="{{ isset($content) ? json_encode($content->$field ?? []) : '[]' }}">
                        <div id="sortable-{{ $field }}" class="preview-grid mb-2 sortable-wrap" data-field="{{ $field }}">
                            @if(isset($content) && !empty($content->$field))
                            @foreach($content->$field as $i => $path)
                            <div class="preview-item sortable-item" data-type="existing" data-path="{{ $path }}">
                                <img src="{{ asset('storage/'.$path) }}">
                                <span class="badge bg-dark badge-order">{{ $i+1 }}</span>
                                <button type="button" class="btn-remove btn-remove-server-media" data-id="{{ $content->id }}" data-module="{{ $module }}" data-field="{{ $field }}" data-path="{{ $path }}" data-type="image"><i class="bi bi-x-lg"></i></button>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <small class="text-muted d-block mb-2" style="font-size:.75rem;"><i class="bi bi-arrows-move me-1"></i>Drag to reorder.</small>
                        <div class="media-dropzone" id="dropzone-{{ $field }}">
                            <i class="bi bi-images icon d-block"></i>
                            <span class="fw-semibold text-dark small">Drag & drop to add images</span>
                        </div>
                        <input type="file" id="{{ $field }}" name="{{ $field }}[]" class="d-none media-input" accept="image/*" multiple data-type="multiple-image">
                    </div>
                </div>
                @endforeach

                {{-- ── Single Video ── --}}
                @foreach($config as $field => $data)
                @if($field==='module_name' || ($data['type']??'') !== 'video') @continue @endif
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="bi bi-camera-video me-1 text-primary"></i>{{ $data['label'] ?? 'Video' }}
                    </div>
                    <div class="form-section-body">
                        <input type="hidden" name="hidden_{{ $field }}" id="hidden-{{ $field }}" value="{{ isset($content) ? ($content->$field ?? '') : '' }}">
                        <div id="preview-wrap-{{ $field }}" class="{{ (isset($content) && $content->$field) ? '' : 'd-none' }}">
                            <div class="preview-item video-preview mb-2" style="width:100%;height:160px;">
                                <video id="preview-video-{{ $field }}" src="{{ isset($content) && $content->$field ? asset('storage/'.$content->$field) : '' }}" controls style="width:100%;height:100%;object-fit:cover;"></video>
                                <button type="button" class="btn-remove" onclick="clearSingleMedia('{{ $field }}', 'video')"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        <div class="media-dropzone py-3" id="dropzone-{{ $field }}">
                            <i class="bi bi-camera-video icon d-block"></i>
                            <span class="fw-semibold text-dark small" id="text-{{ $field }}">Drag & drop or click to upload video</span>
                        </div>
                        <input type="file" id="{{ $field }}" name="{{ $field }}" class="d-none media-input" accept="video/*" data-type="single-video">
                    </div>
                </div>
                @endforeach

                {{-- ── Multiple Videos ── --}}
                @foreach($config as $field => $data)
                @if($field==='module_name' || ($data['type']??'') !== 'video_multiple') @continue @endif
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="bi bi-collection-play me-1 text-primary"></i>{{ $data['label'] ?? 'Videos' }}
                    </div>
                    <div class="form-section-body">
                        <input type="hidden" name="{{ $field }}_order" id="{{ $field }}_order" value="{{ isset($content) ? json_encode($content->$field ?? []) : '[]' }}">
                        <div id="sortable-{{ $field }}" class="preview-grid mb-2 sortable-wrap" data-field="{{ $field }}">
                            @if(isset($content) && !empty($content->$field))
                            @foreach($content->$field as $i => $path)
                            <div class="preview-item sortable-item video-preview" data-type="existing" data-path="{{ $path }}">
                                <video src="{{ asset('storage/'.$path) }}" style="width:100%;height:100%;object-fit:cover;" muted></video>
                                <span class="badge bg-dark badge-order">{{ $i+1 }}</span>
                                <button type="button" class="btn-remove btn-remove-server-media" data-id="{{ $content->id }}" data-module="{{ $module }}" data-field="{{ $field }}" data-path="{{ $path }}" data-type="video"><i class="bi bi-x-lg"></i></button>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <small class="text-muted d-block mb-2" style="font-size:.75rem;"><i class="bi bi-arrows-move me-1"></i>Drag to reorder.</small>
                        <div class="media-dropzone" id="dropzone-{{ $field }}">
                            <i class="bi bi-collection-play icon d-block"></i>
                            <span class="fw-semibold text-dark small">Drag & drop to add videos</span>
                        </div>
                        <input type="file" id="{{ $field }}" name="{{ $field }}[]" class="d-none media-input" accept="video/*" multiple data-type="multiple-video">
                    </div>
                </div>
                @endforeach

                {{-- ── SEO Section ── --}}
                @php $hasSeoFields = collect($config)->filter(fn($d, $k) => in_array($k, $seoFields))->count() > 0; @endphp
                @if($hasSeoFields)
                <div class="form-section">
                    <div class="form-section-header"><i class="bi bi-search me-1 text-primary"></i>SEO / Meta</div>
                    <div class="form-section-body">
                        @foreach(['meta_title','meta_description','meta_keywords'] as $seoField)
                        @if(!isset($config[$seoField])) @continue @endif
                        @php
                        $label = $config[$seoField]['label'] ?? ucfirst(str_replace('_',' ', $seoField));
                        $old = old($seoField, isset($content) ? $content->$seoField : '');
                        @endphp
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.8rem;">{{ $label }}</label>
                            @if($seoField === 'meta_title')
                            <input type="text" name="{{ $seoField }}" id="{{ $seoField }}" class="form-control form-control-sm" value="{{ $old }}">
                            @elseif($seoField === 'meta_description')
                            <textarea name="{{ $seoField }}" id="{{ $seoField }}" class="form-control form-control-sm" rows="3">{{ $old }}</textarea>
                            @elseif($seoField === 'meta_keywords')
                            <div class="tag-container border rounded p-1 d-flex flex-wrap align-items-center gap-1" id="tag-container-{{ $seoField }}" style="min-height:38px;background:#fff;cursor:text;border-color:#dee2e6;">
                                <input type="text" class="border-0 flex-grow-1 tag-inner-input" placeholder="Type keyword + Enter" style="outline:none;min-width:120px;padding:4px;font-size:.8rem;">
                            </div>
                            <input type="hidden" id="{{ $seoField }}" name="{{ $seoField }}" value="{{ $old }}">
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="d-flex gap-2 mt-3 mb-4">
            <button type="submit" class="btn btn-primary px-5"><i class="bi bi-check2-circle me-1"></i>{{ isset($content) ? 'Update' : 'Save' }}</button>
            <a href="{{ route('admin.contents.index', $module) }}" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="{{ asset('backend/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    function toggleScheduledAt(value) {
        const wrapper = document.getElementById('scheduled_at_wrapper');
        wrapper.style.display = value === '2' ? 'block' : 'none';
    }
</script>
<script>
    $(document).ready(function() {
        const csrfToken = '{{ csrf_token() }}';

  tinymce.init({
    selector: '.ckeditor',
    license_key: 'gpl',
    height: 380,
    menubar: false,
    plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount'],
    toolbar: 'undo redo | blocks fontsize | bold italic forecolor backcolor image | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link media code removeformat',
    paste_data_images: true,
    automatic_uploads: false,
    file_picker_types: 'image',
    file_picker_callback: function(cb) {
        var inp = document.createElement('input');
        inp.setAttribute('type', 'file');
        inp.setAttribute('accept', 'image/*');
        inp.onchange = function() {
            var reader = new FileReader();
            reader.onload = function() {
                cb(reader.result, {
                    title: inp.files[0].name
                });
            };
            reader.readAsDataURL(inp.files[0]);
        };
        inp.click();
    }
});

        // ── Media Storage & Sorting Logic (Unified) ──────────────────────────
        const multiFileStore = {};

        function updateOrder(wrap) {
            const field = wrap.dataset.field;
            const orderInput = document.getElementById(field + '_order');
            if (!orderInput) return;

            const orderData = [];
            const inputEl = document.getElementById(field);
            const dt = new DataTransfer();

            wrap.querySelectorAll('.sortable-item').forEach((el, index) => {
                const badge = el.querySelector('.badge-order');
                if (badge) badge.textContent = index + 1;

                if (el.dataset.type === 'existing') {
                    orderData.push(el.dataset.path);
                } else if (el.dataset.type === 'new') {
                    orderData.push(el.dataset.id);
                    const matched = (multiFileStore[field] || []).find(i => i.id === el.dataset.id);
                    if (matched) dt.items.add(matched.file);
                }
            });

            orderInput.value = JSON.stringify(orderData);
            if (inputEl && inputEl.multiple) inputEl.files = dt.files;
        }

        function handleFileSelection(field, files) {
            if (!files || files.length === 0) return;
            const realInput = document.getElementById(field);
            const type = realInput.dataset.type;

            if (realInput.multiple) {
                if (!multiFileStore[field]) multiFileStore[field] = [];
                const wrap = document.getElementById('sortable-' + field);

                Array.from(files).forEach(f => {
                    const tempId = 'new_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                    multiFileStore[field].push({
                        id: tempId,
                        file: f
                    });

                    const item = document.createElement('div');
                    item.className = 'preview-item sortable-item ' + (type === 'multiple-video' ? 'video-preview' : '');
                    item.setAttribute('data-type', 'new');
                    item.setAttribute('data-id', tempId);

                    const mediaHtml = type === 'multiple-video' ?
                        `<video src="${URL.createObjectURL(f)}" style="width:100%;height:100%;object-fit:cover;" muted></video>` :
                        `<img src="${URL.createObjectURL(f)}">`;

                    item.innerHTML = `
                    ${mediaHtml}
                    <span class="badge bg-success badge-order">New</span>
                    <button type="button" class="btn-remove" onclick="removeNewMedia(this,'${field}','${tempId}')"><i class="bi bi-x-lg"></i></button>`;
                    wrap.appendChild(item);
                });
                updateOrder(wrap);
            } else {
                const dt = new DataTransfer();
                dt.items.add(files[0]);
                realInput.files = dt.files;

                const previewWrap = document.getElementById('preview-wrap-' + field);
                previewWrap.classList.remove('d-none');

                if (type === 'single-image') {
                    document.getElementById('preview-img-' + field).src = URL.createObjectURL(files[0]);
                } else if (type === 'single-video') {
                    const previewVideo = document.getElementById('preview-video-' + field);
                    previewVideo.src = URL.createObjectURL(files[0]);
                    previewVideo.load();
                    document.getElementById('text-' + field).innerHTML = `<span class="text-success fw-bold">${files[0].name}</span>`;
                }
            }
        }

        window.removeNewMedia = function(btn, field, tempId) {
            if (multiFileStore[field]) multiFileStore[field] = multiFileStore[field].filter(i => i.id !== tempId);
            const item = btn.closest('.sortable-item');
            const wrap = item.closest('.sortable-wrap');
            item.remove();
            updateOrder(wrap);
        };

        window.clearSingleMedia = function(field, type) {
            document.getElementById('hidden-' + field).value = '';
            document.getElementById(field).value = '';
            document.getElementById('preview-wrap-' + field).classList.add('d-none');
            if (type === 'image') document.getElementById('preview-img-' + field).src = '';
            if (type === 'video') {
                const video = document.getElementById('preview-video-' + field);
                video.src = '';
                video.load();
                document.getElementById('text-' + field).innerText = "Drag & drop or click to upload video";
            }
        };

        // ── Dropzone & file input bindings ────────────────────────────────────
        document.querySelectorAll('.media-input').forEach(inp => {
            inp.addEventListener('click', function() {
                this.value = '';
            });
            inp.addEventListener('change', function() {
                handleFileSelection(this.id, this.files);
            });
        });

        document.querySelectorAll('.media-dropzone').forEach(zone => {
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                zone.classList.add('dragover');
            });
            zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
            zone.addEventListener('drop', function(e) {
                e.preventDefault();
                zone.classList.remove('dragover');
                handleFileSelection(zone.id.replace('dropzone-', ''), e.dataTransfer.files);
            });
            zone.addEventListener('click', function() {
                document.getElementById(this.id.replace('dropzone-', '')).click();
            });
        });

        // ── Sortable Initialization ───────────────────────────────────────────
        document.querySelectorAll('.sortable-wrap').forEach(wrap => {
            new Sortable(wrap, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: () => updateOrder(wrap)
            });
        });

        // ── Remove existing media from server (AJAX) ──────────────────────────
        document.querySelectorAll('.btn-remove-server-media').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!confirm(`Remove this ${this.dataset.type} permanently?`)) return;
                const {
                    id,
                    module: mod,
                    path,
                    field,
                    type
                } = this.dataset;
                const item = this.closest('.sortable-item');
                const wrap = this.closest('.sortable-wrap');

                // Assuming your backend URL differentiates via query param, or unified endpoint
                fetch(`/admin/contents/${mod}/${id}/remove-${type}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        path,
                        field

                    })
                }).then(r => r.json()).then(data => {
                    if (data.success) {
                        item.remove();
                        updateOrder(wrap);
                    }
                });
            });
        });

        // ── Slug generate ─────────────────────────────────────────────────────
        document.querySelector('.btn-generate-slug')?.addEventListener('click', function() {
            const title = this.previousElementSibling?.value.trim();
            if (!title) return alert('Please enter a title first.');
            const slugInput = document.getElementById('slug');
            if (slugInput) slugInput.value = title.toLowerCase().trim().replace(/[^\p{L}\p{M}\p{N}\s\-]/gu, '').replace(/\s+/g, '-').replace(/^-+|-+$/g, '');
        });

        // ── Features Key-Value (Vanilla JS Update) ────────────────────────────
        const featuresWrapper = document.getElementById('features-wrapper');
        const addFeatureBtn = document.getElementById('btn-add-feature');
        const featureTemplate = document.getElementById('feature-row-template');

        if (addFeatureBtn && featuresWrapper && featureTemplate) {

            new Sortable(featuresWrapper, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost'
            });
            addFeatureBtn.addEventListener('click', function() {
                const clone = featureTemplate.content.cloneNode(true);
                featuresWrapper.appendChild(clone);
            });
            featuresWrapper.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.btn-remove-feature');
                if (!removeBtn) return;

                if (featuresWrapper.querySelectorAll('.feature-row').length > 1) {
                    removeBtn.closest('.feature-row').remove();
                } else {
                    const row = removeBtn.closest('.feature-row');
                    row.querySelectorAll('input').forEach(i => i.value = '');
                    const select = row.querySelector('.feature-key-select');
                    if (select) {
                        select.value = '';
                        select.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                }
            });

            featuresWrapper.addEventListener('change', function(e) {
                const select = e.target.closest('.feature-key-select');
                if (!select) return;

                const row = select.closest('.feature-row');
                const customInput = row.querySelector('.custom-key-input');

                if (select.value === 'custom') {
                    customInput.classList.remove('d-none');
                    customInput.setAttribute('name', 'feature_keys[]');
                    customInput.focus();
                    select.removeAttribute('name');
                } else {
                    customInput.classList.add('d-none');
                    customInput.value = '';
                    customInput.removeAttribute('name');
                    select.setAttribute('name', 'feature_keys[]');
                }
            });
        }

        // ── Tag Input (Vanilla JS with Paste & Scope Fix) ─────────────────────
        document.querySelectorAll('.tag-container').forEach(container => {
            const innerInput = container.querySelector('.tag-inner-input');
            const hiddenInput = container.nextElementSibling;

            // ১. ট্যাগ রেন্ডার করার ফাংশন
            function renderTags() {
                container.querySelectorAll('.badge-tag').forEach(el => el.remove());
                const value = hiddenInput.value.trim();
                if (!value) return;

                value.split(',').map(t => t.trim()).filter(t => t).forEach((tag, index) => {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-primary d-flex align-items-center gap-1.5 p-2 badge-tag';
                    badge.style.fontSize = '11px';
                    badge.style.borderRadius = '6px';
                    badge.innerHTML = `${tag} <i class="bi bi-x-circle text-white-50 btn-remove-single-tag" style="cursor:pointer; font-size: 12px;" data-index="${index}"></i>`;
                    container.insertBefore(badge, innerInput);
                });
            }

            function processTags(text) {
                if (!text) return;
                const newTags = text.split(',').map(t => t.trim()).filter(t => t);
                if (newTags.length === 0) return;

                const existingTags = hiddenInput.value.trim() ? hiddenInput.value.trim().split(',').map(t => t.trim()) : [];

                newTags.forEach(tag => {
                    if (!existingTags.includes(tag)) {
                        existingTags.push(tag);
                    }
                });

                hiddenInput.value = existingTags.join(',');
                renderTags();
            }

            // ৩. নির্দিষ্ট ইনডেক্সের ট্যাগ ডিলিট করার লোকাল ফাংশন (যা সঠিকভাবে রেন্ডার ট্রিগার করবে)
            function removeTagByIndex(index) {
                const tags = hiddenInput.value.trim().split(',').map(t => t.trim()).filter(t => t);
                tags.splice(index, 1);
                hiddenInput.value = tags.join(',');
                renderTags();
                innerInput.focus();
            }

            // ৪. কন্টেইনার এবং ক্রস বোতামে ক্লিক ইভেন্ট ডেলিগেশন
            container.addEventListener('click', e => {
                const removeIcon = e.target.closest('.btn-remove-single-tag');
                if (removeIcon) {
                    e.stopPropagation(); // ইনপুট ফোকাস হওয়া আটকাবে
                    const indexToRemove = parseInt(removeIcon.getAttribute('data-index'));
                    removeTagByIndex(indexToRemove);
                } else {
                    innerInput.focus();
                }
            });

            // ৫. কিবোর্ড টাইপিং ট্র্যাকিং (Enter এবং Comma)
            innerInput.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    processTags(innerInput.value);
                    innerInput.value = '';
                } else if (e.key === 'Backspace' && innerInput.value === '') {
                    const tags = hiddenInput.value.trim() ? hiddenInput.value.trim().split(',').map(t => t.trim()) : [];
                    tags.pop();
                    hiddenInput.value = tags.join(',');
                    renderTags();
                }
            });

            // ৬. [নতুন] কপি-পেস্ট ট্র্যাকিং (Paste Event)
            innerInput.addEventListener('paste', e => {
                e.preventDefault();
                // ক্লিপবোর্ড থেকে কপি করা টেক্সট রিড করা
                const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                processTags(pastedText);
                innerInput.value = '';
            });

            renderTags();
        });
    });
</script>
@endsection
