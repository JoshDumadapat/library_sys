<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\address;


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

        // Validate form data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'contact_num' => 'required|string|max:20',
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'street'     => 'required|string|max:255',
            'city'       => 'required|string|max:255',
            'region'     => 'required|string|max:255',

        ]);

        // Update user info
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->contact_no = $request->contact_no;
        $user->email      = $request->email;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        // Update or create address (assuming user has a one-to-one address relationship)
        $addressData = [
            'street' => $request->street,
            'city'   => $request->city,
            'region' => $request->region,
        ];

        if ($user->address) {
            $user->address()->update($addressData);
        } else {
            $user->address()->create($addressData);
        }

        return redirect()->route('member.settings')->with('success', 'Profile updated successfully!');
    }
}
