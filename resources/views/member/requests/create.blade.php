<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Add jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @vite(['resources/css/sidebar.css'])
    @vite(['resources/css/memPag.css'])
    <link rel="icon" type="image/png" href="{{ asset('storage/images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LunaBooks | Request a Book</title>
    <style>
        .select2-container--default .select2-selection--multiple {
            min-height: 38px;
            padding: 5px;
            border: 1px solid #ced4da;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 0 5px;
            margin-top: 4px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #6c757d;
            margin-right: 5px;
        }

        .card-img-placeholder {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 150px;
            color: #6c757d;
        }

        .selected-books-container {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 10px;
        }

        .selected-book-card {
            margin-bottom: 10px;
            border-left: 4px solid #0d6efd;
        }

        .remove-book-btn {
            cursor: pointer;
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="height: 100%;">
        <!-- Top Row -->
        <div class="row mt-3 mx-2">
            <div class="col d-flex align-items-center">
                <img src="{{ asset('storage/images/favicon.png') }}" alt="Logo" style="width: 70px; height: 60px; margin-right: 15px;">
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
                <!-- Form Header -->
                <div class="row align-items-center mb-4">
                    <div class="col-6">
                        <h5 class="fw-bold mb-0">Request Books</h5>
                    </div>
                    <div class="col-6 text-end">
                        <button type="button" class="btn btn-view" id="cancel-btn" style="font-size: 1.1rem;">
                            <i class="bi bi-arrow-left"></i> Back to Requests
                        </button>
                    </div>
                </div>
                <hr class="mb-4">

                <!-- Book Request Form -->
                <form method="POST" action="{{ route('member.requests.store') }}" id="requestForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-4">
                                <label for="book_ID" class="form-label fw-bold">Select Books</label>
                                <select class="form-select select2" id="book_ID" name="book_ID[]" multiple="multiple" required style="width: 100%;">
                                    @foreach($books as $book)
                                    <option
                                        value="{{ $book->book_id }}"
                                        data-author="{{ $book->authors->pluck('au_lname')->join(', ') }}"
                                        data-isbn="{{ $book->isbn }}"
                                        data-available="{{ $book->isAvailable() ? 'Available' : 'Unavailable' }}"
                                        data-copies="{{ $book->availableCopies() }}/{{ $book->total_copies }}"
                                        data-cover="{{ $book->cover_image ?? asset('storage/images/book-placeholder.png') }}">
                                        {{ $book->title }}
                                        @if($book->authors->isNotEmpty())
                                        ({{ $book->authors->pluck('au_lname')->join(', ') }})
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Type to search available books (select multiple)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Books Container -->
                    <div class="row mb-4" id="selectedBooksContainer" style="display: none;">
                        <div class="col-md-8">
                            <h6 class="fw-bold">Selected Books</h6>
                            <div class="selected-books-container" id="selectedBooksList">
                                <!-- Selected books will be added here dynamically -->
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-8">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" id="resetBtn">
                                    <i class="bi bi-x-circle"></i> Clear Selection
                                </button>
                                <button type="button" class="btn btn-add" id="submitBtn">
                                    <i class="bi bi-send-check"></i> Submit Request
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/memPag.js'])

    <script>
        $(document).ready(function() {
            // Initialize Select2 with multiple selection
            $('.select2').select2({
                placeholder: "Search for books",
                allowClear: true,
                multiple: true,
                templateResult: formatBookOption,
                templateSelection: formatBookSelection
            });

            // Array to store selected books data
            let selectedBooks = [];

            // Book selection change handler
            $('#book_ID').on('change', function() {
                const selectedOptions = $(this).find('option:selected');
                selectedBooks = [];

                // Build selected books array
                selectedOptions.each(function() {
                    const $option = $(this);
                    selectedBooks.push({
                        id: $option.val(),
                        title: $option.text().split(' (')[0],
                        author: $option.data('author'),
                        isbn: $option.data('isbn'),
                        available: $option.data('available') === 'Available',
                        copies: $option.data('copies'),
                        cover: $option.data('cover')
                    });
                });

                // Update selected books display
                updateSelectedBooksDisplay();

                // Show/hide container based on selection
                if (selectedBooks.length > 0) {
                    $('#selectedBooksContainer').show();
                } else {
                    $('#selectedBooksContainer').hide();
                }
            });

            // Update the selected books display
            function updateSelectedBooksDisplay() {
                const container = $('#selectedBooksList');
                container.empty();

                selectedBooks.forEach((book, index) => {
                    const availabilityClass = book.available ? 'text-success' : 'text-danger';
                    const availabilityBadge = book.available ?
                        '<span class="badge bg-success">Available</span>' :
                        '<span class="badge bg-danger">Unavailable</span>';

                    const card = $(`
                        <div class="card selected-book-card">
                            <div class="card-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-1">
                                        ${book.cover.includes('placeholder.png') ? 
                                            '<div class="card-img-placeholder small"><i class="bi bi-book"></i></div>' : 
                                            `<img src="${escapeHtml(book.cover)}" class="img-fluid rounded" style="max-height: 50px;">`}
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="mb-1">${escapeHtml(book.title)}</h6>
                                        <p class="mb-1 small text-muted">${escapeHtml(book.author)}</p>
                                        <p class="mb-0 small">${availabilityBadge} <span class="text-muted">(${escapeHtml(book.copies)})</span></p>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <i class="bi bi-x-circle remove-book-btn" data-id="${book.id}"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);

                    container.append(card);
                });

                // Add click handler for remove buttons
                $('.remove-book-btn').click(function() {
                    const bookId = $(this).data('id');
                    $('#book_ID option[value="' + bookId + '"]').prop('selected', false);
                    $('#book_ID').trigger('change');
                });
            }

            // Reset button handler
            $('#resetBtn').click(function() {
                $('#book_ID').val(null).trigger('change');
                $('#selectedBooksContainer').hide();
            });


            // Format Select2 options
            function formatBookOption(book) {
                if (!book.id) return book.text;

                const $option = $(book.element);
                const isAvailable = $option.data('available') === 'Available';
                const availabilityClass = isAvailable ? 'text-success' : 'text-danger';

                return $(
                    `<div>
                        <strong>${escapeHtml(book.text.split(' (')[0])}</strong>
                        <div class="text-muted small">${escapeHtml($option.data('author'))}</div>
                        <span class="${availabilityClass}">${escapeHtml($option.data('available'))} (${escapeHtml($option.data('copies'))})</span>
                    </div>`
                );
            }

            function formatBookSelection(book) {
                if (!book.id) return book.text;
                return $(book.element).text().split(' (')[0];
            }

            // Confirmation dialog before submission
            $('#submitBtn').click(function(e) {
                e.preventDefault();

                if (selectedBooks.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'No Books Selected',
                        text: 'Please select at least one book before submitting your request',
                        confirmButtonColor: '#3085d6',
                    });
                    return;
                }

                // Check if any selected books are unavailable
                const unavailableBooks = selectedBooks.filter(book => !book.available);
                if (unavailableBooks.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Some Books Unavailable',
                        html: `${unavailableBooks.length} of your selected books are currently unavailable. Please remove them before submitting.`,
                        confirmButtonColor: '#3085d6',
                    });
                    return;
                }

                // Build confirmation message
                let confirmationHtml = `
                    <div class="text-start">
                        <p>You are about to request ${selectedBooks.length} book(s):</p>
                        <div class="selected-books-container mb-3">
                `;

                selectedBooks.forEach(book => {
                    confirmationHtml += `
                        <div class="card mb-2">
                            <div class="card-body p-2">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        ${book.cover.includes('placeholder.png') ? 
                                            '<div class="card-img-placeholder small"><i class="bi bi-book"></i></div>' : 
                                            `<img src="${escapeHtml(book.cover)}" class="img-fluid rounded" style="max-height: 50px;">`}
                                    </div>
                                    <div class="col-md-10">
                                        <h6 class="mb-1">${escapeHtml(book.title)}</h6>
                                        <p class="mb-1 small text-muted">${escapeHtml(book.author)}</p>
                                        <p class="mb-0 small"><span class="badge bg-success">Available</span> <span class="text-muted">(${escapeHtml(book.copies)})</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });

                confirmationHtml += `
                        </div>
                        <p>Are you sure you want to proceed?</p>
                    </div>
                `;

                Swal.fire({
                    title: 'Confirm Book Request',
                    html: confirmationHtml,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit request',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    width: '700px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Processing Request',
                            html: 'Please wait while we submit your request...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit the form
                        $('#requestForm').submit();
                    }
                });
            });

            // Helper function to escape HTML
            function escapeHtml(unsafe) {
                if (!unsafe) return '';
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Back button handler
            document.getElementById('cancel-btn').addEventListener('click', function() {
                window.location.href = "{{ route('member.requests.index') }}";
            });
        });
    </script>
</body>

</html>