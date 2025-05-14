<?php

// app/Http/Controllers/AdminBookRequestController.php
namespace App\Http\Controllers;

use App\Models\BookRequest;
use App\Models\Transaction;
use App\Models\TransDetail;
use Illuminate\Http\Request;
use Illuminate\Auth;

class AdminBookRequestController extends Controller
{
    public function index()
    {
        $pendingRequests = BookRequest::with(['user', 'book'])
            ->pending()
            ->latest()
            ->get();

        $approvedRequests = BookRequest::with(['user', 'book'])
            ->approved()
            ->latest()
            ->get();

        return view('admin.requests.index', compact('pendingRequests', 'approvedRequests'));
    }

    public function show(BookRequest $bookRequest)
    {
        return view('admin.requests.show', compact('bookRequest'));
    }

    public function approve(Request $request, BookRequest $bookRequest)
    {
        $bookRequest->update([
            'status' => 'approved',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'admin_notes' => $request->admin_notes
        ]);

        return back()->with('success', 'Request approved successfully');
    }

    public function reject(Request $request, BookRequest $bookRequest)
    {
        $bookRequest->update([
            'status' => 'rejected',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'admin_notes' => $request->admin_notes
        ]);

        return back()->with('success', 'Request rejected successfully');
    }

    public function lend(BookRequest $bookRequest)
    {
        // Use your existing TransactionController logic
        $transactionController = new TransactionController();

        // Prepare request data for lending
        $request = new \Illuminate\Http\Request();
        $request->replace([
            'user_ID' => $bookRequest->user_ID,
            'book_IDs' => [$bookRequest->book_ID],
            'borrow_date' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(14)->format('Y-m-d') // 2 weeks
        ]);

        // Process the lending
        $result = $transactionController->lendBooks($request);

        if ($result->getData()->success) {
            $bookRequest->update(['status' => 'completed']);
            return back()->with('success', 'Book successfully lent to member');
        }

        return back()->with('error', 'Failed to process lending: ' . $result->getData()->message);
    }
}
