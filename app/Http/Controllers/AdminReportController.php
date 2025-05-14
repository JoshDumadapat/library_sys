<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function showLendingReport(Request $request)
    {
        $reports = Transaction::with(['user', 'transDetails.book'])
            ->when($request->search, function ($query, $search) {
                $query->where('trans_ID', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            })
            ->when($request->from_date, function ($query) use ($request) {
                $query->whereDate('borrow_date', '>=', $request->from_date);
            })
            ->when($request->to_date, function ($query) use ($request) {
                $query->whereDate('borrow_date', '<=', $request->to_date);
            })
            ->orderByDesc('borrow_date')
            ->paginate(10);

        return view('admin.report', compact('reports'));
    }



    //rica added this:
    public function print(Request $request)
    {
        // Reuse the same query logic from your index method
        $reports = Transaction::with(['user', 'transDetails.book'])
            ->orderBy('borrow_date', 'desc');

        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $reports->where(function ($q) use ($search) {
                $q->where('trans_ID', 'like', "%$search%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%$search%")
                            ->orWhere('last_name', 'like', "%$search%");
                    });
            });
        }

        if ($request->has('from_date')) {
            $reports->where('borrow_date', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $reports->where('borrow_date', '<=', $request->to_date);
        }

        $reports = $reports->get()->map(function ($transaction) {
            return (object)[
                'trans_ID' => $transaction->trans_ID,
                'user' => $transaction->user,
                'borrow_date' => $transaction->borrow_date,
                'due_date' => $transaction->due_date,
                'return_date' => $transaction->return_date,
                'total_books' => $transaction->transDetails->count(),
                'total_fines' => $transaction->transDetails->sum('fine_amt'),
                'status' => $this->determineStatus($transaction)
            ];
        });

        return view('admin.report-print', [
            'reports' => $reports,
            'reportDate' => now()->format('F j, Y'),
            'search' => $request->search,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'totalBooks' => $reports->sum('total_books'),
            'totalFines' => $reports->sum('total_fines')
        ]);
    }

    private function determineStatus($transaction)
    {
        if ($transaction->return_date) {
            return 'returned';
        }

        return Carbon::now()->gt($transaction->due_date)
            ? 'overdue'
            : 'borrowed';
    }
}
