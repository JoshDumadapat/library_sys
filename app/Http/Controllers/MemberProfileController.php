<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Address;


class MemberProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('member.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user
        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'contact_num' => $validated['contact_num'],
            'email' => $validated['email'],
        ]);

        // Update or create address
        $addressData = [
            'street' => $validated['street'],
            'city' => $validated['city'],
            'region' => $validated['region'],
        ];

        if ($user->address) {
            $user->address()->update($addressData);
        } else {
            $user->address()->create($addressData);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture_path) {
                Storage::delete($user->profile_picture_path);
            }

            $path = $request->file('profile_picture')->store('profile-pictures');
            $user->update(['profile_picture_path' => $path]);
        }

        return redirect()->route('member.settings')->with('success', 'Profile updated successfully!');
    }
}
