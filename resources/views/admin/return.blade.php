<x-sidebar>
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
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
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
                        <tbody id="book-table-body">
                                @foreach($lendings as $lending)
                                <tr>
                                    <td>{{ $lending->trans_ID }}</td>
                                    <td>{{ optional($lending->user)->first_name }} {{ optional($lending->user)->last_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lending->borrow_date)->format('F j, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($lending->due_date)->format('F j, Y') }}</td>
                                    <td>{{ $lending->transDetails->count() }}</td>  <!-- Assuming you want to show the number of books in this transaction -->
                                    <td>
                                        <!-- Status Logic (optional) -->
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
                </div>
            </div>
        </div>

        <!-- FORM AFTER VIEW DETAILS-->

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
        <!-- Form should wrap the entire table -->
        <form action="{{ route('update.book.status', ['trans_id' => $lending->trans_ID]) }}" method="POST">
            @csrf
            @method('POST')

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
            <!-- Rows will be inserted here by JavaScript -->
                  </tbody>
            </table>

            <!-- Submit button inside the form -->
            <div class="mt-3">
                <button type="submit" class="btn btn-success">Update Book Statuses</button>
            </div>
        </form>
            </div>
        </div>

                <hr>

          <!-- Fine Details row -->
                <div class="row mb-0 mt-5">
                    <div class="col-md-12 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Overdue Fine:</strong> <span class="overdue-fine">₱0.00</span></p>
                    </div>
                    <div class="col-md-12 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Missing Book Fine:</strong> <span class="missing-fine">₱0.00</span></p>
                    </div>
                    <div class="col-md-12 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Damaged Book Fine:</strong> <span class="damaged-fine">₱0.00</span></p>
                    </div>
                    <div class="col-md-12 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Total Fine:</strong> <span id="total-fine">₱0.00</span></p>
                    </div>
                </div>


                        <!-- Action Buttons -->
                        <div class="row mb-3">
                            <div class="col-md-12 text-end">
                                <button class="btn btn-add me-2" style="font-size: 1.1rem;">Return Book</button>
                                <button id="cancel-btn" type="button" class="btn btn-view" style="font-size: 1.1rem;">Cancel</button>

                            </div>
                        </div>
                </div>        
            </div>
        </div>
        </form>
    </div>
</x-sidebar>
@vite('resources/js/pagination.js') 
@vite('resources/js/return.js') 