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
                            <!-- Sample Row 1 -->
                            <tr>
                                <td>1</td>
                                <td>F. Scott Fitzgerald</td>
                                <td>1-2-2025</td>
                                <td>1-10-2025</td>
                                <td>3</td>
                                <td><span class="badge bg-warning text-black">Pending</span></td>
                                <td>
                                <button id="lending-detail" class="btn btn-view" style="border-radius: 5px;">
                                    <i class="bi bi-eye me-1"></i>&nbsp;View Details
                                </button>

                                </td>
                            </tr>
                            <!-- Sample Row 2 -->
                            <tr>
                                <td>23</td>
                                <td>F. Scott Fitzgerald</td>
                                <td>1-2-2025</td>
                                <td>1-10-2025</td>
                                <td>3</td>
                                <td><span class="badge bg-danger">Overdue</span></td>
                                <td>
                                    <button class="btn btn-view" style="border-radius: 5px;"><i class="bi bi-eye me-1"></i>&nbsp;View Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3" id="pagination">
                </div>
            </div>
        </div>

        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px; display: none;" id="add-book-form-card">
            <div class="card-body px-4 py-4">

                <!-- Lending Details Header -->
                <div class="row">
                    <h5 class="fw-bold">Lending Details</h5>
                    <hr class="mb-4">
                </div>

                <!-- Lending ID and Total Books -->
                <div class="row mb-0 mt-3">
                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Lending ID:</strong> LND-00123</p>
                    </div>

                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Borrow Date:</strong> 2025-04-01</p>
                    </div>
                </div>

                <!-- Member Name and Status -->
                <div class="row mb-0">
                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Member Name:</strong> John Doe</p>
                    </div>
                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Status:</strong> Pending</p>
                    </div>
                </div>

                <!-- Borrow and Due Dates -->
                <div class="row mb-0">

                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Total Books Borrowed:</strong> 3</p>
                    </div>
                    <div class="col-md-6 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Due Date:</strong> 2025-04-10</p>
                    </div>
                </div>

                <!-- Book Information Header -->
                <div class="row mt-4">
                    <h5 class="fw-bold">Book Information</h5>
                    <hr class="mb-4">
                </div>

                <!-- Book Table -->
                <div class="row mb-4" style="max-height: 280px; overflow-y: auto;">
                    <div class="col-md-12">
                        <table class=" custom-table  ">
                            <thead>
                                <tr>
                                    <th>Book ID</th>
                                    <th>Title</th>
                                    <th>ISBN</th>
                                    <th>Return Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>BK001</td>
                                    <td>Introduction to Algorithms</td>
                                    <td>9780262033848</td>
                                    <td>Returned</td>
                                </tr>
                                <tr>
                                    <td>BK002</td>
                                    <td>Clean Code</td>
                                    <td>9780132350884</td>
                                    <td>Missing</td>
                                </tr>
                                <tr>
                                    <td>BK003</td>
                                    <td>Design Patterns</td>
                                    <td>9780201633610</td>
                                    <td>Damaged</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Fine Details -->
                <div class="row mb-0 mt-5">
                    <div class="col-md-12 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Overdue Fine:</strong> ₱30.00</p>
                    </div>
                    <div class="col-md-12 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Missing Book Fine:</strong> ₱500.00</p>
                    </div>
                    <div class="col-md-12 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Damaged Book Fine:</strong> ₱100.00</p>
                    </div>
                    <div class="col-md-12 mb-0">
                        <p style="font-size: 1.1rem;"><strong>Total Fine:</strong> ₱630.00</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-3">
                    <div class="col-md-12 text-end">
                        <button class="btn btn-add me-2" style="font-size: 1.1rem;">Return Book</button>
                        <button id="cancel-btn" class="btn btn-view" style="font-size: 1.1rem;">Cancel</button>
                    </div>
                </div>
            </div>
        </div>



    </div>
</x-sidebar>
@vite('resources/js/pagination.js') 
@vite('resources/js/return.js') 