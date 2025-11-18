<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Handle different settings updates
        if ($request->has('current_password')) {
            return $this->updatePassword($request);
        }

        if ($request->has('name')) {
            return $this->updateProfile($request);
        }

        if ($request->has('notification_settings')) {
            return $this->updateNotifications($request);
        }

        return redirect()->back()->with('error', 'Invalid request');
    }

    private function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    private function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone', 'address']));

        return back()->with('success', 'Profile updated successfully');
    }

    private function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        // Update notification preferences
        // You might want to create a separate settings table
        // or add JSON column to users table
        
        return back()->with('success', 'Notification settings updated');
    }
}