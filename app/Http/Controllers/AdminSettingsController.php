<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use App\Models\FineType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $fineTypes = FineType::all()->keyBy('reason'); // Changed from 'name' to 'reason'

        return view('admin.settings.index', [
            'user' => $user,
            'fineTypes' => $fineTypes
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validate all fields (profile + fines)
        $validated = $request->validate([
            // Profile fields
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Fine policy fields
            'daily_fine' => 'required|numeric|min:0',
            'lost_book_fine' => 'required|numeric|min:0',
            'damaged_book_fine' => 'required|numeric|min:0',
        ]);

        try {
            // Start transaction for atomic updates
            DB::beginTransaction();

            // 1. Update user profile
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'contact_num' => $validated['contact_num'],
                'email' => $validated['email'],
            ]);

            // 2. Update or create address
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

            // 3. Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old profile picture if exists
                if ($user->profile_picture_path) {
                    Storage::delete($user->profile_picture_path);
                }

                $path = $request->file('profile_picture')->store('profile-pictures');
                $user->update(['profile_picture_path' => $path]);
            }

            // 4. Update fine policies
            FineType::where('reason', 'overdue')->update([
                'default_amount' => $validated['daily_fine'],
                'is_per_day' => true
            ]);

            FineType::where('reason', 'lost')->update([
                'default_amount' => $validated['lost_book_fine'],
                'is_per_day' => false
            ]);

            FineType::where('reason', 'damaged')->update([
                'default_amount' => $validated['damaged_book_fine'],
                'is_per_day' => false
            ]);

            DB::commit();

            return back()->with('success', 'Profile and fine policies updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Profile update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update: ' . $e->getMessage());
        }
    }
}
