<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Ensure user is authenticated
    }

    public function index()
    {
        return view('admin.dashboard');  // Return the admin dashboard view
    }
}
