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

    public function manageBooks()
    {
        return view('admin.manageBooks');
    }

    public function lend()
    {
        return view('admin.lend');
    }

    public function return()
    {
        return view('admin.return');
    }

    public function members()
    {
        return view('admin.members');
    }

    public function report()
    {
        return view('admin.report');
    }

    public function employees()
    {
        return view('admin.employees');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
