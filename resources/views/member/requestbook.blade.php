<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/sidebar.css'])
    @vite(['resources/css/memPag.css'])
    @vite(['resources/js/memPag.js'])
    <link rel="icon" type="image/png" href="{{ asset('storage/images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LunaBooks | Dashboard</title>
</head>

<body>
    <div class="container-fluid" style="height: 100%;">
        <!-- Top Row -->
        <div class="row mt-3 mx-2">
            <div class="col d-flex align-items-center">
                <!-- Profile Image -->
                <img src="{{ asset('storage/images/favicon.png') }}" alt="Logo" style="width: 70px; height: 60px; margin-right: 15px;">

                <!-- Name and Role -->
                <div>
                    <h4 class="fw-bold p-0 m-0" style="font-size: 1.4rem;">{{ Auth::user()->first_name.' '.Auth::user()->last_name ?? 'Guest' }}</h4>
                    <p class="p-0 m-0" style="font-size: 1.2rem;">{{ ucfirst(Auth::user()->role ?? 'N/A') }}</p>
                </div>
            </div>

            <div class="col d-flex justify-content-end align-items-center">
                <label class="switch shadow-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Switch to Dark Mode">
                    <input type="checkbox" id="darkModeToggle">
                    <span class="slider round">
                        <i class="bi bi-sun-fill icon-toggle"></i>
                    </span>
                </label>

                <div class="dropdown position-relative">
                    <img src="{{ asset('storage/images/hero.jpg') }}" alt="Profile" class="profile-img dropbtn">
                    <div class="dropdown-content">
                        <a href="#"><i class="bi bi-person me-2"></i>Profile</a>
                        <a href="#"><i class="bi bi-gear me-2"></i>Settings</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item" style="color: rgb(54, 54, 54); border: none; background: none; width: 100%; text-align: left; padding: 12px 16px;">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <form id="lend-books-form" method="post" action="/transactions/lend">
            <div class="card mt-5 me-3 ms-3 p-0" style="height: 620px; overflow-y: auto; border-radius:12px;" id="add-book-form-card">
                <div class="card-body px-4 py-4">
                    <!-- Member Information -->
                    <div class="row">
                        <h5 class="fw-bold">Request Book</h5>
                        <hr class="mb-4">
                    </div>

                    <!-- Book Information -->
                    <div class="row">
                        <h6 class="fw-bold">Book Information</h6>
                        <hr class="mb-4">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="book-id" style="font-size: 1.1rem;">Search a Book <span style="color: red;">*</span></label>
                            <select id="book-id" class="form-control" style="width: 100%;" required></select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="book-id-input" style="font-size: 1.1rem;">Book ID <span style="color: red;">*</span></label>
                            <input type="text" id="book-id-input" class="form-control select2" placeholder="Enter book ID" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="book-name" style="font-size: 1.1rem;">Book Name <span style="color: red;">*</span></label>
                            <input type="text" id="book-name" class="form-control select2" placeholder="Enter book name" required>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row mb-3">
                        <div class="col-md-12 text-end">
                            <button type="button" class="btn btn-view me-2" id="cancel-btn" style="font-size: 1.1rem;">Cancel</button>

                            <script>
                                document.getElementById('cancel-btn').addEventListener('click', function() {
                                    window.location.href = "{{ url('/member/dashboard') }}";
                                });
                            </script>

                            <button class="btn btn-addbook" id="add-book-btn" style="font-size: 1.1rem;">Add Book</button>
                        </div>
                    </div>

                    <hr>

                    <!-- Book Table -->
                    <div class="row mb-2" style="max-height: 170px; overflow-y: auto;">
                        <div class="col-md-12">
                            <table class="table custom-table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Book ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>ISBN</th>
                                        <th>Floor</th>
                                        <th>Shelf Code</th>
                                    </tr>
                                </thead>
                                <tbody id="book-list-body"></tbody>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <!-- Lending Details -->
                    <div class="row mb-4">
                        <div class="col-md-2 mb-3">
                            <label for="lending-id" style="font-size: 1.1rem;">Lending ID <span style="color: red;">*</span></label>
                            <input type="text" id="lending-id" class="form-control" placeholder="Enter lending ID" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="date-borrowed" style="font-size: 1.1rem;">Date Borrowed <span style="color: red;">*</span></label>
                            <input type="date" id="date-borrowed" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="due-date" style="font-size: 1.1rem;">Due Date <span style="color: red;">*</span></label>
                            <input type="date" id="due-date" class="form-control" required>
                        </div>
                        <div class="col-md-2 mt-4 ">
                            <button class="btn btn-addbook" id="lend-books-btn" style="font-size: 1.1rem; margin-left:47%">Lend&nbsp;Books</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>