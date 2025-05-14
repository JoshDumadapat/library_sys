<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header">
                <h4 class="modal-title" id="bookModalLabel">Edit Book Information</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="edit-book-form">
                @csrf
                <div class="modal-body">
                    <div class="px-4 py-4">
                        <hr class="mb-4">

                        <div class="row mb-4" style="margin-top: 25px;">
                            <div class="col-md-4 mb-3">
                                <label for="title">Book Title <span style="color: red;">*</span></label>
                                <input type="text" id="title" name="title" class="form-control" placeholder="Enter book title" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="author">Author(s) <span style="color: red;">*</span></label>
                                <select multiple id="author" name="author_ids[]" class="form-control" style="height: 39px;" required>
                                    <!-- Options will be dynamically added -->
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="total-copies">Total Copies <span style="color: red;">*</span></label>
                                <input type="number" id="total-copies" name="total_copies" class="form-control" placeholder="Enter total copies" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="genre">Book Genre <span style="color: red;">*</span></label>
                                <select multiple id="genre" name="genre_ids[]" class="form-control" style="height: 39px;" required>
                                    <!-- Options will be dynamically added -->
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="isbn">ISBN <span style="color: red;">*</span></label>
                                <input type="text" id="isbn" name="isbn" class="form-control" placeholder="Enter ISBN" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="volume">Book Volume <span style="color: red;">*</span></label>
                                <input type="text" id="volume" name="volume" class="form-control" placeholder="Enter volume" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="published-date">Published Date <span style="color: red;">*</span></label>
                                <input type="date" id="published-date" name="published_date" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="floor">Floor <span style="color: red;">*</span></label>
                                <select id="floor" name="floor_id" class="form-control" required>
                                    <!-- Floor options will be dynamically added -->
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="shelf-code">Shelf Code <span style="color: red;">*</span></label>
                                <select id="shelf-code" name="shelf_id" class="form-control" required>
                                    <!-- Shelf options will be dynamically added -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="save-edit-btn" type="submit" class="btn btn-addbook">Update Book</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        let editingBookId = null;

        // Handle Edit Button Click
        $(document).on('click', '.btn-edit', function() {
            editingBookId = $(this).data('book_id');

            // Check if the book_id is valid before making the AJAX request
            if (!editingBookId) {
                console.error("Book ID is missing");
                return;
            }

            $.ajax({
                url: "/admin/managebooks/" + editingBookId,
                method: 'GET',
                success: function(response) {
                    const book = response.book;
                    const authors = response.authors;
                    const genres = response.genres;
                    const floors = response.floors;
                    const shelves = response.shelves;

                    // Log the fetched data for debugging
                    console.log(book, authors, genres, floors, shelves);

                    // Clear existing options
                    $('#author').empty();
                    $('#genre').empty();
                    $('#floor').empty();
                    $('#shelf-code').empty();

                    authors.forEach(author => {
                        $('#author').append(
                            $('<option>', {
                                value: author.author_id,
                                text: author.au_fname + ' ' + author.au_lname
                            })
                        );
                    });

                    // Set selected authors
                    $('#author').val(book.authors.map(a => a.author_id)).trigger('change');


                    // Populate genres
                    genres.forEach(genre => {
                        $('#genre').append(
                            $('<option>', {
                                value: genre.genre_id,
                                text: genre.genre
                            })
                        );
                    });

                    // Set selected genres
                    $('#genre').val(book.genres.map(g => g.genre_id)).trigger('change');


                    // Populate floors
                    if (floors && floors.length > 0) {
                        floors.forEach(floor => {
                            $('#floor').append(
                                $('<option>', {
                                    value: floor.floor_id,
                                    text: floor.floor_num
                                })
                            );
                        });
                    } else {
                        console.log("No floors available");
                    }

                    if (shelves && shelves.length > 0) {
                        shelves.forEach(shelf => {
                            $('#shelf-code').append(
                                $('<option>', {
                                    value: shelf.shelf_id,
                                    text: shelf.shelf_code
                                })
                            );
                        });
                    } else {
                        console.log("No shelves available");
                    }

                    // Set the selected shelf
                    if (book.shelf_id) {
                        $('#shelf-code').val(book.shelf_id).trigger('change');
                    }

                    // Set other fields
                    $('#title').val(book.title);
                    $('#isbn').val(book.isbn);
                    $('#volume').val(book.volume);
                    $('#total-copies').val(book.total_copies);
                    $('#published-date').val(book.published_date);

                    // Show modal
                    $('#bookModal').modal('show');
                },
                error: function() {
                    alert('Failed to load book details.');
                }
            });
        });

        // Handle Save Edit
        $('#edit-book-form').on('submit', function(e) {
            e.preventDefault();

            // Validate form data before sending the request
            if ($('#title').val() === "" || $('#isbn').val() === "") {
                alert("Title and ISBN are required fields.");
                return;
            }

            $.ajax({
                url: "/admin/managebooks/" + editingBookId,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    title: $('#title').val(),
                    isbn: $('#isbn').val(),
                    volume: $('#volume').val(),
                    total_copies: $('#total-copies').val(),
                    published_date: $('#published-date').val(),
                    floor_id: $('#floor').val(),
                    shelf_id: $('#shelf-code').val(),
                    author_ids: $('#author').val(), // array
                    genre_ids: $('#genre').val(), // array
                },
                success: function(response) {
                    alert(response.message);
                    $('#bookModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'An unexpected error occurred.';
                    alert("Error: " + error);
                }
            });
        });
    });
</script>