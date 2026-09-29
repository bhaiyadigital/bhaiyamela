<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Traits\HandlesImageUpload;
use Illuminate\Support\Facades\Cache;

class ContentController extends Controller
{
    use HandlesImageUpload;

    public function index(Request $request, string $module)
    {
        $modules = view()->shared('modules');
        abort_unless(isset($modules[$module]), 404, 'Module not found.');

        $config = $modules[$module];

        $status = $request->input('status');

        $query = Content::with('company')->module($module)->sorted();

        if ($status == 3) {
            $query->trashed();
        } else {
            $query->notTrashed();
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '' && $status !== '3') {
            $query->where('status', $status);
        }
        if (auth()->user()->hasRole('super-admin') && $request->filled('company_id')) {
            $query->where('company_id', $request->input('company_id'));
        }

        $records = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.contents.index', compact('module', 'config', 'records'));
    }

    public function create(string $module)
    {
        $modules = view()->shared('modules');
        abort_unless(isset($modules[$module]), 404);

        $config = $modules[$module];
        return view('admin.contents.form', compact('module', 'config'));
    }

    public function store(Request $request, string $module)
    {

        $modules = view()->shared('modules');
        abort_unless(isset($modules[$module]), 404);

        $config = $modules[$module];

        $rules = $this->buildValidationRules($config, $request);
        $request->validate($rules);

        DB::beginTransaction();

        try {
            $data           = $this->extractFields($request, $config);
            if (auth()->check()) {
                $user = auth()->user();
                if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                    $data['company_id'] = $request->filled('company_id') ? $request->input('company_id') : null;
                    $data['user_id']     = $user->id;
                } else {
                    $data['company_id'] = $user->company_id;
                    $data['user_id']     = $user->id;
                }
            }
            $data['module'] = $module;
            $minSortOrder = Content::where('module', $module)->min('sort_order');
            $data['sort_order'] = ($minSortOrder !== null) ? ($minSortOrder - 1) : 1;
            if (isset($config['features'])) {
                $features = [];
                if ($request->has('feature_keys') && $request->has('feature_values')) {
                    foreach ($request->feature_keys as $index => $key) {
                        if (!empty($key)) {
                            $features[] = [
                                'key'   => $key,
                                'value' => $request->feature_values[$index] ?? ''
                            ];
                        }
                    }
                }
                $data['features'] = $features;
            }

            if (isset($config['slug']) && empty($data['slug']) && !empty($data['title'])) {
                $data['slug'] = Content::generateSlug($data['title']);
            }

            if ((int) ($data['status'] ?? 0) === Content::STATUS_SCHEDULED) {
                $data['published_at'] = $data['scheduled_at'] ?? now()->addDay();
            }

            $data = array_merge($data, $this->handleUploads($request, $module, $config));

            $content = Content::create($data);

            if ($module === "social") {

                Cache::forget('social_links');
            }
            if ($module === "global_meta") {
                Cache::forget('global_meta');
            }
            DB::commit();

            return redirect()
                ->route('admin.contents.index', $module)
                ->with('success', 'Record created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create record: ' . $e->getMessage());
        }
    }

    public function edit(string $module, int $id)
    {
        $modules = view()->shared('modules');
        abort_unless(isset($modules[$module]), 404);

        $config  = $modules[$module];
        $content = Content::module($module)->findOrFail($id);

        return view('admin.contents.form', compact('module', 'config', 'content'));
    }

    public function update(Request $request, string $module, int $id)
    {
        $modules = view()->shared('modules');
        abort_unless(isset($modules[$module]), 404);
        $config  = $modules[$module];
        $content = Content::module($module)->findOrFail($id);
        $rules = $this->buildValidationRules($config, $request, $id);
        $request->validate($rules);

        DB::beginTransaction();

        try {
            $data = $this->extractFields($request, $config);
            if (auth()->check()) {
                $user = auth()->user();
                if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                    $data['company_id'] = $request->filled('company_id') ? $request->input('company_id') : null;
                } else {
                    $data['company_id'] = $user->company_id;
                    $data['user_id']     = $user->id;
                }
            }
            unset($data['sort_order']);
            if (isset($config['features'])) {
                $features = [];
                if ($request->has('feature_keys') && $request->has('feature_values')) {
                    foreach ($request->feature_keys as $index => $key) {
                        if (!empty($key)) {
                            $features[] = [
                                'key'   => $key,
                                'value' => $request->feature_values[$index] ?? ''
                            ];
                        }
                    }
                }
                $data['features'] = $features;
            }
            if (isset($config['slug']) && !empty($data['slug']) && $data['slug'] !== $content->slug) {
                $data['prev_slug'] = $content->slug;
                $data['slug']      = Content::generateSlug($data['slug'], $id);
            }

            if ((int) ($data['status'] ?? 0) == Content::STATUS_SCHEDULED) {
                $data['published_at'] = $data['scheduled_at'] ?? now()->addDay();
            } else {
                $data['published_at'] = null;
            }
            $data['admin_approved'] = $content->admin_approved;
            $uploads = $this->handleUploads($request, $module, $config, $content);
            $data    = array_merge($data, $uploads);

            $content->update($data);

            DB::commit();

            if ($module === "social") {
                Cache::forget('social_links');
            }
   
            if ($module === "global_meta") {
                Cache::forget('global_meta');
            }
            return redirect()
                ->route('admin.contents.index', $module)
                ->with('success', 'Record updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update record: ' . $e->getMessage());
        }
    }

    public function toggleStatus(string $module, int $id)
    {
        $content = Content::module($module)->findOrFail($id);
        $content->status = $content->status === Content::STATUS_ACTIVE
            ? Content::STATUS_INACTIVE
            : Content::STATUS_ACTIVE;
        $content->save();

        return response()->json([
            'status'      => $content->status,
            'status_label' => $content->status_label,
        ]);
    }

    public function trash(string $module, int $id)
    {
        $content = Content::module($module)->findOrFail($id);
        $content->update([
            'status'     => Content::STATUS_TRASH,
            'trashed_at' => now(),
        ]);

        return back()->with('success', 'Moved to trash. It will be permanently deleted after 30 days.');
    }

    public function restore(string $module, int $id)
    {
        $content = Content::module($module)->where('status', Content::STATUS_TRASH)->findOrFail($id);
        $content->update([
            'status'     => Content::STATUS_INACTIVE,
            'trashed_at' => null,
        ]);

        return back()->with('success', 'Record restored successfully.');
    }

    public function destroy(string $module, int $id)
    {
        $content = Content::module($module)->findOrFail($id);

        $modules = view()->shared('modules');
        $config  = $modules[$module] ?? [];

        // ডাইনামিক ফাইল ডিলিট
        $this->deleteFiles($content, $config);
        $content->delete();

        return back()->with('success', 'Record permanently deleted.');
    }

    public function bulk(Request $request, string $module)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'integer',
            'action' => 'required|in:delete,activate,deactivate,trash',
        ]);

        $records = Content::module($module)->whereIn('id', $request->ids)->get();
        $modules = view()->shared('modules');
        $config  = $modules[$module] ?? [];

        foreach ($records as $record) {
            match ($request->action) {
                'delete'     => $this->deleteFiles($record, $config) && $record->delete(),
                'activate'   => $record->update(['status' => Content::STATUS_ACTIVE]),
                'deactivate' => $record->update(['status' => Content::STATUS_INACTIVE]),
                'trash'      => $record->update(['status' => Content::STATUS_TRASH, 'trashed_at' => now()]),
            };
        }

        return back()->with('success', 'Bulk action applied successfully.');
    }

    public function reorder(Request $request, string $module)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer',
        ]);

        foreach ($request->order as $position => $id) {
            Content::module($module)
                ->where('id', $id)
                ->update(['sort_order' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function generateSlug(Request $request)
    {
        $request->validate(['title' => 'required|string']);
        $ignoreId = $request->input('ignore_id');
        $slug     = Content::generateSlug($request->title, $ignoreId ? (int) $ignoreId : null);

        return response()->json(['slug' => $slug]);
    }

    public function removeImage(Request $request, string $module, int $id)
    {
        $request->validate([
            'path'  => 'required|string',
            'field' => 'required|string',
        ]);

        $content    = Content::module($module)->findOrFail($id);
        $field      = $request->input('field');
        $imgPaths   = $content->$field ?? [];
        $removePath = $request->input('path');

        if (Storage::disk('public')->exists($removePath)) {
            Storage::disk('public')->delete($removePath);
        }

        $imgPaths = array_values(array_filter($imgPaths, fn($p) => $p !== $removePath));
        $content->update([$field => $imgPaths]);

        return response()->json(['success' => true, $field => $imgPaths]);
    }

    private function buildValidationRules(array $config, Request $request, ?int $ignoreId = null): array
    {
        $rules = [];

        foreach ($config as $field => $options) {
            if ($field === 'module_name' || $field === 'sort_order' || $field === 'admin_approved') continue;


            $required = ($options['required'] ?? false) ? 'required' : 'nullable';
            $type     = $options['type'] ?? 'text';

            if ($type === 'image') {
                $rules[$field] = $ignoreId
                    ? ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp,svg,avif']
                    : [$required, 'file',  'mimes:jpg,jpeg,png,gif,webp,svg,avif'];
            } elseif ($type === 'image_multiple') {
                $rules[$field] = ['nullable', 'array'];
                $rules[$field . '.*'] = ['file',  'mimes:jpg,jpeg,png,gif,webp,svg,avif'];
            } elseif ($type === 'video') {
                $rules[$field] = $ignoreId
                    ? ['nullable', 'file', 'mimetypes:video/mp4,video/avi,video/quicktime']
                    : [$required, 'file', 'mimetypes:video/mp4,video/avi,video/quicktime'];
            } elseif ($type === 'video_multiple') {
                $rules[$field] = ['nullable', 'array'];
                $rules[$field . '.*'] = ['file', 'mimetypes:video/mp4,video/avi,video/quicktime', 'max:102400'];
            } elseif ($field === 'features') {
                $rules['feature_keys']   = ['nullable', 'array'];
                $rules['feature_keys.*'] = ['nullable', 'string'];
                $rules['feature_values']   = ['nullable', 'array'];
                $rules['feature_values.*'] = ['nullable', 'string'];
            } else {
                $rules[$field] = match ($type) {
                    'url'      => [$required, 'url'],
                    'number'   => [$required, 'integer'],
                    'select'   => ($field === 'status')
                        ? [$required, 'in:0,1,2,3']
                        : [$required, 'integer', 'exists:contents,id'],
                    'datetime' => [$required, 'date'],
                    'tag'      => [$required, 'string'],
                    'extra'      => [$required],
                    default    => [$required, 'string'],
                };
            }

            if ($field === 'slug') {
                $uniqueRule    = 'unique:contents,slug';
                if ($ignoreId) $uniqueRule .= ',' . $ignoreId;
                $rules['slug'][] = $uniqueRule;
            }
        }
        $rules['scheduled_at'] = $request->input('status') == Content::STATUS_SCHEDULED
            ? ['required', 'date']
            : ['nullable', 'date'];
        return $rules;
    }

    private function extractFields(Request $request, array $config): array
    {
        $data         = [];
        $fileTypes    = ['image', 'image_multiple', 'video', 'video_multiple', 'virtual_tour'];
        $skipFields   = ['module_name'];

        foreach ($config as $field => $options) {
            if (in_array($field, $skipFields)) continue;
            if (in_array($options['type'] ?? '', $fileTypes)) continue;

            if ($field === 'features') {
                $raw = $request->input('features');
                $data['features'] = is_array($raw) ? $raw : (json_decode($raw, true) ?? []);
                continue;
            }
            $virtualFields = [
                'description_title',
                'description_1_title',
                'description_2_title',
                'description_status',
                'description_1_status',
                'description_2_status'
            ];

            if (in_array($field, $virtualFields)) {
                if ($request->has($field)) {
                    $extraData[$field] = $request->input($field);
                }
                continue;
            }
            if ($field === 'extra') {
                $rawExtra = $request->input('extra');
                if (is_array($rawExtra)) {
                    $data['extra'] = $rawExtra;
                } else {
                    $data['extra'] = $rawExtra ? array_map('trim', explode(',', $rawExtra)) : [];
                }
                continue;
            }

            $data[$field] = $request->input($field);
        }
        if (!empty($extraExtra = isset($content) ? ($content->extra ?? []) : [])) {
            $data['extra'] = array_merge($extraExtra, $extraData ?? []);
        } else {
            $data['extra'] = $extraData ?? [];
        }

        // Handle specific case for saving compiled array of extra fields
        if (!empty($extraData)) {
            $data['extra'] = $extraData;
        }
        if ($request->filled('scheduled_at')) {
            $data['scheduled_at'] = \Carbon\Carbon::parse(
                $request->input('scheduled_at')
            )->format('Y-m-d H:i:s');
        }
        return $data;
    }

    private function handleUploads(Request $request, string $module, array $config, ?Content $existing = null): array
    {
        $data = [];
        $dir  = "uploads/{$module}";

        foreach ($config as $field => $options) {
            if ($field === 'module_name') continue;
            $type = $options['type'] ?? 'text';

            // ১. সিঙ্গেল ইমেজ
            if ($type === 'image' && $request->hasFile($field)) {
                if ($existing?->$field) {
                    Storage::disk('public')->delete($existing->$field);
                }
                $data[$field] = $this->storeAsWebp($request->file($field), $dir);
            }

            // ২. মাল্টিপল ইমেজ অর্ডারিং সহ
            elseif ($type === 'image_multiple') {
                $orderKey      = $field . '_order';
                $existingPaths = $existing?->$field ?? [];
                if (is_string($existingPaths)) {
                    $existingPaths = json_decode($existingPaths, true) ?? [];
                }
                $newPaths      = [];

                // প্রথমে নতুন ছবিগুলো ওয়েবপি হিসেবে আপলোড করে পাথ জেনারেট করি
                if ($request->hasFile($field)) {
                    foreach ($request->file($field) as $file) {
                        $newPaths[] = $this->storeAsWebp($file, $dir);
                    }
                }

                if ($request->filled($orderKey)) {
                    $order = json_decode($request->input($orderKey), true) ?? [];
                    $finalPaths = [];
                    $newFileIndex = 0;

                    foreach ($order as $item) {
                        // যদি আইটেমটি নতুন ফাইল প্লেসহোল্ডার (new_...) হয়
                        if (str_starts_with($item, 'new_')) {
                            if (isset($newPaths[$newFileIndex])) {
                                $finalPaths[] = $newPaths[$newFileIndex];
                                $newFileIndex++;
                            }
                        } else {
                            // যদি আইটেমটি আগের আপলোড করা ছবির পাথ হয়
                            if (in_array($item, $existingPaths)) {
                                $finalPaths[] = $item;
                            }
                        }
                    }

                    // ব্যাকআপ: যদি কোনো কারণে অর্ডারে নতুন ছবির পাথ মিস হয়, সেগুলো শেষে যুক্ত করে দেবে
                    for ($i = $newFileIndex; $i < count($newPaths); $i++) {
                        $finalPaths[] = $newPaths[$i];
                    }

                    $data[$field] = $finalPaths;
                } else {
                    $data[$field] = array_merge($existingPaths, $newPaths);
                }
            }

            // ৩. সিঙ্গেল ভিডিও
            elseif ($type === 'video' && $request->hasFile($field)) {
                if ($existing?->$field) {
                    Storage::disk('public')->delete($existing->$field);
                }
                $data[$field] = $request->file($field)->store($dir . '/videos', 'public');
            }

            // ৪. মাল্টিপল ভিডিও
            elseif ($type === 'video_multiple') {
                $existingVideos = $existing?->$field ?? [];
                $newVideos      = [];

                if ($request->hasFile($field)) {
                    foreach ($request->file($field) as $file) {
                        $newVideos[] = $file->store($dir . '/videos', 'public');
                    }
                }
                $data[$field] = array_merge($existingVideos, $newVideos);
            }
        }

        return $data;
    }

    // ✅ ডাইনামিক ডিলিট লজিক
    private function deleteFiles(Content $content, array $config): bool
    {
        foreach ($config as $field => $options) {
            if ($field === 'module_name') continue;
            $type = $options['type'] ?? 'text';

            if (in_array($type, ['image', 'video'])) {
                if ($content->$field) {
                    Storage::disk('public')->delete($content->$field);
                }
            } elseif (in_array($type, ['image_multiple', 'video_multiple'])) {
                $files = $content->$field ?? [];
                if (is_array($files)) {
                    foreach ($files as $file) {
                        Storage::disk('public')->delete($file);
                    }
                }
            }
        }

        return true;
    }

    public function uploadImage(Request $request, string $module): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'upload'  => 'nullable|image|max:5120',
            'file'    => 'nullable|image|max:5120',
            'cropped' => 'nullable|string',
        ]);

        $dir = "uploads/{$module}/editor";

        if ($request->filled('cropped')) {
            $path = $this->storeAsWebp($request->input('cropped'), $dir);
        } elseif ($file = $request->file('upload') ?? $request->file('file')) {
            $path = $this->storeAsWebp($file, $dir);
        } else {
            return response()->json(['error' => ['message' => 'No file provided.']], 422);
        }

        $url = asset('storage/' . $path);

        return response()->json([
            'uploaded' => 1,
            'fileName' => basename($path),
            'url'      => $url,
            'location' => $url,
        ]);
    }
    public function toggleAdminApproval(Request $request, string $module, int $id)
    {
        abort_unless(auth()->user()->hasRole('super-admin'), 403);

        $request->validate([
            'status' => 'required|in:0,1,2'
        ]);

        $content = Content::module($module)->findOrFail($id);
        $content->admin_approved = $request->input('status');
        $content->save();

        return response()->json([
            'success' => true,
            'status'  => $content->admin_approved,
        ]);
    }
}
