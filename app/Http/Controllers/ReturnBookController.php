<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\TransDetail;
use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\FineType; // Add this at the top if not yet imported


class ReturnBookController extends Controller
{
    public function returnBook(Request $request)
    {
        $transDetail = TransDetail::with('transaction')->findOrFail($request->tdetail_id);
        $transaction = $transDetail->transaction;
    
        // 1. Mark as returned
        $transDetail->td_status = 'returned';
        $transDetail->save();
    
        // 2. Update return date (if not set yet)
        if (!$transaction->return_date) {
            $transaction->return_date = now();
            $transaction->save();
        }
    
        // 3. Check if overdue
        $dueDate = Carbon::parse($transaction->due_date);
        $returnDate = Carbon::parse($transaction->return_date);
        $daysLate = $returnDate->diffInDays($dueDate, false); // false = return negative if overdue
    
        // If the book is overdue, create a fine
        if ($daysLate < 0) {
            // Get the fine rate for overdue from fine_types table
            $fineType = FineType::where('reason', 'Overdue')->first();
            $ratePerDay = $fineType->default_amount ?? 10; // Default value if no rate found
    
            $fineAmount = abs($daysLate) * $ratePerDay;
    
            // Check if fine already exists for this transaction (avoid duplicates)
            $existingFine = Fine::where('trans_ID', $transaction->trans_ID)->first();
            if (!$existingFine) {
                // Create the fine record
                Fine::create([
                    'trans_ID' => $transaction->trans_ID,
                    'fine_amt' => $fineAmount,
                    'reason' => 'Overdue by ' . abs($daysLate) . ' days',
                    'fine_status' => 'unpaid',
                    'collected_by' => null, // To be updated when fine is paid
                    'ftype_id' => $fineType->id, // Link to FineType
                ]);
            }
        }
    
        return back()->with('success', 'Book returned successfully.');
    }


    // This is for the return ui
    public function showLendingRecords()
    {
        $lendings = Transaction::with('user', 'transDetails')->paginate(10);
    
        return view('admin.return', compact('lendings'));
    }


    public function updateBookStatus(Request $request, $trans_id)
         {
            $transaction = Transaction::with('transDetails.book')->findOrFail($trans_id);

            $totalFine = 0;

            foreach ($transaction->transDetails as $transDetail) {
                $status = $request->input('status.' . $transDetail->id); // Get the status selected by admin

                if ($status) {
                    // Update the return status
                    $transDetail->return_status = $status;

                    // Find the correct fine type based on the status (now using enum)
                    $fineType = FineType::where('reason', ucfirst($status))->first();

                    // If a matching fine type is found, calculate the fine
                    if ($fineType) {
                        $fineAmount = 0;

                        // Calculate the fine based on whether it's per day or a fixed amount
                        if ($fineType->is_per_day) {
                            // If the fine is per day, calculate the overdue days
                            $fineAmount = $this->calculateOverdueFine($transDetail, $fineType);
                        } else {
                            // If it's not per day, just use the default amount
                            $fineAmount = $fineType->default_amount;
                        }

                        // Create and save the fine in the database
                        $fine = new Fine();
                        $fine->tdetail_ID = $transDetail->id; // Link fine to the transaction detail (specific book)
                        $fine->fine_amt = $fineAmount;
                        $fine->reason = $fineType->reason;
                        $fine->fine_status = 'unpaid'; // You can adjust this as needed
                        $fine->collected_by = null; // Can be set later
                        $fine->ftype_id = $fineType->ftype_id; // Associate with the FineType
                        $fine->save();

                        // Add to the total fine amount
                        $totalFine += $fineAmount;
                    }
                }

                // Save the transaction detail if necessary
                $transDetail->save();
            }

            // Save the transaction if needed
            $transaction->save();

            // Redirect with total fine
            return redirect()->route('return.book.detail', ['trans_id' => $trans_id])
                        ->with('totalFine', $totalFine)
                        ->with('message', 'Book status updated and fines calculated.');

         }

         public function getLendingDetails($transId)
         {
             // Fetch the lending transaction using trans_id with user and its relationships
             $lending = Transaction::with('user', 'transDetails.book', 'transDetails.fines') // Correct case 'transDetails'
                 ->find($transId);
         
             if ($lending) {
                 return response()->json($lending);
             } else {
                 return response()->json(['error' => 'Lending details not found'], 404);
             }
         }
         

// public function getReturnDetails($transId)
// {
//     $transaction = Transaction::with('user', 'trans_details.book', 'trans_details.fines') // Eager load necessary relationships
//         ->where('trans_ID', $transId)
//         ->first();

//     if ($transaction) {
//         return response()->json($transaction);
//     } else {
//         return response()->json(['error' => 'Transaction not found'], 404);
//     }
// }


}
