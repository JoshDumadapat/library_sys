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
                    <div class="input-group w-50">
                        <input type="text" class="form-control" placeholder="Search Books" aria-label="Search Books">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <div>
                        <button id="lend-book-btn" class="btn me-2 btn-add" style="background-color: #246484; color: white;">Lend Books</button>
                        <button class="btn btn-view" style="background-color:rgb(240, 240, 240); color:black;">Request</button>
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
                <div class="col-md-4 mb-3">
                    <label for="member-id" style="font-size: 1.1rem;">Member <span style="color: red;">*</span></label>
                    <select id="member-id" class="form-control select2" required></select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="member-name" style="font-size: 1.1rem;">Name <span style="color: red;">*</span></label>
                    <input type="text" id="member-name" class="form-control select2" placeholder="Enter name" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="contact-number" style="font-size: 1.1rem;">Contact Number <span style="color: red;">*</span></label>
                    <input type="text" id="contact-number" class="form-control" placeholder="Enter contact number" required>
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
        <select id="book-id" class="form-control" style="width: 100%;" required></select>
    </div>
</div>

            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <label for="book-id" style="font-size: 1.1rem;">Book ID <span style="color: red;">*</span></label>
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
$(document).ready(function () {
    // Initialize Select2 for member
    $('#member-id').select2({
        placeholder: 'Search for a member',
        ajax: {
            url: '/api/search-members',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(member => ({
                        id: member.id,
                        text: `${member.id} - ${member.name}`
                    }))
                };
            }
        }
    });

    // Initialize Select2 for book
    $('#book-id').select2({
        placeholder: 'Search for a book',
        ajax: {
            url: '/api/search-books',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
    return {
        results: data // Already formatted properly by the backend
    };
}


        }
    });

    // Fetch Lending ID and set dates
    $.get('/transactions/next-id', function (data) {
        $('#lending-id').val(data.next_id);
    });
    let today = new Date().toISOString().split('T')[0];
    $('#date-borrowed').val(today);
    let due = new Date();
    due.setDate(due.getDate() + 7);
    $('#due-date').val(due.toISOString().split('T')[0]);

    // Add book to table
    let selectedBooks = [];

$('#add-book-btn').on('click', function (e) {
    e.preventDefault();

    let bookId = $('#book-id').val();
    if (!bookId || selectedBooks.includes(bookId)) return;

    $.get(`/book/${bookId}`, function (book) {
        selectedBooks.push(bookId);

        // Ensure related data like author, genre, floor, and shelf_code exist
        let author = book.author ? book.author.name : 'N/A'; // Adjust based on your data structure
        let floor = book.floor ? book.floor.floor_num : 'N/A'; // Adjust based on your data structure
        let shelfCode = book.shelf_code ? book.shelf_code : 'N/A'; // Adjust based on your data structure

    let row = `
        <tr>
            <td>${book.book_id}</td>
            <td>${book.title}</td>
            <td>${book.author}</td>
            <td>${book.isbn}</td>
            <td>${book.floor}</td>
            <td>${book.shelf_code}</td>
        </tr>
    `;
    $('#book-list-body').append(row);
});


});
    // Submit lending transaction
    $('#lend-books-btn').on('click', function (e) {
        e.preventDefault();
        let payload = {
            user_ID: $('#member-id').val(),
            book_IDs: selectedBooks,
            borrow_date: $('#date-borrowed').val(),
            due_date: $('#due-date').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.post('/transactions/lend', payload, function (res) {
            alert(res.message);
            location.reload(); // reset page
        }).fail(function (err) {
            alert('Error: ' + (err.responseJSON?.message || 'Something went wrong'));
        });
    });
   
});




</script>

</x-sidebar>
@vite('resources/js/pagination.js') 
@vite('resources/js/lend.js') 