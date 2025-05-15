<x-sidebar>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content">
        <!-- Header -->
        <h5 id="lend-books-header" class="ms-3 mt-2"><strong>Lend</strong></h5>

        <!-- Card with Table -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="book-card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-0 p-0">
                    <div class="input-group w-50">
                        <h5 class="fw-bold">Books Available</h5>
                    </div>
                </div>

                <hr class="mb-3 mt-0">
                <div class="d-flex justify-content-between mb-3">
                    <div class="input-group shadow-sm rounded w-50">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" placeholder="Search Books" aria-label="Search Books" style="height: 40px;">
                    </div>
                    <div>
                        <button id="lend-book-btn" class="btn me-0 btn-add" style="background-color: #246484; color: white;">Lend Books</button>
                        <!--<button class="btn btn-view" style="background-color:rgb(240, 240, 240); color:black;">Request</button>-->
                    </div>
                </div>

                <!-- Table -->
                <div id="book-table">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th scope="col">Book ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">Author</th>
                                <th scope="col">Total Copies</th>
                                <th scope="col">Lended Copies</th>
                                <th scope="col">Available</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="book-table-body">
                            @foreach($books as $book)
                            <tr>
                                <td>{{ $book->book_id }}</td>
                                <td>{{ $book->title }}</td>
                                <td>
                                    @foreach($book->authors as $index => $author)
                                    {{ $author->au_fname }} {{ $author->au_lname }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td>{{ $book->total_copies }}</td>
                                <td>{{ $book->lended_copies }}</td>
                                <td>{{ $book->available_copies }}</td>
                                <td>{{ $book->book_status }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3" id="pagination">
                </div>
            </div>
        </div>

        <form id="lend-books-form" method="post" action="/transactions/lend">
            <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px; display: none;" id="add-book-form-card">
                <div class="card-body px-4 py-4">
                    <!-- Member Information -->
                    <div class="row">
                        <h5 class="fw-bold">Member Information</h5>
                        <hr class="mb-4">
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label for="member-id" style="font-size: 1.1rem; display: block; margin-bottom: 5px;">
                                Member <span style="color: red;">*</span>
                            </label>
                            <select id="member-id" class="form-control select2" required
                                style="width: 700px; ">
                            </select>
                        </div>

                    </div>

                    <!-- Book Information -->
                    <div class="row">
                        <h5 class="fw-bold">Book Information</h5>
                        <hr class="mb-4">
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="book-id" style="font-size: 1.1rem;">Search a Book <span style="color: red;">*</span></label>
                            <select id="book-id" class="form-control" style="width: 700px;" required></select>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row mb-3">
                        <div class="col-md-12 text-end">
                            <button class="btn btn-view me-2" id="cancel-btn" style="font-size: 1.1rem;">Cancel</button>
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

        <!-- Add required CSS and JS libraries -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- ✅ Then Select2 -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Custom Script -->
        <script>
            $(document).ready(function() {
                // Initialize Select2 for member
                $('#member-id').select2({
                    placeholder: 'Search for a member',
                    ajax: {
                        url: '/api/search-members',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: data.map(member => ({
                                    id: member.id,
                                    text: `${member.id} - ${member.name}`
                                }))
                            };
                        }
                    }
                });

                // Check for unpaid fines when member changes
                $('#member-id').on('change', function() {
                    let memberId = $(this).val();
                    if (!memberId) return;

                    $.get('/transactions/check-fines/' + memberId)
                        .done(function(res) {
                            if (res.has_fines) {
                                alert('This member has unpaid fines. Please settle fines before borrowing.');
                                $('#lend-books-btn').prop('disabled', true);
                            } else {
                                $('#lend-books-btn').prop('disabled', false);
                            }
                        });
                });

                // Initialize Select2 for book
                $('#book-id').select2({
                    placeholder: 'Search for a book',
                    ajax: {
                        url: '/api/search-books',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        }
                    }
                });

                // Check book availability when book changes
                $('#book-id').on('change', function() {
                    let bookId = $(this).val();
                    if (!bookId) return;

                    $.get('/transactions/check-book-availability/' + bookId)
                        .done(function(res) {
                            if (!res.is_available) {
                                alert(res.message);
                                $('#lend-books-btn').prop('disabled', true);
                            } else {
                                $('#lend-books-btn').prop('disabled', false);
                            }
                        })
                        .fail(function() {
                            alert('Error checking book availability');
                        });
                });

                // Fetch Lending ID and set dates
                $.get('/transactions/next-id', function(data) {
                    $('#lending-id').val(data.next_id);
                });

                let today = new Date().toISOString().split('T')[0];
                $('#date-borrowed').val(today);
                let due = new Date();
                due.setDate(due.getDate() + 7);
                $('#due-date').val(due.toISOString().split('T')[0]);

                // Add book to table
                let selectedBooks = [];
                let selectedBooksData = [];

                $('#add-book-btn').on('click', function(e) {
                    e.preventDefault();

                    let bookId = $('#book-id').val();
                    if (!bookId || selectedBooks.includes(bookId)) return;

                    $.get(`/book/${bookId}`, function(book) {
                        selectedBooks.push(bookId);
                        selectedBooksData.push(book);

                        let authors = book.author.split(', ');
                        let authorDisplay = authors.length >= 2 ?
                            `${authors[0]} ${authors[2]}, ${authors[1]}` : book.author;

                        let row = `
                    <tr>
                        <td>${book.book_id}</td>
                        <td>${book.title}</td>
                        <td>${authorDisplay}</td>
                        <td>${book.isbn}</td>
                        <td>${book.floor}</td>
                        <td>${book.shelf_code}</td>
                    </tr>
                `;
                        $('#book-list-body').append(row);
                    });
                });

                // Submit lending transaction
                $('#lend-books-btn').on('click', function(e) {
                    e.preventDefault();

                    if (!$('#member-id').val()) {
                        alert('Please select a member first');
                        return;
                    }

                    if (selectedBooks.length === 0) {
                        alert('Please add at least one book');
                        return;
                    }

                    let payload = {
                        user_ID: $('#member-id').val(),
                        book_IDs: selectedBooks,
                        borrow_date: $('#date-borrowed').val(),
                        due_date: $('#due-date').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                    $.post('/transactions/lend', payload)
                        .done(function(res) {
                            if (res.success) {
                                generateReceipt(res.transaction, selectedBooksData, $('#member-id option:selected').text());
                            } else {
                                alert(res.message);
                            }
                        })
                        .fail(function(err) {
                            let errorMsg = err.responseJSON?.message || 'Something went wrong';
                            alert('Error: ' + errorMsg);
                        })
                        .always(function() {
                            $('#lend-books-btn').prop('disabled', false).text('Lend Books');
                        });
                });

                // Function to generate receipt
                function generateReceipt(transaction, books, memberInfo) {
                    // Create receipt HTML
                    let receiptHTML = `
                <div class="receipt-content">
                    <h4 class="text-center mb-3">LIBRARY BOOK LENDING RECEIPT</h4>
                    
                    <div class="receipt-meta mb-3">
                        <div><strong>Date:</strong> ${new Date(transaction.borrow_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                        <div><strong>Time:</strong> ${new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</div>
                        <div><strong>Transaction #:</strong> ${transaction.id}</div>
                    </div>
                    
                    <div class="member-info mb-3">
                        <strong>Member:</strong> ${memberInfo}
                    </div>
                    
                    <table class="table table-sm receipt-table">
                        <thead>
                            <tr>
                                <th>Book ID</th>
                                <th>Title</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

                    // Add books to receipt
                    books.forEach(book => {
                        receiptHTML += `
                    <tr>
                        <td>${book.book_id}</td>
                        <td>${book.title}</td>
                        <td>${new Date(transaction.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                    </tr>
                `;
                    });

                    receiptHTML += `
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><strong>Total Items:</strong></td>
                                <td><strong>${books.length}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <div class="receipt-notice text-center mt-3">
                        <small class="text-muted">* Books must be returned by due date to avoid fines of ₽50.00 per day</small>
                    </div>
                </div>
            `;

                    // Insert into modal and show
                    $('#receiptContent').html(receiptHTML);
                    const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
                    receiptModal.show();

                    // Set up print button
                    $('#printReceiptBtn').off('click').on('click', function() {
                        const printContent = `
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Lending Receipt</title>
                        <style>
                            body { 
                                font-family: 'Courier New', monospace;
                                font-size: 12px;
                                width: 80mm;
                                margin: 0 auto;
                                padding: 10px;
                            }
                            h4 { 
                                text-align: center; 
                                margin: 5px 0 10px 0;
                                font-size: 14px;
                                font-weight: bold;
                            }
                            .receipt-meta div {
                                margin-bottom: 3px;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin: 10px 0;
                            }
                            th {
                                text-align: left;
                                border-bottom: 1px dashed #000;
                                padding: 3px 0;
                            }
                            td {
                                padding: 3px 0;
                            }
                            tfoot td {
                                border-top: 1px dashed #000;
                                padding-top: 5px;
                            }
                            .receipt-notice {
                                margin-top: 15px;
                                font-size: 10px;
                                text-align: center;
                            }
                            @page {
                                size: auto;
                                margin: 0mm;
                            }
                        </style>
                    </head>
                    <body>
                        ${$('#receiptContent').html()}
                    </body>
                    </html>
                `;

                        const printWindow = window.open('', '_blank');
                        printWindow.document.write(printContent);
                        printWindow.document.close();

                        printWindow.onload = function() {
                            setTimeout(() => {
                                printWindow.print();
                                printWindow.close();
                            }, 200);
                        };
                    });

                    // Refresh page when modal is closed
                    $('#receiptModal').on('hidden.bs.modal', function() {
                        location.reload();
                    });
                }
            });
        </script>

        <!-- Receipt Modal (place this near the end of your body) -->
        <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Lending Receipt</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="receiptContent">
                        <!-- Receipt content will be dynamically inserted here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="printReceiptBtn" class="btn btn-primary">
                            <i class="bi bi-printer"></i> Print Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>

</x-sidebar>
@vite('resources/js/pagination.js')
@vite('resources/js/lend.js')