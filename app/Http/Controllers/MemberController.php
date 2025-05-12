<?php

namespace App\Http\Controllers;

use App\Models\User;


use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('member.dashboard');
    }


    public function showMember($id)
    {
        $member = User::find($id);
        return response()->json($member);
    }

    public function searchMembers(Request $request)
    {
        $members = User::select('id', 'first_name', 'last_name')
            ->where('role', 'member')
            ->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->q . '%')
                    ->orWhere('last_name', 'like', '%' . $request->q . '%');
            })
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->first_name . ' ' . $member->last_name
                ];
            });

        return response()->json($members);
    }
}
