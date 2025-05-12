<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Fine;
use App\Models\Transaction;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        try {

            $request->merge([
                'items' => collect($request->items)->map(function ($item) {
                    return [
                        'tdetail_id' => $item['tdetail_ID'] ?? $item['tdetail_id'] ?? null,
                        'fine_amount' => $item['fine_amount']
                    ];
                })->toArray()
            ]);

            $validated = $request->validate([
                'trans_id' => 'required|exists:transactions,trans_ID', // matches your DB column
                'amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|in:cash,gcash,card,check',
                'items' => 'required|array',
                'items.*.tdetail_id' => 'required|exists:trans_details,tdetail_ID', // matches DB
                'items.*.fine_amount' => 'required|numeric|min:0'
            ]);



            DB::beginTransaction();

            // 1. Create Payment Record - use consistent field names
            $payment = Payment::create([
                'trans_ID' => $validated['trans_id'], // use validated key (trans_id)
                'amount' => $validated['amount'],
                'method' => $validated['payment_method'],

            ]);

            // 2. Update Fines - use consistent field names
            foreach ($validated['items'] as $item) {
                Fine::where('tdetail_ID', $item['tdetail_id'])
                    ->update([
                        'fine_status' => 'paid',
                        'payment_id' => $payment->payment_id,

                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'payment_id' => $payment->payment_id,
                'redirect_url' => route('admin.fines', ['payment' => 'success'])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ], 500);
        }
    }


    public function markAsPending(Request $request)
    {
        $validated = $request->validate([
            'trans_id' => 'required|exists:transactions,trans_ID',
            'items' => 'required|array|min:1',
            'items.*.tdetail_id' => 'required|exists:trans_details,tdetail_ID',
            'items.*.fine_amount' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $item) {
                Fine::updateOrCreate(
                    ['tdetail_id' => $item['tdetail_id']],
                    [
                        'fine_amount' => $item['fine_amount'],
                        'fine_status' => 'pending',
                        'collected_by' => Auth::id(),
                        'trans_ID' => $validated['trans_id']
                    ]
                );
            }
        });

        return response()->json(['success' => true]);
    }

    public function processBulkPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'fine_ids' => 'required|json',
                'method' => 'required|in:cash,gcash,card,check',
                'amount' => 'required|numeric|min:0.01',
                'reference' => 'nullable|string'
            ]);

            $fineIds = json_decode($validated['fine_ids'], true);

            if (!is_array($fineIds) || empty($fineIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or empty fine IDs provided.'
                ], 422);
            }

            DB::beginTransaction();

            // Get the transaction ID from the first fine
            $firstFine = Fine::where('fine_id', $fineIds[0])->firstOrFail();
            $transId = $firstFine->trans_ID;

            // Create Payment
            $payment = Payment::create([
                'trans_ID' => $transId,
                'amount' => $validated['amount'],
                'method' => $validated['method'],
            ]);

            // Update fines as paid
            Fine::whereIn('fine_id', $fineIds)->update([
                'fine_status' => 'paid',
                'payment_id' => $payment->payment_id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk payment processed successfully.',
                'payment_id' => $payment->payment_id,
                'redirect_url' => route('admin.fines', ['payment' => 'success'])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bulk payment processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateFineStatus(Request $request)
    {
        $validated = $request->validate([
            'fine_id' => 'required|exists:fines,fine_id',
            'payment_id' => 'required|exists:payments,payment_id'
        ]);

        Fine::where('fine_id', $validated['fine_id'])
            ->update([
                'fine_status' => 'paid',
                'payment_id' => $validated['payment_id'],
                'paid_at' => now()
            ]);

        return response()->json(['success' => true]);
    }
}
