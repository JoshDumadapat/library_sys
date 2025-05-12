<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function manageBooks()
    {
        return view('admin.manageBooks.index', compact('books'));
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

        $employees = User::where('role', '!=', 'member')->with('address')->get();

        return view('admin.employees.index', compact('employees'));
    }



    public function settings()
    {
        return view('admin.settings');
    }
}
