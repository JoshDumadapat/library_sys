<x-sidebar>
    <div class="content">
        <!-- Header -->
        <h5 id="employees-header" class="ms-3 mt-2"><strong>Employees</strong></h5>

        <!-- Card with Table -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="book-card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-0 p-0">
                    <div class="input-group w-50">
                        <h5 class="fw-bold">List of Employees</h5>
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
                        <tbody id="book-table-body">
                            @forelse ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->id }}</td>
                                    <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->contact_num }}</td>
                                    <td>
                                        {{ $employee->address->street ?? '' }},
                                        {{ $employee->address->city ?? '' }},
                                        {{ $employee->address->region ?? '' }}
                                    </td>
                                    <td>{{ ucfirst($employee->role) }}</td>
                                    <td>
                                        <button class="btn btn-view" data-bs-toggle="modal" data-bs-target="#editEmployeeModal{{ $employee->id }}" style="border-radius: 8px;">
                                            <i class="bi bi-eye me-1"></i>&nbsp;View
                                        </button>
                                        <button class="btn btn-delete" style="border-radius: 8px;">
                                            <i class="bi bi-person-x me-1"></i>&nbsp;Deactivate
                                        </button>
                                    </td>
                                </tr>

                                {{-- Include modal for this specific employee --}}
                                @include('admin.employees.edit', ['employee' => $employee])
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No employees found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3" id="pagination"></div>
            </div>
        </div>

<!-- Add Employee Form -->
<div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px; display: none;" id="add-employee-form-card">
    <div class="card-body px-4 py-4">

        <div class="row">
            <h5 class="fw-bold">Employee Information</h5>
            <hr class="mb-4">
        </div>

        <!-- Form Starts Here -->
        <form action="{{ route('employees.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
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
                    <small class="text-start d-block">Email <span class="text-danger">*</span></small>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <small class="text-start d-block">Password <span class="text-danger">*</span></small>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-4 mb-5">
                    <small class="text-start d-block">Confirm Password <span class="text-danger">*</span></small>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <!-- Dropdown for Role Selection -->
            <div class="row mb-3">
                <div class="col-md-4 mb-3">
                    <small class="text-start d-block">Role <span class="text-danger">*</span></small>
                    <select name="role" class="form-control" required>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="librarian" {{ old('role') == 'librarian' ? 'selected' : '' }}>Librarian</option>
                    </select>
                </div>
            </div>

            <hr>

            <div class="row mb-4">
                <div class="col-12 col-md-12 mt-4 text-center">
                    <button class="btn btn-view me-2" id="cancel-btn" style="font-size: 1.1rem;">Cancel</button>
                    <button type="submit" class="btn btn-addbook" id="add-employee-btn" style="font-size: 1.1rem;">+ Add Employee</button>
                </div>
            </div>
        </form>
    </div>
</div>


<<script>
    // Optional: Reset the form when redirected and success message exists
    document.addEventListener("DOMContentLoaded", function () {
        const successMessage = "{{ session('success') }}";
        if (successMessage) {
            const form = document.querySelector('form');
            if (form) {
                form.reset();

                // Optionally reset dropdowns manually (if not reset by .reset())
                const selects = form.querySelectorAll('select');
                selects.forEach(select => select.selectedIndex = 0);
            }

            // Optional: hide the add-employee form after successful submission
            const formCard = document.getElementById('add-employee-form-card');
            if (formCard) {
                formCard.style.display = 'none';
            }
        }
    });
</script>



        
    </div>
</x-sidebar>

@vite('resources/js/pagination.js')
@vite('resources/js/employees.js')
