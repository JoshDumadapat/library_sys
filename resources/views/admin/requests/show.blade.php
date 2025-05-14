<x-sidebar>
    <div class="container-fluid">
        <!-- Top Row -->
        <div class="row mt-3 mx-2">
            <div class="col d-flex align-items-center">
                <div>
                    <h4 class="fw-bold p-0 m-0" style="font-size: 1.4rem;">Request Details</h4>
                </div>
            </div>
        </div>

        <hr>

        <div class="card mt-3 me-3 ms-3 p-0" style="border-radius:12px;">
            <div class="card-body px-4 py-4">
                <div class="row">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        @if($bookRequest->book->cover_image)
                        <img src="{{ asset('storage/' . $bookRequest->book->cover_image) }}" class="img-fluid rounded" style="max-height: 300px;" alt="Book Cover">
                        @else
                        <div class="card-img-placeholder" style="height: 300px;">
                            <i class="bi bi-book" style="font-size: 3rem;"></i>
                        </div>
                        @endif
                    </div>

                    <div class="col-md-8">
                        <h3 class="fw-bold mb-3">{{ $bookRequest->book->title }}</h3>
                        <p class="text-muted mb-4">{{ $bookRequest->book->authors->pluck('au_lname')->join(', ') }}</p>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fw-bold mb-3">Request Information</h5>
                                        <p>
                                            <strong><i class="bi bi-person me-2"></i>Member:</strong><br>
                                            {{ $bookRequest->user->first_name }} {{ $bookRequest->user->last_name }}<br>
                                            <small class="text-muted">{{ $bookRequest->user->email }}</small>
                                        </p>
                                        <p>
                                            <strong><i class="bi bi-calendar me-2"></i>Request Date:</strong><br>
                                            {{ \Carbon\Carbon::parse($bookRequest->created_at)->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="fw-bold mb-3">Status</h5>
                                        <p>
                                            <span class="badge 
                                                @if($bookRequest->status == 'approved') bg-success 
                                                @elseif($bookRequest->status == 'rejected') bg-danger 
                                                @else bg-warning @endif"
                                                style="font-size: 1rem;">
                                                {{ ucfirst($bookRequest->status) }}
                                            </span>
                                        </p>
                                        @if($bookRequest->processed_at)
                                        <p>
                                            <strong><i class="bi bi-clock-history me-2"></i>Processed Date:</strong><br>
                                            {{ \Carbon\Carbon::parse($bookRequest->processed_at)->format('M d, Y h:i A') }}
                                        </p>
                                        <p>
                                            <strong><i class="bi bi-person-check me-2"></i>Processed By:</strong><br>
                                            {{ $bookRequest->admin->name }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($bookRequest->status == 'pending')
                        <div class="card">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Process Request</h5>
                                <form action="{{ route('admin.requests.approve', $bookRequest) }}" method="POST" class="mb-4">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="admin_notes" class="form-label">Notes (Optional)</label>
                                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="2" placeholder="Add any notes for the member"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-1"></i> Approve Request
                                    </button>
                                </form>

                                <form action="{{ route('admin.requests.reject', $bookRequest) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="admin_notes" class="form-label">Reason for Rejection</label>
                                        <textarea class="form-control" id="admin_notes" name="admin_notes" rows="2" required placeholder="Please provide a reason for rejection"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-x-circle me-1"></i> Reject Request
                                    </button>
                                </form>
                            </div>
                        </div>
                        @elseif($bookRequest->status == 'approved')
                        <div class="card">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Book Lending</h5>
                                <form action="{{ route('admin.requests.lend', $bookRequest) }}" method="POST">
                                    @csrf
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-arrow-left me-1"></i> Back to Requests
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-bookmark-check me-1"></i> Lend Book Now
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.requests.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm before actions
            const approveForm = document.querySelector('form[action*="/approve"]');
            const rejectForm = document.querySelector('form[action*="/reject"]');
            const lendForm = document.querySelector('form[action*="/lend"]');

            if (approveForm) {
                approveForm.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to approve this request?')) {
                        e.preventDefault();
                    }
                });
            }

            if (rejectForm) {
                rejectForm.addEventListener('submit', function(e) {
                    const notes = document.getElementById('admin_notes').value;
                    if (!notes.trim()) {
                        alert('Please provide a reason for rejection');
                        e.preventDefault();
                    } else if (!confirm('Are you sure you want to reject this request?')) {
                        e.preventDefault();
                    }
                });
            }

            if (lendForm) {
                lendForm.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to lend this book?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
    @endsection
</x-sidebar>