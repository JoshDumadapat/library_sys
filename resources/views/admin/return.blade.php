<x-sidebar>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="content">
        <!-- Header -->
        <h5 id="return-books-header" class="ms-3 mt-2"><strong>Return</strong></h5>

        <!-- Card with Table -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="book-card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-0 p-0">
                    <div class="input-group w-50">
                        <h5 class="fw-bold">Lending Details</h5>
                    </div>
                </div>

                <hr class="mb-3 mt-0">

                <div class="d-flex justify-content-between mb-3">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" placeholder="Search Books" aria-label="Search Books">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div id="book-table">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Lend ID</th>
                                <th>Member Name</th>
                                <th>Date Borrowed</th>
                                <th>Due Date</th>
                                <th>Total Books</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lendings as $lending)
                            <tr>
                                <td>{{ $lending->trans_ID }}</td>
                                <td>{{ optional($lending->user)->first_name }} {{ optional($lending->user)->last_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($lending->borrow_date)->format('F j, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($lending->due_date)->format('F j, Y') }}</td>
                                <td>{{ $lending->transDetails->count() }}</td>
                                <td>
                                    @if($lending->return_date)
                                    <span class="badge bg-success">Returned</span>
                                    @else
                                    <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="lending-detail" data-id="{{ $lending->trans_ID }}">
                                        <i class="bi bi-eye me-1"></i>&nbsp;View Details
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3" id="pagination">
                    {{ $lendings->links() }}
                </div>
            </div>
        </div>

        <!-- Return Details Form -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px; display: none;" id="add-book-form-card">
            <div class="card-body px-4 py-4">
                <!-- Lending Details Header -->
                <div class="row">
                    <h5 class="fw-bold">Lending Details</h5>
                    <hr class="mb-4">
                </div>

                <!-- Lending ID and Borrow Date -->
                <div class="row mb-0 mt-3">
                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;">
                            <strong>Lending ID:</strong> <span id="lend-id"></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;">
                            <strong>Borrow Date:</strong> <span id="borrow-date"></span>
                        </p>
                    </div>

                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;">
                            <strong>Member Name:</strong> <span id="member-name"></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;">
                            <strong>Status:</strong> <span id="status"></span>
                        </p>
                    </div>

                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;">
                            <strong>Total Books Borrowed:</strong> <span id="total-books"></span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;">
                            <strong>Due Date:</strong> <span id="due-date"></span>
                        </p>
                    </div>
                </div>

                <!-- Book Information Header -->
                <div class="row mt-4">
                    <h5 class="fw-bold">Book Information</h5>
                    <hr class="mb-4">
                </div>

                <!-- Lended Book Table Row -->
                <div class="row mb-4" style="max-height: 280px; overflow-y: auto;">
                    <div class="col-md-12">
                        <form id="returnForm" action="" method="POST">
                            @csrf
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Book ID</th>
                                        <th>Title</th>
                                        <th>ISBN</th>
                                        <th>Return Status</th>
                                        <th>Fine Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="book-table-body">
                                    <!-- Rows inserted by JavaScript -->
                                </tbody>
                            </table>

                            <div class="total-fine-container mt-3 mb-3">
                                <span class="fw-bold">Total Fine:</span>
                                <span class="total-fine fw-bold">₱0.00</span>
                            </div>

                            <div id="book-status-fines-container">
                                <!-- Hidden inputs inserted dynamically -->
                            </div>

                            <!-- Action Buttons -->
                            <div class="row mb-3">
                                <div class="col-md-12 text-end">
                                    <button type="submit" class="btn btn-add me-2" style="font-size: 1.1rem;">
                                        <i class="bi bi-check-circle"></i> Return Book
                                    </button>
                                    <button id="cancel-btn" type="button" class="btn btn-view" style="font-size: 1.1rem;">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add these modals at the bottom of your Blade file -->
    <!-- Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Return Receipt</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="receiptContent">
                    <!-- Receipt content will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="printReceiptBtn" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Options Modal -->
    <div class="modal fade" id="paymentOptionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Payment Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Total amount due: <span id="paymentTotalAmount" class="fw-bold">₱0.00</span></p>
                    <div class="d-grid gap-2">
                        <button type="button" id="payNowBtn" class="btn btn-success">
                            <i class="bi bi-credit-card"></i> Pay Now
                        </button>
                        <button type="button" id="payLaterBtn" class="btn btn-outline-secondary">
                            <i class="bi bi-clock"></i> Pay Later
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Form Modal -->
    <div class="modal fade" id="paymentFormModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Process Payment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm">
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" id="paymentMethod" required>
                                <option value="">Select method</option>
                                <option value="cash">Cash</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="gcash">GCash</option>
                            </select>
                        </div>
                        <div class="mb-3" id="cashFields" style="display: none;">
                            <label class="form-label">Amount Tendered</label>
                            <input type="number" step="0.01" class="form-control" id="amountTendered">
                            <div id="changeAmount" class="text-success mt-2" style="display: none;"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount Due</label>
                            <input type="text" class="form-control" id="amountDue" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmPaymentBtn" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/return.js')
</x-sidebar>