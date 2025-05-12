<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $books = Book::with(['authors', 'genres'])->get();
        return view('member.dashboard', compact('books'));
    }
}
