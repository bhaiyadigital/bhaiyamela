<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Rules\ValidPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{

    public function index()
    {
        $setting = Setting::instance();
        return view('admin.settings.index', compact('setting'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'site_name'          => 'nullable|string|max:255',
            'site_slogan'        => 'nullable|string|max:255',
            'logo'               => 'nullable|image|max:2048',
            'favicon'            => 'nullable|image|max:2048',
            'meta_index'         => 'required|in:index,noindex',
            'privacy_policy_url' => 'nullable|url|max:500',
            'terms_url'          => 'nullable|url|max:500',
            'footer_credit'      => 'nullable|string|max:500',
            'email'              => 'nullable|email|max:255',
            'phone'      => ['required', 'string', new ValidPhoneNumber()],
            'address'            => 'nullable|string|max:500',
            'map_embed'          => 'nullable|string',
        ]);

        $setting = Setting::instance();
        $data    = $request->except(['_token', '_method', 'logo', 'favicon']);

        // Logo upload — replace old file
        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        // Favicon upload — replace old file
        if ($request->hasFile('favicon')) {
            if ($setting->favicon) {
                Storage::disk('public')->delete($setting->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        $setting->update($data);
        Cache::forget('site_settings');

        return back()->with('success', 'Settings saved successfully.');
    }
}
