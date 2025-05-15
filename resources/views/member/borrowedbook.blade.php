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
                        <a href="{{ route('member.settings') }}"><i class="bi bi-person me-2"></i>Profile</a>
                        <a href="{{ route('member.settings') }}"><i class="bi bi-gear me-2"></i>Settings</a>

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
                        <h5 class="fw-bold mb-0">Borrowed Books</h5>
                    </div>
                    <div class="col-6 text-end">
                        <button type="button" class="btn btn-view" id="cancel-btn" style="font-size: 1.1rem;">Back to Dashboard</button>
                    </div>
                </div>
                <hr class="mb-4">

                <script>
                    document.getElementById('cancel-btn').addEventListener('click', function() {
                        window.location.href = "{{ url('/member/dashboard') }}";
                    });
                </script>


                <div id="search-and-add" class="d-flex justify-content-between mb-3">
                    <div class="input-group shadow-sm rounded w-50">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" placeholder="Search Books" aria-label="Search Books" style="height: 40px;">
                    </div>
                </div>

                <!-- Book Table -->
                <div class="overflow-x-auto">
                    <table class="custom-table">
                        <thead class="bg-gray-100 text-gray-700">
                            <tr>
                                <th class="px-5 py-2 border" style="width: 200px;">Lend ID</th>
                                <th class="px-5 py-2 border" style="width: 150px;">Date Borrowed</th>
                                <th class="px-5 py-2 border" style="width: 150px;">Due Date</th>
                                <th class="px-5 py-2 border" style="width: 150px;">Total Books</th>
                                <th class="px-5 py-2 border" style="width: 100px;">Status</th>
                                <th class="px-5 py-2 border" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td class="px-4 py-2 border">{{ $transaction->trans_ID }}</td>
                                <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($transaction->borrow_date)->format('F j, Y') }}</td>
                                <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($transaction->due_date)->format('F j, Y') }}</td>
                                <td class="px-4 py-2 border">{{ $transaction->trans_details_count }}</td>
                                <td class="px-4 py-2 border">
                                    @if($transaction->return_date)
                                    Returned
                                    @else
                                    Pending
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm view-details-btn"
                                        data-trans-id="{{ $transaction->trans_ID }}">
                                        View
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">No borrowed books found.</td>
                            </tr>
                            @endforelse
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle view details button click
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', function() {
                    const transId = this.getAttribute('data-transid');
                    window.location.href = `/member/transactions/${transId}`;
                });
            });

            // Search functionality
            document.querySelector('input[placeholder="Search Books"]').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('transactionDetailsModal'));
            const modalBody = document.getElementById('transaction-details-content');

            document.querySelectorAll('.view-details-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const transId = button.getAttribute('data-trans-id');
                    modalBody.innerHTML = '<p>Loading...</p>';
                    modal.show();

                    fetch(`/member/transaction/${transId}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.text(); // assuming the controller returns a view partial or HTML
                        })
                        .then(html => {
                            modalBody.innerHTML = html;
                        })
                        .catch(error => {
                            modalBody.innerHTML = '<p class="text-danger">Failed to load details.</p>';
                            console.error('Error fetching transaction details:', error);
                        });
                });
            });
        });
    </script>

    <!-- Transaction Details Modal -->
    <div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-labelledby="transactionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionDetailsModalLabel">Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Details will be loaded here -->
                    <div id="transaction-details-content">
                        <p>Loading...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>