<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // ------------------------------------------------------------------
    // List all users
    // ------------------------------------------------------------------
    public function index(Request $request)
    {
        $query = User::where('user_type', '!=', 'user')->with('roles');

        // Live search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($roleSlug = $request->input('role')) {
            $query->whereHas('roles', fn($q) => $q->where('slug', $roleSlug));
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        $roles = Role::orderBy('name')->get();
        

        return view('admin.users.index', compact('users', 'roles'));
    }

    // ------------------------------------------------------------------
    // Show create form
    // ------------------------------------------------------------------
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.form', compact('roles'));
    }

    // ------------------------------------------------------------------
    // Store new user
    // ------------------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'roles'    => 'nullable|exists:roles,id',
            'status' => 'required|in:0,1',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'status'    => $request->status,
            'password' => Hash::make($request->password),
        ]);

        // Single role sync
        $user->roles()->sync($request->roles ? [$request->roles] : []);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    // ------------------------------------------------------------------
    // Show edit form
    // ------------------------------------------------------------------
    public function edit(int $id)
    {
        $user  = User::with('roles')->findOrFail($id);
        $roles = Role::with('permissions')->orderBy('name')->get();
        return view('admin.users.form', compact('user', 'roles'));
    }

    // ------------------------------------------------------------------
    // Update user
    // ------------------------------------------------------------------
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $id,
            'roles'  => 'nullable|exists:roles,id',
            'status' => 'required|in:0,1',
        ]);

        DB::beginTransaction();

        try {
            $data = [
                'name'   => $request->name,
                'email'  => $request->email,
                'status' => $request->status,
            ];

            $user->update($data);

            $user->roles()->sync($request->roles ? [$request->roles] : []);


            if ($user->company) {
                $user->company->update([
                    'status' => $request->status
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User and associated company updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Update Failed: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }
    }

    // ------------------------------------------------------------------
    // Delete user
    // ------------------------------------------------------------------
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->roles()->detach();
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
    public function subscriberList(Request $request)
    {
        $query = Subscription::query();

        // Search filter by email
        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->input('search') . '%');
        }

        $subscriptions = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }


    public function destroySubscirber(int $id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            $subscription->delete();

            return redirect()
                ->route('admin.subscriptions.index')
                ->with('success', 'Subscriber deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.subscriptions.index')
                ->with('error', 'Failed to delete subscriber: ' . $e->getMessage());
        }
    }
    public function customersIndex(Request $request)
    {
        // Query only users where user_type is 'user'
        $query = User::where('user_type', 'user');

        // Apply Search Filter (Name, Email, Phone)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Paginate results (15 per page)
        $customers = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.customers.index', compact('customers'));
    }

  
    public function toggleCustomerStatus(Request $request, int $id)
    {
        try {
            $customer = User::where('user_type', 'user')->findOrFail($id);

            $customer->status = ($customer->status == 1) ? 0 : 1;
            $customer->save();

            return response()->json([
                'success' => true,
                'status'  => $customer->status,
                'message' => 'Status updated successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle status: ' . $e->getMessage()
            ], 500);
        }
    }
}
