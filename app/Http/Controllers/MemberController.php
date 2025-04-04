<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Ensure user is authenticated
    }

    public function index()
    {
        return view('member.dashboard');  // Return the member dashboard view
    }
}
