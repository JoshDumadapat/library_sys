<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminMemberController extends Controller
{
    // Show the list of members
    public function index()
    {
        // Fetch users with the role 'member'
        $members = User::where('role', 'member')->get();
        
        // Return the admin members view and pass the members data
        return view('admin.members', compact('members')); 
    }

    // Deactivate a member
    public function deactivate(User $member)
    {
        if ($member->status !== 'Inactive') {
            $member->status = 'Inactive';
            $member->save();
        }

        return redirect()->route('admin.members');
    }

    // Activate a member
    public function activate(User $member)
    {
        if ($member->status !== 'Active') {
            $member->status = 'Active';
            $member->save();
        }

        return redirect()->route('admin.members');
    }
}
