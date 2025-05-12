<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransDetail;
use Illuminate\Support\Facades\DB;




class TransactionController extends Controller
{



    public function showLendUI()
    {
        $books = Book::with('transDetails.transaction.admin')->get();

        return view('admin.lend', compact('books'));
    }

    public function getNextTransactionId()
    {
        $nextId = Transaction::max('trans_ID') + 1;
        return response()->json(['next_id' => $nextId]);
    }



    public function checkMemberFines($memberId)
    {
        $unpaidFines = \App\Models\Fine::whereHas('transactionDetail.transaction', function ($query) use ($memberId) {
            $query->where('user_ID', $memberId);
        })
            ->where('fine_status', 'pending')
            ->exists();

        return $unpaidFines;
    }

    public function lendBooks(Request $request)
    {
        // First check for unpaid fines
        if ($this->checkMemberFines($request->user_ID)) {
            return response()->json([
                'success' => false,
                'message' => 'Member has unpaid fines. Please settle fines before borrowing.'
            ], 403);
        }

        $validated = $request->validate([
            'user_ID' => 'required|exists:users,id',
            'book_IDs' => 'required|array',
            'book_IDs.*' => 'exists:books,book_ID',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date'
        ]);

        DB::beginTransaction();

        try {
            // First verify all books are available
            foreach ($request->book_IDs as $bookId) {
                $currentlyBorrowed = TransDetail::where('book_ID', $bookId)
                    ->whereHas('transaction', function ($query) {
                        $query->whereNull('return_date'); // Assuming you track returns
                    })
                    ->count();

                $totalCopies = Book::find($bookId)->total_copies;

                if ($currentlyBorrowed >= $totalCopies) {
                    throw new \Exception("Book ID $bookId is not available for borrowing");
                }
            }

            // Create transaction
            $transaction = new Transaction();
            $transaction->user_ID = $request->user_ID;
            $transaction->borrow_date = $request->borrow_date;
            $transaction->due_date = $request->due_date;
            $transaction->save();

            // Create transaction details
            foreach ($request->book_IDs as $bookId) {
                $transDetail = new TransDetail();
                $transDetail->trans_ID = $transaction->trans_ID;
                $transDetail->book_ID = $bookId;
                $transDetail->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Books successfully lent'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error processing transaction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkFines($memberId)
    {
        $hasFines = $this->checkMemberFines($memberId);

        return response()->json([
            'has_fines' => $hasFines,
            'message' => $hasFines ? 'Member has unpaid fines' : 'No unpaid fines'
        ]);
    }

    public function checkBookAvailability($bookId)
    {
        $currentlyBorrowed = TransDetail::where('book_ID', $bookId)
            ->whereHas('transaction', function ($query) {
                $query->whereNull('return_date');
            })
            ->count();

        $book = Book::findOrFail($bookId);
        $isAvailable = ($currentlyBorrowed < $book->total_copies);

        return response()->json([
            'is_available' => $isAvailable,
            'message' => $isAvailable
                ? 'Book is available'
                : "The book '{$book->title}' is currently unavailable"
        ]);
    }
}
