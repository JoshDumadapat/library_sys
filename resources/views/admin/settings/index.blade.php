<x-sidebar>
    <div class="content">
        <!-- Header -->
        <h5 class="ms-3 mt-2"><strong>Settings</strong></h5>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;">
            <div class="card-body px-4 py-4">

                <!-- Account Management (Profile Section) -->
                <div class="row">
                    <h5 class="fw-bold">Account Management</h5>
                    <hr class="mb-4">
                </div>
                <form action="{{ route('admin.settings.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="first-name">First Name <span style="color:red">*</span></label>
                            <input type="text" name="first_name" id="first-name" class="form-control"
                                value="{{ old('first_name', $user->first_name) }}" required />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="last-name">Last Name <span style="color:red">*</span></label>
                            <input type="text" name="last_name" id="last-name" class="form-control"
                                value="{{ old('last_name', $user->last_name) }}" required />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="contact-num">Contact No. <span style="color:red">*</span></label>
                            <input type="text" name="contact_num" id="contact-num" class="form-control"
                                value="{{ old('contact_num', $user->contact_num) }}" required />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="street">Street <span style="color:red">*</span></label>
                            <input type="text" name="street" id="street" class="form-control"
                                value="{{ old('street', $user->address->street ?? '') }}" required />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="city">City <span style="color:red">*</span></label>
                            <input type="text" name="city" id="city" class="form-control"
                                value="{{ old('city', $user->address->city ?? '') }}" required />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="region">Region <span style="color:red">*</span></label>
                            <input type="text" name="region" id="region" class="form-control"
                                value="{{ old('region', $user->address->region ?? '') }}" required />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email">Email <span style="color:red">*</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="role">Role <span style="color:red">*</span></label>
                            <input type="text" name="role" id="role" class="form-control"
                                value="{{ old('role', $user->role) }}" readonly />
                        </div>
                        <div class="col-md-4 mb-3 mt-4">
                            <a href="{{ route('member.password.change') }}" class="btn btn-add w-100">Change Password</a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="profile-picture">Change Profile Picture</label>
                            <input type="file" name="profile_picture" id="profile-picture" class="form-control" />
                            @if($user->profile_picture_path)
                            <small class="text-muted">Current: {{ basename($user->profile_picture_path) }}</small>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <h5 class="fw-bold">Fine Policies for Overdue Books</h5>
                        <hr class="mb-4">
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="daily-fine">Daily Fine Per Day <span style="color:red">*</span></label>
                            <input type="number" step="0.01" id="daily-fine" name="daily_fine" class="form-control"
                                value="{{ old('daily_fine', $fineTypes['overdue']->default_amount ?? '') }}" required>
                            <small class="text-muted">(Charged per day)</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lost-book-fine">Missing Book Fine <span style="color:red">*</span></label>
                            <input type="number" step="0.01" id="lost-book-fine" name="lost_book_fine" class="form-control"
                                value="{{ old('lost_book_fine', $fineTypes['lost']->default_amount ?? '') }}" required>
                            <small class="text-muted">(One-time charge)</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="damaged-book-fine">Damaged Book Fine <span style="color:red">*</span></label>
                            <input type="number" step="0.01" id="damaged-book-fine" name="damaged_book_fine" class="form-control"
                                value="{{ old('damaged_book_fine', $fineTypes['damaged']->default_amount ?? '') }}" required>
                            <small class="text-muted">(One-time charge)</small>
                        </div>
                    </div>

                    <!-- Save/Cancel Buttons -->
                    <div class="row mb-4 justify-content-center">
                        <div class="col-md-12 d-flex justify-content-center">
                            <button type="reset" class="btn btn-view" style="font-size: 1.1rem; width:200px;">Cancel</button>
                            &nbsp;&nbsp;&nbsp;
                            <button type="submit" class="btn btn-add" style="font-size: 1.1rem; width:200px;">Save</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-sidebar>