<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookRequest;
use Illuminate\Http\Request;

class MemberBookRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = BookRequest::with('book')
            ->where('user_ID', auth()->id())
            ->when($request->search, function ($query) use ($request) {
                $query->search($request->search);
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc')
            ->paginate(10)
            ->withQueryString(); // Preserve all query parameters

        return view('member.requests.index', compact('requests'));
    }

    public function create()
    {
        $books = Book::available()
            ->with('authors') // Load the relationship
            ->get();

        return view('member.requests.create', compact('books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_ID' => 'required|array',
            'book_ID.*' => 'exists:books,book_ID'
        ]);

        // Get all requested books with their borrowed copies count
        $books = Book::whereIn('book_ID', $validated['book_ID'])
            ->withCount('borrowedCopies') // Assuming you have this relationship
            ->get();

        // Check for unavailable books (where borrowed_copies >= total_copies)
        $unavailableBooks = $books->filter(function ($book) {
            return $book->borrowed_copies_count >= $book->total_copies;
        });

        if ($unavailableBooks->isNotEmpty()) {
            return back()->with('error', 'Some books are unavailable: ' .
                $unavailableBooks->pluck('title')->implode(', '));
        }

        // Check for existing requests
        $existingRequests = BookRequest::where('user_ID', auth()->id())
            ->whereIn('book_ID', $validated['book_ID'])
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('book_ID');

        if ($existingRequests->isNotEmpty()) {
            return back()->with('error', 'You already have requests for: ' .
                Book::whereIn('book_ID', $existingRequests)->pluck('title')->implode(', '));
        }

        // Create requests
        foreach ($validated['book_ID'] as $bookId) {
            BookRequest::create([
                'user_ID' => auth()->id(),
                'book_ID' => $bookId,
                'status' => 'pending'
            ]);
        }

        return redirect()->route('member.requests.index')
            ->with('success', count($validated['book_ID']) . ' book requests submitted!');
    }
}
