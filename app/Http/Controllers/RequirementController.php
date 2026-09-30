<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use App\Rules\Recaptcha;
use App\Rules\ValidPhoneNumber;
use App\Services\FacebookConversionApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequirementController extends Controller
{

    public function index(Request $request)
    {
        $query = Requirement::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $requirements = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.requirements.index', compact('requirements'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,contacted,matched,closed',
        ]);

        try {
            $requirement = Requirement::findOrFail($id);
            $requirement->update([
                'status' => $request->input('status')
            ]);

            return response()->json([
                'success' => true,
                'status'  => $requirement->status,
                'message' => 'Status updated successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
    public function create()
    {
        return view('frontend.requirements');
    }


    public function store(Request $request)
    {
        $request->validate([
            'purpose'       => 'required|in:buy,rent,roommates',
            'property_type' => 'required|string|max:255',
            'size'          => 'required|string|max:255',
            'city'          => 'required|string|max:255',
            'location'      => 'nullable|string|max:255',
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'country_code'  => 'required|string|max:10',
            'phone'      => ['required', 'string', new ValidPhoneNumber()],
            'recaptcha_token' => ['required', new Recaptcha()],

        ], [
            'name.required'  => 'Please enter your name.',
            'email.required' => 'Please enter a valid email address.',
            'phone.required' => 'Please enter your phone number.',
        ]);

        try {
            $fullPhone = $request->input('country_code') . ' ' . $request->input('phone');

            // 3. Create the record in database
            Requirement::create([
                'purpose'       => $request->input('purpose'),
                'property_type' => $request->input('property_type'),
                'size'          => $request->input('size'),
                'city'          => $request->input('city'),
                'location'      => $request->input('location'),
                'name'          => $request->input('name'),
                'email'         => $request->input('email'),
                'phone'         => $fullPhone,
            ]);

            FacebookConversionApi::sendEvent('Lead', null, [
                'lead_type' => 'requirement', 
                'purpose' => $request->input('purpose')
            ], [
                'em' => $request->input('email'),
                'ph' => $fullPhone,
                'fn' => $request->input('name')
            ]);

            return redirect()
                ->back()
                ->with('success', 'Your property requirement has been posted successfully. We will notify you soon!');
        } catch (\Exception $e) {
            Log::error('Requirement Saving Error: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to save requirement: ' . $e->getMessage());
        }
    }
}
