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
                            <!-- Example of a Table Row -->
                            <tr>
                                <td><input type="checkbox"></td>
                                <td>1</td>
                                <td>Book Title</td>
                                <td>Author Name</td>
                                <td>Fiction</td>
                                <td>978-3-16-148410-0</td>
                                <td>5</td>
                                <td>2nd Floor</td>
                                <td>A12</td>
                                <td>
                                    <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#bookModal">
                                        <i class="bi bi-pencil-square me-1"></i>&nbsp;Edit
                                    </button>
                                    <button class="btn btn-delete">
                                        <i class="bi bi-trash me-1"></i>&nbsp;Delete
                                    </button>
                                </td>

                            </tr>


                            <!-- Repeat rows for more books -->
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Controls -->
                <div class="d-flex justify-content-center mt-3" id="pagination">
                </div>

            </div>
        </div>

        <!-- Form for Adding a Book (Initially Hidden) -->
        <!-- Book Information Card -->
        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px; display: none;" id="add-book-form-card">
            <div class="card-body px-4 py-4">
                <h4 class="section-title mb-3" style="font-size: 1.5rem;">Book Information</h4>
                <hr class="mb-4">

                <!-- Row 1 -->
                <div class="row mb-4" style="margin-top:50px;">
                    <div class="col-md-4 mb-3">
                        <label for="title" style="font-size: 1.1rem;">Book Title <span style="color: red;">*</span></label>
                        <input type="text" id="title" class="form-control" placeholder="Enter book title" required style="font-size: 1.05rem;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="isbn" style="font-size: 1.1rem;">Author(s) <span style="color: red;">*</span></label>
                        <input type="text" id="author" class="form-control" placeholder="Enter author" required style="font-size: 1.05rem;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="genre" style="font-size: 1.1rem;">Book Genre <span style="color: red;">*</span></label>
                        <input type="text" id="genre" class="form-control" placeholder="Enter book genre" required style="font-size: 1.05rem;">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="row mb-4"> 
                    <div class="col-md-4 mb-3">
                        <label for="isbn" style="font-size: 1.1rem;">ISBN <span style="color: red;">*</span></label>
                        <input type="text" id="isbn" class="form-control" placeholder="Enter ISBN" required style="font-size: 1.05rem;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="volume" style="font-size: 1.1rem;">Book Volume <span style="color: red;">*</span></label>
                        <input type="text" id="volume" class="form-control" placeholder="Enter volume" required style="font-size: 1.05rem;">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="total-copies" style="font-size: 1.1rem;">Total Copies <span style="color: red;">*</span></label>
                        <input type="number" id="total-copies" class="form-control" placeholder="Enter total copies" required style="font-size: 1.05rem;">
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label for="published-date" style="font-size: 1.1rem;">Published Date <span style="color: red;">*</span></label>
                        <input type="date" id="published-date" class="form-control" required style="font-size: 1.05rem;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="floor" style="font-size: 1.1rem;">Floor <span style="color: red;">*</span></label>
                        <input type="text" id="floor" class="form-control" placeholder="e.g. 2nd Floor" required style="font-size: 1.05rem;">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="shelf-code" style="font-size: 1.1rem;">Shelf Code <span style="color: red;">*</span></label>
                        <input type="text" id="shelf-code" class="form-control" placeholder="e.g. A12" required style="font-size: 1.05rem;">
                    </div>
                </div>

                <!-- Row 4: Buttons -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button id="save-book-btn" class="btn btn-addbook me-2" style="font-size: 1.1rem;">Add Book</button>
                        <button id="cancel-btn" class="btn btn-view" style="font-size: 1.1rem;">Cancel</button>
                    </div>
                </div>
            </div>
        </div>


        <!--EDIT MODAL -->
        <!-- Modal -->
        <div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content" style="border-radius: 12px;">
                    <div class="modal-header">
                        <h4 class="modal-title" id="bookModalLabel">Edit Book Information</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Book form goes here -->
                        <div class="px-4 py-4">
                            <hr class="mb-4">

                            <div class="row mb-4" style="margin-top: 25px;">
                                <div class="col-md-4 mb-3">
                                    <label for="title">Book Title <span style="color: red;">*</span></label>
                                    <input type="text" id="title" class="form-control" placeholder="Enter book title" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="author">Author(s) <span style="color: red;">*</span></label>
                                    <input type="text" id="author" class="form-control" placeholder="Enter author name" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="total-copies">Total Copies <span style="color: red;">*</span></label>
                                    <input type="number" id="total-copies" class="form-control" placeholder="Enter total copies" required>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4 mb-3">
                                    <label for="genre">Book Genre <span style="color: red;">*</span></label>
                                    <input type="text" id="genre" class="form-control" placeholder="Enter book genre" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="isbn">ISBN <span style="color: red;">*</span></label>
                                    <input type="text" id="isbn" class="form-control" placeholder="Enter ISBN" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="volume">Book Volume <span style="color: red;">*</span></label>
                                    <input type="text" id="volume" class="form-control" placeholder="Enter volume" required>
                                </div>
                            </div>

                            <div class="row mb-4"> 
                                <div class="col-md-4 mb-3">
                                    <label for="published-date">Published Date <span style="color: red;">*</span></label>
                                    <input type="date" id="published-date" class="form-control" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="floor">Floor <span style="color: red;">*</span></label>
                                    <input type="text" id="floor" class="form-control" placeholder="e.g. 2nd Floor" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="shelf-code">Shelf Code <span style="color: red;">*</span></label>
                                    <input type="text" id="shelf-code" class="form-control" placeholder="e.g. A12" required>
                                </div>                              
                            </div> 
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="save-book-btn" class="btn btn-addbook">Update Book</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</x-sidebar>

@vite('resources/js/pagination.js')
@vite('resources/js/manageBooks.js') 