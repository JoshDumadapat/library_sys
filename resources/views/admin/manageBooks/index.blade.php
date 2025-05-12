@include('admin.managebooks.edit') <!-- Make sure the path is correct -->

<x-sidebar>

    <div class="content">
        <!-- Header outside the card -->

        <h5 id="manage-books-header" class="ms-3 mt-2"><strong>Manage Books</strong></h5>

        <!-- Card with Table and Inline CSS for Height -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px;" id="book-card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-0 p-0">
                    <div class="input-group w-50">
                        <h5 class="fw-bold">Books List </h5>
                    </div>
                </div>

                <hr class="mb-3 mt-0">
                <!-- Search Bar and Add Button Section (will be hidden when adding a book) -->
                <div id="search-and-add" class="d-flex justify-content-between mb-3">
                    <div class="input-group w-50">
                        <input type="text" class="form-control" placeholder="Search Books" aria-label="Search Books">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                        </div>
                    </div>
                    <!-- Add Book Button -->
                    <button id="add-book-btn" class="btn btn-add" style="background-color: #246484;">+ Add Book</button>
                </div>

                <!-- Table -->
                <div id="book-table">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th scope="col"></th> <!-- Blank header for checkbox column -->
                                <th scope="col">Book ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">Author</th>
                                <th scope="col">Genre</th>
                                <th scope="col">ISBN</th>
                                <th scope="col">Total Copies</th>
                                <th scope="col">Floor</th>
                                <th scope="col">Shelf Code</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="book-table-body">
                            @foreach($books as $book)
                            <tr>
                                <td><input type="checkbox" value="{{ $book->id }}"></td>
                                <td>{{ $book->book_id }}</td>
                                <td>{{ $book->title }}</td>

                                <!-- Display author names -->
                                <td>
                                    @foreach($book->authors as $index => $author)
                                    {{ $author->au_fname }} {{ $author->au_lname }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>


                                <!-- Display genre names -->
                                <td>
                                    @foreach($book->genres as $genre)
                                    {{ $genre->genre }} <!-- Assuming 'name' is the field in Genre model -->
                                    @endforeach
                                </td>

                                <td>{{ $book->isbn }}</td>
                                <td>{{ $book->total_copies }}</td>

                                <!-- Display floor code -->
                                <td>{{ $book->floor->floor_num }}</td> <!-- Assuming 'floor_code' is the field in Floor model -->

                                <!-- Display shelf code -->
                                <td>{{ $book->shelf->shelf_code }}</td> <!-- Assuming 'shelf_code' is the field in Shelf model -->

                                <td>
                                    <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#bookModal" data-book_id="{{ $book->book_id }}">
                                        <i class="bi bi-pencil-square me-1"></i>&nbsp;Edit
                                    </button>
                                    <button class="btn btn-delete" data-book_id="{{ $book->book_id }}">
                                        <i class="bi bi-trash me-1"></i>&nbsp;Delete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <!-- Pagination Controls -->
                <div class="d-flex justify-content-center mt-3" id="pagination">
                </div>

            </div>
        </div>



        <!-- Add Book Form Card -->

        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px; display: none;" id="add-book-form-card">
            <div class="card-body px-4 py-4">
            </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.btn-delete').click(function() {
                    const book_id = $(this).data('book_id'); // ✅ extract data attribute

                    if (confirm('Are you sure you want to delete this book?')) {
                        $.ajax({
                            url: `/admin/managebooks/${book_id}`,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                alert(response.message);
                                location.reload(); // refresh the list
                            },
                            error: function(xhr) {
                                alert('Error deleting book.');
                                console.error(xhr.responseText);
                            }
                        });

                    }
                });
            });
        </script>




</x-sidebar>

@vite('resources/js/pagination.js')
@vite('resources/js/manageBooks.js')