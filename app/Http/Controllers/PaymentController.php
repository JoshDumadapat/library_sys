<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Fine;
use App\Models\Transaction;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        DB::beginTransaction();

        try {
            // Normalize input data
            $request->merge([
                'items' => collect($request->items)->map(function ($item) {
                    return [
                        'tdetail_id' => $item['tdetail_ID'] ?? $item['tdetail_id'] ?? null,
                        'fine_amount' => $item['fine_amount']
                    ];
                })->toArray()
            ]);

            // Validate input
            $validated = $request->validate([
                'trans_id' => 'required|exists:transactions,trans_ID',
                'amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|in:cash,gcash,card,check',
                'items' => 'required|array|min:1',
                'items.*.tdetail_id' => 'required|exists:trans_details,tdetail_ID',
                'items.*.fine_amount' => 'required|numeric|min:0'
            ]);

            Log::info('Payment initiated', ['request' => $validated]);

            // Get all tdetail_ids for this payment
            $tdetailIds = collect($validated['items'])->pluck('tdetail_id');

            // Check payment status of these fines
            $paidFines = Fine::whereIn('tdetail_ID', $tdetailIds)
                ->where('fine_status', 'paid')
                ->whereNotNull('payment_id')
                ->get();

            if ($paidFines->isNotEmpty()) {
                Log::warning('Attempted to pay already paid fines', [
                    'paid_fines' => $paidFines->pluck('fine_id'),
                    'existing_payments' => $paidFines->pluck('payment_id')
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Some fines are already paid',
                    'paid_fines' => $paidFines->pluck('fine_id'),
                    'payment_ids' => $paidFines->pluck('payment_id')
                ], 422);
            }

            // Create payment record
            $payment = Payment::create([
                'trans_ID' => $validated['trans_id'],
                'amount' => $validated['amount'],
                'method' => $validated['payment_method'],
                'collected_by' => Auth::id(),
                'payment_date' => now(),
            ]);

            Log::info('Payment record created', ['payment_id' => $payment->payment_id]);

            // Update only payable fines
            $updatedCount = 0;
            foreach ($validated['items'] as $item) {
                $updateResult = Fine::where('tdetail_ID', $item['tdetail_id'])
                    ->where(function ($query) {
                        $query->where('fine_status', '!=', 'paid')
                            ->orWhereNull('payment_id');
                    })
                    ->update([
                        'fine_status' => 'paid',
                        'payment_id' => $payment->payment_id,
                        'collected_by' => Auth::id(),
                        'updated_at' => now(),
                    ]);

                Log::debug('Fine update attempt', [
                    'tdetail_id' => $item['tdetail_id'],
                    'rows_affected' => $updateResult,
                    'payment_id' => $payment->payment_id
                ]);

                $updatedCount += $updateResult;
            }

            // Verify all fines were updated
            if ($updatedCount !== count($validated['items'])) {
                Log::error('Payment count mismatch', [
                    'expected' => count($validated['items']),
                    'actual' => $updatedCount
                ]);
                throw new \Exception("Payment processed but only $updatedCount/" . count($validated['items']) . " fines were updated");
            }

            DB::commit();

            Log::info('Payment completed successfully', [
                'payment_id' => $payment->payment_id,
                'updated_fines' => $updatedCount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'payment_id' => $payment->payment_id,
                'updated_fines' => $updatedCount,
                'redirect_url' => route('admin.fines', ['payment' => 'success', 't' => now()->timestamp])
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Payment validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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

        DB::beginTransaction();

        try {
            foreach ($validated['items'] as $item) {
                $fine = Fine::updateOrCreate(
                    ['tdetail_ID' => $item['tdetail_id']],
                    [
                        'fine_amount' => $item['fine_amount'],
                        'fine_status' => 'pending',
                        'collected_by' => Auth::id(),
                        'trans_ID' => $validated['trans_id'],
                        'payment_id' => null,
                    ]
                );
                Log::debug('Fine marked as pending', ['fine_id' => $fine->fine_id]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Fines marked as pending successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Mark as pending failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark fines as pending: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processBulkPayment(Request $request)
    {
        $validated = $request->validate([
            'fine_ids' => 'required|array|min:1',
            'fine_ids.*' => 'exists:fines,fine_id',
            'method' => 'required|in:cash,gcash,card,check',
            'amount' => 'required|numeric|min:0.01',
            'reference' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            // Verify all fines are payable
            $unpayableFines = Fine::whereIn('fine_id', $validated['fine_ids'])
                ->where('fine_status', 'paid')
                ->whereNotNull('payment_id')
                ->get();

            if ($unpayableFines->isNotEmpty()) {
                Log::warning('Bulk payment attempted on paid fines', [
                    'paid_fines' => $unpayableFines->pluck('fine_id')
                ]);
                return response()->json([
                    'success' => false,
                    'message' => $unpayableFines->count() . ' fine(s) are already paid',
                    'paid_fines' => $unpayableFines->pluck('fine_id')
                ], 422);
            }

            // Get transaction ID from first fine
            $firstFine = Fine::findOrFail($validated['fine_ids'][0]);
            $transId = $firstFine->trans_ID;

            // Create payment
            $payment = Payment::create([
                'trans_ID' => $transId,
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'reference' => $validated['reference'] ?? null,
                'collected_by' => Auth::id(),
                'payment_date' => now(),
            ]);

            Log::info('Bulk payment record created', ['payment_id' => $payment->payment_id]);

            // Update fines
            $updatedCount = Fine::whereIn('fine_id', $validated['fine_ids'])
                ->where(function ($query) {
                    $query->where('fine_status', '!=', 'paid')
                        ->orWhereNull('payment_id');
                })
                ->update([
                    'fine_status' => 'paid',
                    'payment_id' => $payment->payment_id,
                    'collected_by' => Auth::id(),
                    'updated_at' => now(),
                ]);

            Log::info('Bulk payment fines updated', [
                'expected' => count($validated['fine_ids']),
                'actual' => $updatedCount
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk payment processed successfully',
                'payment_id' => $payment->payment_id,
                'updated_fines' => $updatedCount,
                'redirect_url' => route('admin.fines', ['payment' => 'success', 't' => now()->timestamp])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk payment failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Bulk payment failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
