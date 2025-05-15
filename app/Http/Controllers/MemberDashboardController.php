<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookRequest;  // <-- import your BookRequest model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Count total borrowed books for this user across all transactions
        $borrowedBooksCount = DB::table('trans_details as td')
            ->join('transactions as t', 'td.trans_ID', '=', 't.trans_ID')
            ->where('t.user_ID', $userId)
            ->count();

        // Count total book requests for this user
        $bookRequestsCount = BookRequest::where('user_ID', $userId)->count();

        $books = Book::with(['authors', 'genres'])->get();

        return view('member.dashboard', compact('books', 'borrowedBooksCount', 'bookRequestsCount'));
    }
}
