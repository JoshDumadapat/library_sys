<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransDetail;
use App\Models\Fine;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\FineType;
use Illuminate\Support\Facades\DB;

class ReturnBookController extends Controller
{
    public function returnBook(Request $request)
    {
        $validated = $request->validate([
            'trans_id' => 'required|exists:transactions,trans_ID',
            'return_date' => 'required|date',
            'books' => 'required|array',
            'books.*.tdetail_id' => 'required|exists:trans_details,tdetail_ID',
            'books.*.status' => 'required|in:returned,damaged,lost,overdue'
        ]);

        return DB::transaction(function () use ($validated) {
            $transaction = Transaction::findOrFail($validated['trans_id']);
            $transaction->return_date = $validated['return_date'];
            $transaction->save();

            $totalFine = 0;

            foreach ($validated['books'] as $bookData) {
                $transDetail = TransDetail::findOrFail($bookData['tdetail_id']);
                $transDetail->td_status = $bookData['status'];
                $transDetail->save();

                if ($bookData['status'] !== 'returned') {
                    $fineType = FineType::where('reason', ucfirst($bookData['status']))->first();

                    if ($fineType) {
                        $fineAmount = $fineType->is_per_day
                            ? $this->calculateOverdueFine($transDetail, $fineType)
                            : $fineType->default_amount;

                        Fine::create([
                            'tdetail_ID' => $transDetail->tdetail_ID,
                            'trans_ID' => $transaction->trans_ID,
                            'fine_amt' => $fineAmount,
                            'reason' => $bookData['status'],
                            'fine_status' => 'unpaid',
                            'ftype_id' => $fineType->ftype_id
                        ]);

                        $totalFine += $fineAmount;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'total_fine' => $totalFine,
                'has_fines' => $totalFine > 0
            ]);
        });
    }

    public function showLendingRecords()
    {
        $lendings = Transaction::with(['user', 'transDetails.book'])
            ->whereNull('return_date')
            ->paginate(10);

        return view('admin.return', compact('lendings'));
    }

    // In your ReturnBookController.php
    public function processReturn(Request $request, $trans_id)
    {
        $validated = $request->validate([
            'return_date' => 'required|date',
            'status' => 'required|array',
            'status.*' => 'in:returned,damaged,lost,overdue',
            'fine' => 'sometimes|array'
        ]);

        return DB::transaction(function () use ($trans_id, $validated) {
            $transaction = Transaction::with('transDetails')->findOrFail($trans_id);
            $transaction->return_date = $validated['return_date'];
            $transaction->save();

            $totalFine = 0;
            $items = [];

            foreach ($transaction->transDetails as $transDetail) {
                if (isset($validated['status'][$transDetail->tdetail_ID])) {
                    $status = $validated['status'][$transDetail->tdetail_ID];
                    $transDetail->td_status = $status;
                    $transDetail->save();

                    $fineAmount = $validated['fine'][$transDetail->tdetail_ID] ?? 0;

                    if ($status !== 'returned' && $fineAmount > 0) {
                        $fineType = FineType::where('reason', ucfirst($status))->first();

                        if ($fineType) {
                            Fine::create([
                                'tdetail_ID' => $transDetail->tdetail_ID,
                                'trans_ID' => $transaction->trans_ID,
                                'fine_amt' => $fineAmount,
                                'reason' => $status,
                                'fine_status' => 'unpaid',
                                'ftype_id' => $fineType->ftype_id
                            ]);

                            $totalFine += $fineAmount;
                        }
                    }

                    $items[] = [
                        'tdetail_id' => $transDetail->tdetail_ID,
                        'book_id' => $transDetail->book->book_id,
                        'title' => $transDetail->book->title,
                        'status' => $status,
                        'fine' => $fineAmount
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'total_fine' => $totalFine,
                'has_fines' => $totalFine > 0,
                'items' => $items,
                'member_name' => $transaction->user->first_name . ' ' . $transaction->user->last_name
            ]);
        });
    }

    public function getLendingDetails($transId)
    {
        $lending = Transaction::with(['user', 'transDetails.book', 'transDetails.fines'])
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

    public function getFineRate($reason)
    {
        $fineType = FineType::where('reason', ucfirst($reason))->first();

        if (!$fineType) {
            return response()->json(['error' => 'Fine type not found for: ' . $reason], 404);
        }

        return response()->json([
            'fine_amount' => $fineType->default_amount,
            'is_per_day' => $fineType->is_per_day,
        ]);
    }

    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'trans_id' => 'required|exists:transactions,trans_ID',
            'payment_method' => 'required|in:cash,credit,gcash',
            'amount' => 'required|numeric|min:0',
            'amount_tendered' => 'required_if:payment_method,cash|numeric|min:0'
        ]);

        // Process payment logic here
        // Update fine statuses, create payment record, etc.

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully'
        ]);
    }

    protected function calculateOverdueFine($transDetail, $fineType)
    {
        $dueDate = Carbon::parse($transDetail->transaction->due_date);
        $returnDate = Carbon::now(); // Using current date if return_date isn't set

        if ($transDetail->transaction->return_date) {
            $returnDate = Carbon::parse($transDetail->transaction->return_date);
        }

        $overdueDays = $returnDate->diffInDays($dueDate, false);

        if ($overdueDays > 0) {
            return $fineType->default_amount * $overdueDays;
        }

        return $fineType->default_amount; // Minimum fine even if not overdue
    }

    public function updateBookStatus(Request $request, $trans_id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'return_date' => 'required|date',
            'status' => 'required|array',
            'status.*' => 'required|in:returned,damaged,lost,overdue',
            'fine' => 'sometimes|array',
            'fine.*' => 'nullable|numeric|min:0'
        ]);

        // Start database transaction
        return DB::transaction(function () use ($trans_id, $validated) {
            // Find the transaction with books
            $transaction = Transaction::with(['transDetails.book'])->findOrFail($trans_id);

            // Update transaction return date
            $transaction->return_date = $validated['return_date'];
            $transaction->save();

            $totalFine = 0;

            // Process each book in the transaction
            foreach ($transaction->transDetails as $transDetail) {
                if (isset($validated['status'][$transDetail->tdetail_ID])) {
                    $status = $validated['status'][$transDetail->tdetail_ID];

                    // Update book status
                    $transDetail->td_status = $status;
                    $transDetail->save();

                    // Handle book copies based on status
                    if ($transDetail->book) {
                        if ($status === 'lost') {
                            // For lost books: permanently reduce total copies
                            $transDetail->book->decrement('total_copies');
                        }
                        // No action needed for returned books since we don't track available copies
                    }

                    // Process fines if status is not 'returned'
                    if ($status !== 'returned') {
                        $fineAmount = $validated['fine'][$transDetail->tdetail_ID] ?? 0;

                        if ($fineAmount > 0) {
                            // Find the fine type
                            $fineType = FineType::where('reason', ucfirst($status))->first();

                            if ($fineType) {
                                // Create fine record
                                Fine::create([
                                    'tdetail_ID' => $transDetail->tdetail_ID,
                                    'trans_ID' => $transaction->trans_ID,
                                    'fine_amt' => $fineAmount,
                                    'reason' => $status,
                                    'fine_status' => 'unpaid',
                                    'ftype_id' => $fineType->ftype_id
                                ]);

                                $totalFine += $fineAmount;
                            }
                        }
                    }
                }
            }

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Book statuses updated successfully',
                'total_fine' => $totalFine,
                'has_fines' => $totalFine > 0
            ]);
        });
    }
}
