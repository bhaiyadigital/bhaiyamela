<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Rules\Recaptcha;
use App\Rules\ValidPhoneNumber;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            $contacts = Contact::with('company')->latest()->get();
            $companies = Company::select('id', 'company_name')->get();
        } else {
            $companyId = $user->company_id;

            if (!$companyId) {
                $contacts = collect();
            } else {
                $contacts = Contact::where('company_id', $companyId)->latest()->get();
            }

            $companies = collect();
        }

        return view('admin.contactList', compact('contacts', 'companies'));
    }

    /**
     * মেসেজকে রিড (is_read = true) হিসেবে আপডেট করা (AJAX)
     */
    public function markRead($id)
    {
        $contact = Contact::findOrFail($id);
        $user = auth()->user();

        // সিকিউরিটি গার্ড: সাধারণ ইউজার যেন অন্য কোম্পানির মেসেজ রিড করতে না পারে
        if (!$user->hasRole('super-admin') && $contact->company_id !== $user->company_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

        $contact->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }


    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $user = auth()->user();

        if (!$user->hasRole('super-admin') && $contact->company_id !== $user->company_id) {
            abort(403, 'Unauthorized action.');
        }

        $contact->delete();

        return redirect()->back()->with('success', 'Message deleted successfully.');
    }
    public function storeLead(Request $request)
    {
        // ১. প্রোপার ইনপুট ভ্যালিডেশন
        $request->validate([
            'lead_name'    => 'required|string|max:255',
            'lead_email'   => 'nullable|email|max:255',
            'lead_phone' => ['required', 'string', new ValidPhoneNumber()],
            'lead_message' => 'required|string',
            'company_id'   => 'nullable|integer',
            'subject'      => 'nullable|string|max:255',
            'recaptcha_token' => ['required', new Recaptcha()],

        ], [
            'lead_name.required'  => 'Please enter your name.',
            'lead_phone.required' => 'Please enter your phone number.',
            'lead_message.required' => 'Please write a message.',
        ]);

        try {
            $fullPhone = $request->input('country_code') . ' ' . $request->input('lead_phone');

            $additionalInfo = "";
            if ($request->filled('lead_job_title')) $additionalInfo .= "\nJob Title: " . $request->input('lead_job_title');
            if ($request->filled('lead_company')) $additionalInfo .= "\nCompany: " . $request->input('lead_company');
            if ($request->filled('lead_budget')) $additionalInfo .= "\nBudget: " . $request->input('lead_budget');
            if ($request->filled('lead_investment_time')) $additionalInfo .= "\nInvestment Plan: " . $request->input('lead_investment_time');

            $finalMessage = $request->input('lead_message');
            if (!empty($additionalInfo)) {
                $finalMessage .= "\n\n--- Additional Details ---" . $additionalInfo;
            }

            Contact::create([
                'name'       => $request->input('lead_name'),
                'company_id' => $request->input('company_id'),
                'email'      => $request->input('lead_email'),
                'phone'      => $fullPhone,
                'job_title'  => $request->input('lead_job_title'),
                'company'    => $request->input('lead_company'),
                'budget'     => $request->input('lead_budget'),
                'investment_time' => $request->input('lead_investment_time'),
                'subject'    => $request->input('subject'),
                'message'    => $finalMessage,
                'is_read'    => false,
            ]);

            \App\Services\FacebookConversionApi::sendEvent('Lead', null, [
                'lead_type'       => 'property_owner',
                'job_title'       => $request->input('lead_job_title'),
                'company'         => $request->input('lead_company'),
                'budget'          => $request->input('lead_budget'),
                'investment_time' => $request->input('lead_investment_time')
            ], [
                'em' => $request->input('lead_email'),
                'ph' => $fullPhone,
                'fn' => $request->input('lead_name')
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully to the property owner.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lead Submission Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.'
            ], 500);
        }
    }
}
