<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    @vite(['resources/css/sidebar.css'])
    @vite(['resources/css/memPag.css'])
    @vite(['resources/js/memPag.js'])
    <link rel="icon" type="image/png" href="{{ asset('storage/images/favicon.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>LunaBooks | Dashboard</title>
</head>

<body>
    <div class="container-fluid" style="height: 100%;">
        <!-- Top Row -->
        <div class="row mt-3 mx-2">
            <div class="col d-flex align-items-center">
                <img src="{{ asset('storage/images/favicon.png') }}" alt="Logo" style="width: 70px; height: 60px; margin-right: 15px;" />
                <div class="col">
                    <h4 class="fw-bold p-0 m-0">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name ?? 'Guest' }}</h4>
                    <p class="p-0 m-0">{{ ucfirst(Auth::user()->role ?? 'N/A') }}</p>
                </div>
            </div>
            <div class="col d-flex justify-content-end align-items-center">
                <label class="switch shadow-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Switch to Dark Mode">
                    <input type="checkbox" id="darkModeToggle" />
                    <span class="slider round">
                        <i class="bi bi-sun-fill icon-toggle"></i>
                    </span>
                </label>

                <div class="dropdown position-relative ms-3">
                    <img src="{{ asset('storage/images/hero.jpg') }}" alt="Profile" class="profile-img dropbtn" />
                    <div class="dropdown-content">
                        <a href="{{ route('member.settings') }}"><i class="bi bi-person me-2"></i>Profile</a>
                        <a href="{{ route('member.settings') }}"><i class="bi bi-gear me-2"></i>Settings</a>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="dropdown-item"
                                style="color: rgb(54, 54, 54); border: none; background: none; width: 100%; text-align: left; padding: 12px 16px;">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
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
            <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius: 12px;">
                <div class="card-body px-4 py-4">

                    <div class="row">
                        <h5 class="fw-bold">Account Management</h5>
                        <hr class="mb-4" />
                    </div>

                    {{-- Editable Form Starts Here --}}
                    <form action="{{ route('member.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="first-name">First Name <span style="color:red">*</span></label>
                                <input type="text" name="first_name" id="first-name" class="form-control"
                                    value="{{ old('first_name', Auth::user()->first_name) }}" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last-name">Last Name <span style="color:red">*</span></label>
                                <input type="text" name="last_name" id="last-name" class="form-control"
                                    value="{{ old('last_name', Auth::user()->last_name) }}" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="contact-num">Contact No. <span style="color:red">*</span></label>
                                <input type="text" name="contact_num" id="contact-num" class="form-control"
                                    value="{{ old('contact_num', Auth::user()->contact_num) }}" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="street">Street <span style="color:red">*</span></label>
                                <input type="text" name="street" id="street" class="form-control"
                                    value="{{ old('street', Auth::user()->address->street ?? '') }}" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city">City <span style="color:red">*</span></label>
                                <input type="text" name="city" id="city" class="form-control"
                                    value="{{ old('city', Auth::user()->address->city ?? '') }}" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="region">Region <span style="color:red">*</span></label>
                                <input type="text" name="region" id="region" class="form-control"
                                    value="{{ old('region', Auth::user()->address->region ?? '') }}" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="email">Email <span style="color:red">*</span></label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email', Auth::user()->email) }}" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="role">Role <span style="color:red">*</span></label>
                                <input type="text" name="role" id="role" class="form-control"
                                    value="{{ old('role', Auth::user()->role) }}" readonly />
                            </div>
                            <div class="col-md-4 mb-3 mt-4">
                                <a href="{{ route('member.password.change') }}" class="btn btn-add w-100">Change Password</a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="profile-picture">Change Profile Picture</label>
                                <input type="file" name="profile_picture" id="profile-picture" class="form-control" />
                            </div>
                        </div>

                        <div class="row mb-4 justify-content-center">
                            <div class="col-md-12 d-flex justify-content-center">
                                <a href="{{ route('member.dashboard') }}" class="btn btn-view" style="font-size: 1.1rem; width:200px;">Back to Dashboard</a>
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" class="btn btn-add" style="font-size: 1.1rem; width:200px;">Save</button>
                            </div>
                        </div>
                    </form>
                    {{-- Editable Form Ends Here --}}

                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>