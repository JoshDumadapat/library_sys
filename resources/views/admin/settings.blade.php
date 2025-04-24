<x-sidebar>
    <div class="content">
        <!-- Header -->
        <h5 class="ms-3 mt-2"><strong>Lend</strong></h5>

        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;">
            <div class="card-body px-4 py-4">

                <!-- Account Management (Profile Section) -->
                <div class="row">
                    <h5 class="fw-bold">Account Management</h5>
                    <hr class="mb-4">
                </div>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label for="first-name">First Name <span style="color:red">*</span></label>
                        <input type="text" id="first-name" class="form-control" placeholder="Enter first name" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="last-name">Last Name <span style="color:red">*</span></label>
                        <input type="text" id="last-name" class="form-control" placeholder="Enter last name" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="contact-no">Contact No. <span style="color:red">*</span></label>
                        <input type="text" id="contact-no" class="form-control" placeholder="Enter contact number" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="street">Street <span style="color:red">*</span></label>
                        <input type="text" id="street" class="form-control" placeholder="Enter street" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="city">City <span style="color:red">*</span></label>
                        <input type="text" id="city" class="form-control" placeholder="Enter city" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="region">Region <span style="color:red">*</span></label>
                        <input type="text" id="region" class="form-control" placeholder="Enter region" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="email">Email <span style="color:red">*</span></label>
                        <input type="email" id="email" class="form-control" placeholder="Enter email" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="role">Role <span style="color:red">*</span></label>
                        <input type="text" id="role" class="form-control" placeholder="Enter role" required>
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-add w-100">Change Password</button>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="profile-picture">Change Profile Picture <span style="color:red">*</span></label>
                        <input type="file" id="profile-picture" class="form-control" required>
                    </div>
                </div>

                <!-- Lending Limits -->
                <div class="row">
                    <h5 class="fw-bold">Lending Limits</h5>
                    <hr class="mb-4">
                </div>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label for="max-books">Max Books Per User <span style="color:red">*</span></label>
                        <input type="number" id="max-books" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="max-duration">Max Lending Duration (Days) <span style="color:red">*</span></label>
                        <input type="number" id="max-duration" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="max-reservations">Max Reservation Per User <span style="color:red">*</span></label>
                        <input type="number" id="max-reservations" class="form-control" required>
                    </div>
                </div>

                <!-- Fine Policies -->
                <div class="row">
                    <h5 class="fw-bold">Fine Policies for Overdue Books</h5>
                    <hr class="mb-4">
                </div>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label for="daily-fine">Daily Fine Per Day <span style="color:red">*</span></label>
                        <input type="number" step="0.01" id="daily-fine" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="missing-book-fine">Missing Book Fine <span style="color:red">*</span></label>
                        <input type="number" step="0.01" id="missing-book-fine" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="damaged-book-fine">Damaged Book Fine <span style="color:red">*</span></label>
                        <input type="number" step="0.01" id="damaged-book-fine" class="form-control" required>
                    </div>
                </div>

                <!-- Save/Cancel Buttons -->
                <div class="row mb-4 justify-content-center">
                    <div class="col-md-12 d-flex justify-content-center">
                        <button class="btn btn-view" style="font-size: 1.1rem; width:200px;">Cancel</button>
                        &nbsp;&nbsp;&nbsp;
                        <button class="btn btn-add" style="font-size: 1.1rem; width:200px;">Save</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-sidebar>