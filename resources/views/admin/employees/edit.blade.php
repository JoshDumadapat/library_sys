<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal{{ $employee->id }}" tabindex="-1" aria-labelledby="editEmployeeModalLabel{{ $employee->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h4 class="modal-title" id="editEmployeeModalLabel{{ $employee->id }}">Edit Employee Information</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <!-- Name and Contact -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $employee->last_name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact No. <span class="text-danger">*</span></label>
                            <input type="text" name="contact_num" class="form-control" value="{{ old('contact_num', $employee->contact_num) }}" required minlength="11" maxlength="11">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Street <span class="text-danger">*</span></label>
                            <input type="text" name="street" class="form-control" value="{{ old('street', $employee->address->street) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $employee->address->city) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Region <span class="text-danger">*</span></label>
                            <input type="text" name="region" class="form-control" value="{{ old('region', $employee->address->region) }}" required>
                        </div>
                    </div>

                    <!-- Role and Email -->
                    <div class="row mb-3">
                            <div class="col-md-4">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-control" required>
                                        <option value="admin" {{ old('role', $employee->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="librarian" {{ old('role', $employee->role) == 'librarian' ? 'selected' : '' }}>Librarian</option>
                                    </select>
                                </div>

                        <div class="col-md-4">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $employee->email) }}" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>
