<x-sidebar>
    <div class="content">
        <h5 id="return-books-header" class="ms-3 mt-2"><strong>Report</strong></h5>

        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="book-card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-0 p-0">
                    <div class="input-group w-50">
                        <h5 class="fw-bold">Lending Report</h5>
                    </div>
                </div>

                <hr class="mb-3 mt-0">

                <form method="GET" class="row g-2 align-items-center mb-4" id="filter-form">
                    <div class="col-md-4">
                        <div class="input-group shadow-sm rounded">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control border-start-0" name="search" id="report-search"
                                placeholder="Search by ID or member name..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-auto d-flex align-items-center">
                        <label class="me-2 mb-0 fw-semibold text-muted">From</label>
                        <input type="date" class="form-control shadow-sm" name="from_date" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-auto d-flex align-items-center">
                        <label class="mx-2 mb-0 fw-semibold text-muted">To</label>
                        <input type="date" class="form-control shadow-sm" name="to_date" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-auto d-flex gap-2">
                        <button class="btn btn-outline-primary shadow-sm" type="submit">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <a href="{{ route('reports.lending') }}" class="btn btn-outline-secondary shadow-sm">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        <button type="button" id="generate-print-btn" class="btn btn-add shadow-sm"
                            style="background-color: #246484; font-size:1.05rem;">
                            <i class="bi bi-printer"></i> Print Report
                        </button>
                    </div>
                </form>

                <div id="book-table">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Member</th>
                                <th>Date Borrowed</th>
                                <th>Due Date</th>
                                <th>Date Returned</th>
                                <th>Total Books</th>
                                <th>Fines</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                            <tr>
                                <td>{{ $report->trans_ID }}</td>
                                <td>{{ $report->user->first_name }} {{ $report->user->last_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->borrow_date)->format('F j, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->due_date)->format('F j, Y') }}</td>
                                <td>
                                    {{ $report->return_date ? \Carbon\Carbon::parse($report->return_date)->format('F j, Y') : 'Not returned' }}
                                </td>
                                <td>{{ $report->total_books }}</td>
                                <td>₱{{ number_format($report->total_fines, 2) }}</td>
                                <td>
                                    @php
                                    $badgeClass = match($report->status) {
                                    'borrowed' => 'bg-primary',
                                    'overdue' => 'bg-danger',
                                    'returned' => 'bg-success',
                                    default => 'bg-secondary',
                                    };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} text-white text-capitalize">
                                        {{ $report->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No data found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $reports->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-sidebar>

@vite('resources/js/pagination.js')

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Print button logic
        document.getElementById('generate-print-btn').addEventListener('click', function() {
            const url = new URL("{{ route('reports.lending.print') }}");
            const searchParams = new URLSearchParams(window.location.search);

            searchParams.forEach((value, key) => {
                url.searchParams.set(key, value);
            });

            // Open in new tab - it will auto-print
            window.open(url.toString(), '_blank');
        });
    });
</script>