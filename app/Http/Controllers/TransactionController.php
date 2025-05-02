<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransDetail;



class TransactionController extends Controller
{
    public function lendFromReservation($resId)
    {
        $reservation = Reservation::with('resDetails')->findOrFail($resId);
    
        $transaction = Transaction::create([
            'user_ID' => $reservation->user_ID,
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
            'handled_by' => auth()->id()
        ]);
    
        foreach ($reservation->resDetails as $item) {
            TransDetail::create([
                'trans_ID' => $transaction->trans_ID,
                'book_id' => $item->book_id,
                'td_status' => 'borrowed'
            ]);
    
            $item->update(['res_status' => 'approved']);
        }
    
        $reservation->update(['res_status' => 'approved']);
    
        return redirect()->back()->with('success', 'Book lent successfully.');
    }

    
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

public function lendBooks(Request $request)
{
    $validated = $request->validate([
        'user_ID' => 'required|exists:users,id',
        'book_IDs' => 'required|array',
        'book_IDs.*' => 'exists:books,book_id'
    ]);

    // Continue with the rest of the logic
    $transaction = new Transaction();
    $transaction->user_ID = $request->user_ID;
    $transaction->borrow_date = $request->borrow_date;
    $transaction->due_date = $request->due_date;
    $transaction->save();

    try {
        $transaction->save();
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to save transaction', 'error' => $e->getMessage()]);
    }
    
    if (!$transaction->trans_ID) {
        return response()->json(['message' => 'Failed to save transaction']);
    }
    

    foreach ($request->book_IDs as $bookId) {
    $transDetail = new TransDetail();
    $transDetail->trans_ID = $transaction->trans_ID; // Ensure this is set properly
    $transDetail->book_ID = $bookId;
    $transDetail->save();
}

    return response()->json(['message' => 'Books successfully lent']);
}


    
}
