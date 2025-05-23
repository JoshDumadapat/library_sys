<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Fine;
use App\Models\Payment;
use App\Models\TransDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all transactions with unpaid fines
     */
    public function index()
    {
        $transactions = Transaction::has('transDetails.fines')
            ->with(['user', 'transDetails.book', 'transDetails.fines'])
            ->orderBy('created_at', 'desc') // Add this line to sort by latest first
            ->paginate(10);

        return view('admin.fines.index', compact('transactions'));
    }
    /**
     * Get fines details for a specific transaction
     */
    public function getTransactionFines($transactionId)
    {
        $transaction = Transaction::with([
            'user:id,first_name,last_name,id',
            'transDetails' => function ($query) {
                $query->with([
                    'book:book_id,title',
                    'fines'
                ]);
            }
        ])
            ->findOrFail($transactionId);

        $fines = $transaction->transDetails->flatMap(function ($detail) {
            return $detail->fines->map(function ($fine) use ($detail) {
                return [
                    'fine_id' => $fine->fine_id,
                    'book_title' => $detail->book->title ?? 'Unknown Book',
                    'amount' => $fine->fine_amt,
                    'reason' => $fine->reason,
                    'status' => $fine->fine_status,
                    'created_at' => $fine->created_at->format('F d, Y')
                ];
            });
        });

        return response()->json([
            'success' => true,
            'transaction' => [
                'id' => $transaction->trans_ID,
                'member_name' => $transaction->user->first_name . ' ' . $transaction->user->last_name,
                'member_id' => $transaction->user->user_id, // Fixed null member_id
                'transaction_date' => $transaction->created_at->format('F d, Y')
            ],
            'fines' => $fines,
            'total_amount' => $fines->sum('amount')
        ]);
    }

    /**
     * Process payment for multiple fines
     */
    public function processPayment(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,trans_ID',
            'fine_ids' => 'required|array',
            'fine_ids.*' => 'exists:fines,fine_id',
            'method' => 'required|in:cash,gcash,card',
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string|max:255'
        ]);

        return DB::transaction(function () use ($validated) {
            $fines = Fine::whereIn('fine_id', $validated['fine_ids'])
                ->where('fine_status', '!=', 'paid')
                ->get();

            if ($fines->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No unpaid fines found for the selected transaction'
                ], 400);
            }

            $totalAmount = $fines->sum('fine_amt');

            if (abs($totalAmount - $validated['amount']) > 0.01) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount does not match total fines amount'
                ], 400);
            }

            $payment = Payment::create([
                'trans_ID' => $validated['transaction_id'],
                'payment_amount' => $validated['amount'],
                'payment_method' => $validated['method'],
                'reference_number' => $validated['reference'] ?? null,
                'payment_date' => now(),
                'payment_status' => 'completed'
            ]);

            Fine::whereIn('fine_id', $validated['fine_ids'])
                ->update([
                    'fine_status' => 'paid',
                    'payment_id' => $payment->payment_id
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'payment_id' => $payment->payment_id
            ]);
        });
    }

    public function showFines()
    {
        return $this->index(); // Just calls the existing index method
    }


    //rica added this
    /**
     * Generate fines report
     */
    public function generateReport(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = Transaction::has('transDetails.fines')
            ->with(['user', 'transDetails.book', 'transDetails.fines'])
            ->orderBy('created_at', 'desc');

        // Apply status filter if not 'all'
        if ($status !== 'all') {
            $query->whereHas('transDetails.fines', function ($q) use ($status) {
                if ($status === 'paid') {
                    $q->where('fine_status', 'paid');
                } elseif ($status === 'unpaid') {
                    $q->where('fine_status', 'unpaid');
                } elseif ($status === 'pending') {
                    $q->where('fine_status', 'pending');
                }
            });
        }

        $transactions = $query->get();

        $totalAmount = 0;
        $paidAmount = 0;
        $unpaidAmount = 0;
        $pendingAmount = 0;

        // Process transactions for report data
        $reportData = $transactions->map(function ($transaction) use (&$totalAmount, &$paidAmount, &$unpaidAmount, &$pendingAmount) {
            $finesCount = 0;
            $transactionTotal = 0;
            $hasUnpaid = false;
            $hasPaid = false;

            foreach ($transaction->transDetails as $detail) {
                $finesCount += $detail->fines->count();
                foreach ($detail->fines as $fine) {
                    $transactionTotal += $fine->fine_amt;
                    $totalAmount += $fine->fine_amt;

                    if ($fine->fine_status == 'paid') {
                        $paidAmount += $fine->fine_amt;
                        $hasPaid = true;
                    } elseif ($fine->fine_status == 'unpaid') {
                        $unpaidAmount += $fine->fine_amt;
                        $hasUnpaid = true;
                    } else {
                        $pendingAmount += $fine->fine_amt;
                    }
                }
            }

            $status = $hasUnpaid ? 'unpaid' : ($hasPaid ? 'paid' : 'pending');

            return [
                'transaction_id' => $transaction->trans_ID,
                'member_name' => $transaction->user->first_name . ' ' . $transaction->user->last_name,
                'fines_count' => $finesCount,
                'total_amount' => $transactionTotal,
                'status' => $status,
                'transaction_date' => $transaction->created_at->format('F d, Y')
            ];
        });

        return view('admin.fines.report', [
            'transactions' => $reportData,
            'totalAmount' => $totalAmount,
            'paidAmount' => $paidAmount,
            'unpaidAmount' => $unpaidAmount,
            'pendingAmount' => $pendingAmount,
            'filterStatus' => $status,
            'reportDate' => now()->format('F d, Y')
        ]);
    }
}
