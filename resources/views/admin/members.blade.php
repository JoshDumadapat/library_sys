<x-sidebar>
    <div class="content">
        <!-- Header -->
        <h5 id="return-books-header" class="ms-3 mt-2"><strong>Members</strong></h5>

        <!-- Card with Table -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="book-card">
            <div class="card-body">
                <!-- Search Bar and Buttons -->
                <div class="d-flex justify-content-between mb-0 p-0">
                    <div class="input-group w-50">
                        <h5 class="fw-bold">Members List</h5>
                    </div>
                </div>

                <hr class="mb-3 mt-0">

                <div class="d-flex justify-content-between mb-3">
                    <div class="input-group w-100">
                        <input type="text" class="form-control" placeholder="Search Member" aria-label="Search Member">
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
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Actions</th>
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
                                <td><span class="badge bg-warning text-black">Inactive</span></td>
                                <td>
                                    <button id="lending-detail" class="btn btn-delete" style="border-radius: 8px;"><i class="bi bi-person-x me-1"></i>&nbsp;Deactivate</button>
                                    <button class="btn btn-add" style="border-radius: 8px;"><i class="bi bi-person-check me-1"></i>Activate</button>
                                </td>
                            </tr>
                            <!-- Sample Row 2 -->
                            <tr>
                                <td>23</td>
                                <td>F. Scott Fitzgerald</td>
                                <td>1-2-2025</td>
                                <td>1-10-2025</td>
                                <td>3</td>
                                <td><span class="badge bg-success">Active</span></td>
                                <td>
                                    <button id="lending-detail" class="btn btn-delete" style="border-radius: 8px;"><i class="bi bi-person-x me-1"></i>&nbsp;Deactivate</button>
                                    <button class="btn btn-add" style="border-radius: 8px;"><i class="bi bi-person-check me-1"></i>Activate</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-sidebar>