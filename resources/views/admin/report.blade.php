<x-sidebar>
    <div class="content">
        <!-- Header -->
        <h5 id="return-books-header" class="ms-3 mt-2"><strong>Report</strong></h5>

        <!-- Card with Table -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="book-card">
            <div class="card-body">
                <!-- Search Bar and Buttons -->
                <div class="d-flex justify-content-between mb-0 p-0">
                    <div class="input-group w-50">
                        <h5 class="fw-bold">Lending Report</h5>
                    </div>
                </div>

                <hr class="mb-3 mt-0">

                <div class="d-flex justify-content-end mb-3">
                    <button id="add-book-btn" class="btn btn-add" style="background-color: #246484; font-size:1.1rem;">Generate Report</button>
                </div>


                <!-- Table -->
                <div id="book-table">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Date Borrowed</th>
                                <th>Date Returned</th>
                                <th>Total Books</th>
                                <th>Fines Paid</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample Row 1 -->
                            <tr>
                                <td>1</td>
                                <td>F. Scott Fitzgerald</td>
                                <td>1-2-2025</td>
                                <td>1-10-2025</td>
                                <td>3</td>
                                <td>P 0.00</td>
                                <td>
                                    <span class="badge bg-success text-white">Completed</span>
                                </td>
                            </tr>
                            <!-- Sample Row 2 -->
                            <tr>
                                <td>23</td>
                                <td>F. Scott Fitzgerald</td>
                                <td>1-2-2025</td>
                                <td>1-10-2025</td>
                                <td>3</td>
                                <td>P 0.00</td>
                                <td>
                                    <span class="badge bg-success text-white">Completed</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-sidebar>