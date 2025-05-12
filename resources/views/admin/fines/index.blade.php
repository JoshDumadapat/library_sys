<x-sidebar>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="content">
        <!-- Header -->
        <h5 id="fines-header" class="ms-3 mt-2"><strong>Fines Management</strong></h5>

        <!-- Card with Table -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="fines-card">
            <div class="card-body">
                <!-- Search Bar and Buttons -->
                <div class="d-flex justify-content-between mb-0 p-0">
                    <div class="input-group w-50">
                        <h5 class="fw-bold">Fines Report</h5>
                    </div>

                    <div>
                        <button id="generate-report-btn" class="btn btn-add" style="background-color: #246484; font-size:1.1rem;">
                            <i class="bi bi-file-earmark-text"></i> Generate Report
                        </button>
                    </div>
                </div>

                <hr class="mb-3 mt-0">

                <!-- Table -->
                <div id="fines-table">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Member</th>
                                <th>Fines Count</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Transaction Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            @php
                            // Calculate values manually
                            $finesCount = 0;
                            $totalAmount = 0;
                            $hasUnpaid = false;
                            $hasPaid = false;

                            foreach ($transaction->transDetails as $detail) {
                            $finesCount += $detail->fines->count();
                            foreach ($detail->fines as $fine) {
                            $totalAmount += $fine->fine_amt;
                            if ($fine->fine_status == 'paid') {
                            $hasPaid = true;
                            } else {
                            $hasUnpaid = true;
                            }
                            }
                            }

                            $status = $hasUnpaid ? 'unpaid' : ($hasPaid ? 'paid' : 'pending');
                            $formattedDate = $transaction->created_at->format('F d, Y');
                            @endphp
                            <tr>
                                <td>{{ $transaction->trans_ID }}</td>
                                <td>
                                    {{ $transaction->user->first_name }} {{ $transaction->user->last_name }}
                                </td>
                                <td>{{ $finesCount }}</td>
                                <td>₱{{ number_format($totalAmount, 2) }}</td>
                                <td>
                                    <span class="badge 
                    @if($status == 'paid') bg-success
                    @elseif($status == 'unpaid') bg-danger
                    @else bg-warning @endif">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>{{ $formattedDate }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary view-fines-btn"
                                        data-transaction-id="{{ $transaction->trans_ID }}"
                                        data-member-id="{{ $transaction->user->id }}">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Fines Details Modal -->
    <div class="modal fade" id="finesDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Fines Details for Transaction TRANS-<span id="modalTransactionId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6>Member: <span id="modalMemberName" class="fw-bold"></span></h6>
                        <h6>Transaction Date: <span id="modalTransactionDate" class="fw-bold"></span></h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Amount</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Date Issued</th>
                                </tr>
                            </thead>
                            <tbody id="finesDetailsBody">
                                <!-- Fines details will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <h5>Total Amount Due: <span id="totalAmountDue" class="fw-bold">₱0.00</span></h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="payAllFinesBtn">Pay All Fines</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Process Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="paymentForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="transaction_id" id="paymentTransactionId">
                        <input type="hidden" name="fine_ids" id="paymentFineIds">

                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Payment Method</label>
                            <select name="method" id="paymentMethod" class="form-select" required>
                                <option value="">Select method</option>
                                <option value="cash">Cash</option>
                                <option value="gcash">GCash</option>
                                <option value="card">Credit Card</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="paymentAmountInput" class="form-label">Amount</label>
                            <input type="number" name="amount" id="paymentAmountInput" class="form-control" required readonly>
                        </div>
                        <div id="cashFields" style="display: none;">
                            <div class="mb-3">
                                <label for="amountTendered" class="form-label">Amount Tendered</label>
                                <input type="number" id="amountTendered" class="form-control" step="0.01" min="0">
                            </div>
                            <div id="changeAmount" class="mb-3" style="display: none;"></div>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="receiptModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="receiptContent"></div>
                </div>
            </div>
        </div>
    </div>
</x-sidebar>

@vite(['resources/js/pagination.js'])
@vite(['resources/js/payment.js'])