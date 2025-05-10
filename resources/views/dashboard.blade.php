<x-sidebar>

    <div class="container-fluid" style="height: 100%;">

        <div class="row mt-2">
            <div class="col">
                <div class="card text-bg-dark" style="border-radius: 15px; height: 280px; margin-bottom: 100px;">
                    <img class="card-img" src="{{ asset('storage/images/dashboard/dashboard.jpg') }}" alt="LunaBooks Homepage" style="border-radius: 15px; height: 280px;">
                    <div class="card-img-overlay d-flex align-items-center justify-content-start">
                        <img src="{{ asset('storage/images/dashboard/book1.png') }}" alt="Book" class="book-dashboard me-5">
                        <div class="text-container ms-3">
                            <h2 class="card-title fw-bold mb-3">Welcome back to LunaBooks!</h2>
                            <p class="card-text mb-4">Start your transactions now with LunaBooks.</p>
                            <a href="/register" class="btn-dashboard" style="text-decoration: none; padding-left: 100px; padding-right: 100px;">Add Books</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-4">
                <div class="card text-center border border-light" style="border-radius: 10px;">
                    <div class="card-body d-flex align-items-center">
                        <img src="{{ asset('storage/images/avail.png') }}" alt="Borrowed Books Icon" style="width: 60px; height: 60px; margin-right: 10px;">
                        <div>
                            <h5 class="card-title">Borrowed Books</h5>
                            <span class="fw-bold" style="font-size: 2rem; color: #295183;">050</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card text-center border border-light" style="border-radius: 10px;">
                    <div class="card-body d-flex align-items-center">
                        <img src="{{ asset('storage/images/reqs.png') }}" alt="Book Requests Icon" style="width: 60px; height: 60px; margin-right: 10px;">
                        <div>
                            <h5 class="card-title">Book Requests</h5>
                            <span class="fw-bold" style="font-size: 2rem; color: #DBA910;">008</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card text-center border border-light" style="border-radius: 10px;">
                    <div class="card-body d-flex align-items-center">
                        <img src="{{ asset('storage/images/od.png') }}" alt="Overdue Books Icon" style="width: 60px; height: 60px; margin-right: 10px;">
                        <div>
                            <h5 class="card-title">Overdue Books</h5>
                            <span class="fw-bold text-danger" style="font-size: 2rem;">002</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left side -->
            <div class="col-lg-12">
                <div class="card shadow" style="width: 100%; height: 535px; overflow: hidden;">
                    <div class="card-body d-flex flex-column p-3" style="height: 100%;">

                        <!-- Search bar and Requests button -->
                        <div class="d-flex justify-content-between mb-3">
                            <div class="flex-grow-1 me-2" style="max-width: 350px;">
                                <input type="text" id="book-search" placeholder="Search books..." class="border border-gray-300 px-4 py-2 rounded-lg w-100 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                            </div>
                            <button class="btn btn-dark">Requests</button>
                        </div>

                        <!-- AddTable -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 text-sm text-left">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="px-5 py-2 border" style="width: 100px;">Book ID</th>
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
                            </table>
                        </div>

                        <!-- Centered Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            <div class="pagination">
                                <button class="px-3 py-1 border rounded hover:bg-gray-100">&laquo;</button>
                                <button class="px-3 py-1 border bg-blue-500 text-white rounded">1</button>
                                <button class="px-3 py-1 border hover:bg-gray-100">2</button>
                                <button class="px-3 py-1 border hover:bg-gray-100">3</button>
                                <button class="px-3 py-1 border hover:bg-gray-100">4</button>
                                <button class="px-3 py-1 border hover:bg-gray-100">5</button>
                                <button class="px-3 py-1 border rounded hover:bg-gray-100">&raquo;</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side (optional additional content) -->
            <div class="col-lg-4">
                <!-- You can add any additional content here if needed -->
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>

</x-sidebar>