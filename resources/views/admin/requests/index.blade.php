<x-sidebar>
    <div class="container-fluid">
        <!-- Top Row -->
        <div class="row mt-3 mx-2">
            <div class="col d-flex align-items-center">
                <div>
                    <h4 class="fw-bold p-0 m-0" style="font-size: 1.4rem;">Book Requests Management</h4>
                </div>
            </div>
        </div>

        <hr>

        <div class="card mt-3 me-3 ms-3 p-0" style="border-radius:12px;">
            <div class="card-body px-4 py-4">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="requestTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                            <i class="bi bi-hourglass-split me-2"></i>Pending ({{ $pendingRequests->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button">
                            <i class="bi bi-check-circle me-2"></i>Approved ({{ $approvedRequests->count() }})
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-4">
                    <!-- Pending Requests Tab -->
                    <div class="tab-pane fade show active" id="pending">
                        @if($pendingRequests->isEmpty())
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No pending requests at this time.
                        </div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member</th>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Request Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingRequests as $request)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-light rounded-circle">
                                                        <i class="bi bi-person-fill text-primary"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $request->user->first_name }} {{ $request->user->last_name }}</h6>
                                                    <small class="text-muted">{{ $request->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $request->book->title }}</td>
                                        <td>{{ $request->book->authors->pluck('au_lname')->join(', ') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y h:i A') }}</td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.requests.show', $request) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.requests.approve', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Approve Request">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.requests.reject', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Reject Request">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>

                    <!-- Approved Requests Tab -->
                    <div class="tab-pane fade" id="approved">
                        @if($approvedRequests->isEmpty())
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No approved requests at this time.
                        </div>
                        @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member</th>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Approved Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedRequests as $request)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <div class="avatar-title bg-light rounded-circle">
                                                        <i class="bi bi-person-fill text-primary"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $request->user->first_name }} {{ $request->user->last_name }}</h6>
                                                    <small class="text-muted">{{ $request->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $request->book->title }}</td>
                                        <td>{{ $request->book->authors->pluck('au_lname')->join(', ') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($request->processed_at)->format('M d, Y h:i A') }}</td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.requests.show', $request) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.requests.lend', $request) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="Lend Book">
                                                        <i class="bi bi-bookmark-check"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Confirm before actions
            const approveForms = document.querySelectorAll('form[action*="/approve"]');
            const rejectForms = document.querySelectorAll('form[action*="/reject"]');
            const lendForms = document.querySelectorAll('form[action*="/lend"]');

            function confirmAction(e, message) {
                if (!confirm(message)) {
                    e.preventDefault();
                }
            }

            approveForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    confirmAction(e, 'Are you sure you want to approve this request?');
                });
            });

            rejectForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    confirmAction(e, 'Are you sure you want to reject this request?');
                });
            });

            lendForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    confirmAction(e, 'Are you sure you want to lend this book?');
                });
            });

            // Tab persistence
            const requestTabs = document.getElementById('requestTabs');
            const tab = new bootstrap.Tab(requestTabs.querySelector('.nav-link.active'));

            requestTabs.addEventListener('click', function(e) {
                if (e.target.classList.contains('nav-link')) {
                    const tabTrigger = new bootstrap.Tab(e.target);
                    tabTrigger.show();
                }
            });
        });
    </script>
    @endsection
</x-sidebar>