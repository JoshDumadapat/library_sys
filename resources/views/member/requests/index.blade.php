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
    <title>LunaBooks | My Book Requests</title>
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

        <div class="card mt-5 me-3 ms-3 p-0" style="height: 620px; overflow-y: auto; border-radius:12px;" id="add-book-form-card">
            <div class="card-body px-4 py-4">
                <!-- Member Information -->
                <div class="row align-items-center mb-4">
                    <div class="col-6">
                        <h5 class="fw-bold mb-0">My Book Requests</h5>
                    </div>
                    <div class="col-6 text-end">
                        <a href="{{ route('member.requests.create') }}" class="btn btn-add" style="font-size: 1.1rem;">
                            <i class="bi bi-plus-lg"></i> New Request
                        </a>
                        <button type="button" class="btn btn-add" id="cancel-btn" style="font-size: 1.1rem;">Back to Dashboard</button>
                    </div>
                </div>
                <hr class="mb-4">

                <script>
                    document.getElementById('cancel-btn').addEventListener('click', function() {
                        window.location.href = "{{ url('/member/dashboard') }}";
                    });
                </script>

                <div id="search-and-add" class="d-flex justify-content-between mb-3">
                    <div class="input-group w-50">
                        <input type="text" class="form-control" placeholder="Search requests" aria-label="Search Requests">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                </div>

                <!-- Requests Table -->
                <div class="overflow-x-auto">
                    <table class="custom-table">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-5 py-2 border">Book Title</th>
                                <th class="px-5 py-2 border">Status</th>
                                <th class="px-5 py-2 border">Request Date</th>
                                <th class="px-5 py-2 border">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                            <tr>
                                <td class="px-4 py-2 border">{{ $request->book->title }}</td>
                                <td class="px-4 py-2 border">
                                    <span class="badge 
                                        @if($request->status == 'approved') bg-success 
                                        @elseif($request->status == 'rejected') bg-danger 
                                        @else bg-warning @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 border">{{ $request->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-2 border text-center">
                                    @if($request->status == 'approved')
                                    <button class="btn btn-add">Pick Up</button>
                                    @else
                                    <button class="btn btn-secondary" disabled>No Action</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                @if($requests->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-book" style="font-size: 3rem; color: #6c757d;"></i>
                    <h5 class="mt-3">No book requests yet</h5>
                    <p class="text-muted">Click "New Request" to request a book</p>
                </div>
                @endif

                <!-- Pagination -->
                <div id="pagination" class="mt-3">
                    @if($requests->hasPages())
                    <button id="prevBtn" class="btn" {{ $requests->onFirstPage() ? 'disabled' : '' }}>
                        Previous
                    </button>
                    <div id="pageNumbers" class="d-flex">
                        @foreach(range(1, $requests->lastPage()) as $page)
                        <span class="page-number {{ $requests->currentPage() == $page ? 'active' : '' }}">
                            {{ $page }}
                        </span>
                        @endforeach
                    </div>
                    <button id="nextBtn" class="btn" {{ !$requests->hasMorePages() ? 'disabled' : '' }}>
                        Next
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced search with debounce
        let searchTimer;
        const searchInput = document.querySelector('input[placeholder="Search requests"]');
        const searchAndAdd = document.getElementById('search-and-add');

        // Create status filter dropdown
        const statusFilter = document.createElement('select');
        statusFilter.className = 'form-select w-auto';
        statusFilter.innerHTML = `
    <option value="">All Statuses</option>
    <option value="pending">Pending</option>
    <option value="approved">Approved</option>
    <option value="rejected">Rejected</option>
    <option value="completed">Completed</option>
`;
        statusFilter.value = new URLSearchParams(window.location.search).get('status') || '';

        statusFilter.addEventListener('change', function() {
            updateUrlParams({
                status: this.value,
                page: null
            });
        });

        searchAndAdd.appendChild(statusFilter);

        // Create sort dropdown
        const sortDropdown = document.createElement('select');
        sortDropdown.className = 'form-select w-auto ms-2';
        sortDropdown.innerHTML = `
    <option value="">Sort By</option>
    <option value="created_at_desc">Newest First</option>
    <option value="created_at_asc">Oldest First</option>
    <option value="title_asc">Title (A-Z)</option>
    <option value="title_desc">Title (Z-A)</option>
`;

        // Set initial sort value
        const urlParams = new URLSearchParams(window.location.search);
        const sortBy = urlParams.get('sort_by');
        const sortOrder = urlParams.get('sort_order');
        sortDropdown.value = sortBy && sortOrder ? `${sortBy}_${sortOrder}` : 'created_at_desc';

        sortDropdown.addEventListener('change', function() {
            const [sortBy, sortOrder] = this.value.split('_');
            updateUrlParams({
                sort_by: sortBy,
                sort_order: sortOrder,
                page: null
            });
        });

        searchAndAdd.appendChild(sortDropdown);

        // Update URL parameters
        function updateUrlParams(params) {
            const url = new URL(window.location);
            Object.entries(params).forEach(([key, value]) => {
                if (value === null || value === '') {
                    url.searchParams.delete(key);
                } else {
                    url.searchParams.set(key, value);
                }
            });
            window.location.href = url.toString();
        }

        // Debounced search
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                updateUrlParams({
                    search: this.value,
                    page: null
                });
            }, 500);
        });

        // Initialize with current search term
        searchInput.value = new URLSearchParams(window.location.search).get('search') || '';
    </script>
</body>

</html>