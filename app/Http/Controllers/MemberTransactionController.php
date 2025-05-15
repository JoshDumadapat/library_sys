<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class MemberTransactionController extends Controller
{
    public function myBorrowedBooks()
    {
        $userId = Auth::id();

        $transactions = Transaction::withCount('transDetails')
            ->where('user_ID', $userId)
            ->orderBy('borrow_date', 'desc')
            ->get();

        return view('member.borrowedbook', compact('transactions'));
    }

    public function getLendingDetails($transId)
    {
        $lending = Transaction::with(['user', 'transDetails.book', 'transDetails.fines'])
            ->where('user_ID', Auth::id())
            ->find($transId);

        if (!$lending) {
            return response()->json(['error' => 'Lending details not found'], 404);
        }

        return response()->json([
            'trans_ID' => $lending->trans_ID,
            'user' => $lending->user,
            'borrow_date' => $lending->borrow_date,
            'due_date' => $lending->due_date,
            'return_date' => $lending->return_date,
            'trans_details' => $lending->transDetails->map(function ($detail) {
                return [
                    'tdetail_ID' => $detail->tdetail_ID,
                    'book_ID' => $detail->book_ID,
                    'book' => $detail->book,
                    'td_status' => $detail->td_status,
                    'fines' => $detail->fines
                ];
            })
        ]);
    }

    public function showMyBorrowedBooks()
    {
        $userId = Auth::id();

        $transactions = Transaction::withCount('transDetails')
            ->where('user_ID', $userId)
            ->orderBy('borrow_date', 'desc')
            ->get();

        return view('member.borrowedbook', compact('transactions'));
    }

    public function viewTransaction($id)
    {
        $transaction = Transaction::with(['transDetails.book'])
            ->where('trans_ID', $id)
            ->where('user_ID', Auth::id()) // ensure user owns the transaction
            ->firstOrFail();

        // Return a partial view with the transaction details (only the HTML snippet)
        return view('member.partials.transaction-details-modal', compact('transaction'));
    }
}
