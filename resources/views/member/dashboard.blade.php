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
            <div class="col">
                <h4 class="fw-bold p-0 m-0">{{ Auth::user()->first_name.' '.Auth::user()->last_name ?? 'Guest' }}</h4>
                <p class="p-0 m-0">{{ ucfirst(Auth::user()->role ?? 'N/A') }}</p>

            </div>
            <div class="col d-flex justify-content-end align-items-center">
                <label class="switch shadow-sm" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Switch to Dark Mode">
                    <input type="checkbox" id="darkModeToggle">
                    <span class="slider round">
                        <i class="bi bi-sun-fill icon-toggle"></i>
                    </span>
                </label>

                <div class="dropdown position-relative">
                    <img src="{{ asset('storage/images/hero.jpg') }}" alt="Profile"
                        class="profile-img dropbtn">
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

        <!-- Welcome Card - Moved container class here to align content -->
        <div class="welcome-card-container">
            <div class="row mt-2">
                <div class="col mb-0">
                    <div class="card text-bg-dark" style="border-radius: 15px; height: 280px; margin-bottom: 100px;">
                        <img class="card-img" src="{{ asset('storage/images/dashboard/dashboard.jpg') }}" alt="LunaBooks Homepage" style="border-radius: 15px; height: 280px;">
                        <div class="card-img-overlay d-flex align-items-center justify-content-start">
                            <img src="{{ asset('storage/images/dashboard/book1.png') }}" alt="Book" class="book-dashboard me-5">
                            <div class="text-container ms-3">
                                <h2 class="card-title fw-bold mb-3">Welcome back to LunaBooks!</h2>
                                <p class="card-text mb-4">Start your transactions now with LunaBooks.</p>
                                <a href="/register" class="btn-dashboard"
                                    style="text-decoration: none; 
          padding: 10px 100px; 
          background-color: black; 
          color: white; 
          border-radius: 50px; 
          display: inline-block;">
                                    Add Books
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Main Container - Added padding to align with welcome card -->
        <div class="main-content-container">
            <!-- Stats Cards -->
            <div class="row mb-3">
                <div class="col-lg-4 mb-2">
                    <a href="{{ url('/borrowed-books') }}" class="card text-center border border-light shadow" style="border-radius: 10px; text-decoration: none;">
                        <div class="card text-center border border-light shadow" style="border-radius: 10px;">
                            <div class="card-body d-flex align-items-center">
                                <img src="{{ asset('storage/images/avail.png') }}" alt="Borrowed Books Icon" style="width: 60px; height: 60px; margin-right: 10px;">
                                <div>
                                    <h5 class="card-title">Borrowed Books</h5>
                                    <span class="fw-bold" style="font-size: 2rem; color: #295183;">050</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 mb-2">
                    <!-- Wrap the card with an anchor tag to make it clickable -->
                    <a href="{{ url('/book-requests') }}" class="card text-center border border-light shadow" style="border-radius: 10px; text-decoration: none;">
                        <div class="card-body d-flex align-items-center">
                            <img src="{{ asset('storage/images/reqs.png') }}" alt="Book Requests Icon" style="width: 60px; height: 60px; margin-right: 10px;">
                            <div>
                                <h5 class="card-title">Book Requests</h5>
                                <span class="fw-bold" style="font-size: 2rem; color: #DBA910;">008</span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4 mb-2">
                    <a href="{{ url('/overdue-books') }}" class="card text-center border border-light shadow" style="border-radius: 10px; text-decoration: none;">
                        <div class="card text-center border border-light shadow" style="border-radius: 10px;">
                            <div class="card-body d-flex align-items-center">
                                <img src="{{ asset('storage/images/od.png') }}" alt="Overdue Books Icon" style="width: 60px; height: 60px; margin-right: 10px;">
                                <div>
                                    <h5 class="card-title">Overdue Books</h5>
                                    <span class="fw-bold text-danger" style="font-size: 2rem;">002</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Main Table and Search -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow" style="width: 100%; height: 535px; overflow: hidden;">
                        <div class="card-body d-flex flex-column p-3" style="height: 100%;">

                            <!-- Search bar and Requests button -->
                            <div id="search-and-add" class="d-flex justify-content-between mb-3">
                                <div class="input-group w-50">
                                    <input type="text" class="form-control" placeholder="Search Books" aria-label="Search Books">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                </div>
                                <!-- Add Book Button -->
                                <a href="{{ url('/request-book') }}" class="btn btn-add" style="background-color: #246484;">Request Book</a>
                            </div>


                            <!-- Book Table -->
                            <div class="overflow-x-auto">
                                <table class="custom-table" id="myTable">
                                    <thead class="bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="px-5 py-2 border" style="width: 200px;">Title</th>
                                            <th class="px-5 py-2 border" style="width: 150px;">Author</th>
                                            <th class="px-5 py-2 border" style="width: 150px;">Genre / Category</th>
                                            <th class="px-5 py-2 border" style="width: 100px;">ISBN</th>
                                            <th class="px-5 py-2 border" style="width: 100px;">Available</th>
                                            <th class="px-5 py-2 border" style="width: 100px;">Lended</th>
                                            <th class="px-5 py-2 border" style="width: 100px;">Status</th>
                                            <th class="px-5 py-2 border" style="width: 100px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($books as $book)
                                        <tr>
                                            <td class="px-4 py-2 border">{{ $book->title }}</td>
                                            <td class="px-4 py-2 border">
                                                {{ $book->authors->pluck('name')->join(', ') }}
                                            </td>
                                            <td class="px-4 py-2 border">
                                                {{ $book->genres->pluck('name')->join(', ') }}
                                            </td>
                                            <td class="px-4 py-2 border">{{ $book->isbn }}</td>
                                            <td class="px-4 py-2 border">{{ $book->available_copies }}</td>
                                            <td class="px-4 py-2 border">{{ $book->lended_copies }}</td>
                                            <td class="px-4 py-2 border">{{ $book->book_status }}</td>
                                            <td class="px-4 py-2 border text-center">
                                                <button class="btn btn-add">View</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            <!-- Updated Pagination -->
                            <div id="pagination" class="mt-3">
                                <button id="prevBtn" class="btn">Previous</button>
                                <div id="pageNumbers" class="d-flex">
                                    <span class="page-number active">1</span>
                                </div>
                                <button id="nextBtn" class="btn">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>