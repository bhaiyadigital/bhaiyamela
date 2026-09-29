<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();

        // Eager load favorite projects
        $favorites = Favorite::where('user_id', $user->id)
            ->with('project')
            ->get();
        $activeTicket = null;
        if ($request->filled('ticket_id')) {
            $activeTicket = Ticket::with('messages.user')
                ->where('user_id', $user->id)
                ->findOrFail($request->input('ticket_id'));
        }
        return view('frontend.user.dashboard', compact('user', 'favorites', 'activeTicket'));
    }

    /**
     * Update user profile (Name & Email).
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('user.dashboard', ['tab' => 'profile'])->with('success', 'Profile updated successfully.');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password does not match.');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('user.dashboard', ['tab' => 'password'])->with('success', 'Password changed successfully.');
    }
}
