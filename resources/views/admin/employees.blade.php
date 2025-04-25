<x-sidebar>
    <div class="content">
        <!-- Header -->
        <h5 id="employees-header" class="ms-3 mt-2"><strong>Employees</strong></h5>

        <!-- Card with Table -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="book-card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-0 p-0">
                    <div class="input-group w-50">
                        <h5 class="fw-bold">List of Employess</h5>
                    </div>
                </div>

                <hr class="mb-3 mt-0">
                <div class="d-flex justify-content-between mb-3">
                    <div class="input-group w-50">
                        <input type="text" class="form-control" placeholder="Search Employee" aria-label="Search Employee">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <div>
                        <button id="employees-btn" class="btn me-2 btn-add" style="background-color: #246484; color: white;">+ Add Employee</button>
                    </div>
                </div>

                <!-- Table -->
                <div id="book-table">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Role</th>
                                <th>Actions</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>F. Scott Fitzgerald</td>
                                <td>f@gmail.com</td>
                                <td>09726358423</td>
                                <td>Davao City</td>
                                <td>Librarianitor</td>
                                <td>
                                    <button id="lending-detail" class="btn btn-view" style="border-radius: 8px;"><i class="bi bi-eye me-1"></i>&nbsp;View</button>
                                    <button class="btn btn-delete" style="border-radius: 8px;"><i class="bi bi-person-x me-1"></i>&nbsp;Deactivate</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px; display: none;" id="add-employee-form-card">
            <div class="card-body px-4 py-4">

                <!-- Employee Information -->
                <div class="row">
                    <h5 class="fw-bold">Employee Information</h5>
                    <hr class="mb-4">
                </div>
                <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="row mb-3 ">
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">First name <span class="text-danger">*</span></small>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Last name <span class="text-danger">*</span></small>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Contact No. <span class="text-danger">*</span></small>
                                <input type="text" name="contact_num" value="{{ old('contact_num') }}" class="form-control" required minlength="11" maxlength="11">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Street <span class="text-danger">*</span></small>
                                <input type="text" name="street" value="{{ old('street') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">City <span class="text-danger">*</span></small>
                                <input type="text" name="city" value="{{ old('city') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Region <span class="text-danger">*</span></small>
                                <input type="text" name="region" value="{{ old('region') }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Role <span class="text-danger">*</span></small>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                            </div>                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Email <span class="text-danger">*</span></small>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <small class="text-start d-block">Password <span class="text-danger">*</span></small>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-5 ">
                                <small class="text-start d-block">Confirm Password <span class="text-danger">*</span></small>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                    </form>

                <hr>

                <!-- Lending Details -->
                <div class="row mb-4">
                    <div class="col-12 col-md-12 mt-4 text-center">
                        <button class="btn btn-view me-2" id="cancel-btn" style="font-size: 1.1rem;">Cancel</button>
                        <button class="btn btn-addbook" id="lend-books-btn" style="font-size: 1.1rem;">+ Add Employee</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-sidebar>


<script>
    // JavaScript to toggle between table and form
    document.getElementById('employees-btn').addEventListener('click', function() {
        // Change header to "Manage Books > Add Books"
        document.getElementById('employees-header').innerHTML = '<strong>Employees > Employee Details</strong>';

        // Hide the table card and show the form card
        document.getElementById('book-card').style.display = 'none';
        document.getElementById('add-employee-form-card').style.display = 'block';
    });

    document.getElementById('cancel-btn').addEventListener('click', function() {
        // Reset header back to "Manage Books"
        document.getElementById('employees-header').innerHTML = '<strong>Employees</strong>';

        // Show the table card and hide the form card
        document.getElementById('book-card').style.display = 'block';
        document.getElementById('add-employee-form-card').style.display = 'none';
    });

    document.addEventListener("DOMContentLoaded", function() {
        const rowsPerPage = 10;
        const tableBody = document.getElementById("book-table-body");
        const paginationContainer = document.getElementById("pagination");

        const rows = Array.from(tableBody.querySelectorAll("tr"));
        const pageCount = Math.ceil(rows.length / rowsPerPage);

        let currentPage = 1;

        function displayPage(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((row, index) => {
                row.style.display = index >= start && index < end ? "" : "none";
            });
        }

        function createButton(label, page = null, disabled = false) {
            const btn = document.createElement("button");
            btn.innerText = label;
            btn.className = "btn btn-sm mx-1 btn-outline-primary";
            if (disabled) btn.disabled = true;
            if (page === currentPage) btn.classList.add("active");

            if (page !== null) {
                btn.addEventListener("click", () => {
                    currentPage = page;
                    displayPage(currentPage);
                    setupPagination();
                });
            }

            return btn;
        }

        function setupPagination() {
            paginationContainer.innerHTML = "";

            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(pageCount, currentPage + 2);

            if (endPage - startPage < maxVisiblePages - 1) {
                if (startPage === 1) {
                    endPage = Math.min(pageCount, startPage + maxVisiblePages - 1);
                } else if (endPage === pageCount) {
                    startPage = Math.max(1, pageCount - maxVisiblePages + 1);
                }
            }

            // Prev
            paginationContainer.appendChild(
                createButton("« Prev", currentPage - 1, currentPage === 1)
            );

            // First page + dots
            if (startPage > 1) {
                paginationContainer.appendChild(createButton(1, 1));
                if (startPage > 2) {
                    const dots = document.createElement("span");
                    dots.innerText = "...";
                    dots.className = "mx-1";
                    paginationContainer.appendChild(dots);
                }
            }

            // Middle page numbers
            for (let i = startPage; i <= endPage; i++) {
                paginationContainer.appendChild(createButton(i, i));
            }

            // Dots + last page
            if (endPage < pageCount) {
                if (endPage < pageCount - 1) {
                    const dots = document.createElement("span");
                    dots.innerText = "...";
                    dots.className = "mx-1";
                    paginationContainer.appendChild(dots);
                }
                paginationContainer.appendChild(createButton(pageCount, pageCount));
            }

            // Next
            paginationContainer.appendChild(
                createButton("Next »", currentPage + 1, currentPage === pageCount)
            );
        }

        displayPage(currentPage);
        setupPagination();
    });
</script>