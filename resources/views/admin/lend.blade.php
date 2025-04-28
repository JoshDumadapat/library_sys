<x-sidebar>
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
                                <th scope="col">Genre</th>
                                <th scope="col">Total Copies</th>
                                <th scope="col">Available</th>
                                <th scope="col">Lended</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="book-table-body">                       
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3" id="pagination">
                </div>
            </div>
        </div>

        <div class="card mt-5 me-3 ms-3" style="height: 830px; overflow-y: auto; border-radius:12px; display: none;" id="add-book-form-card">
            <div class="card-body px-4 py-4">

                <!-- Member Information -->
                <div class="row">
                    <h5 class="fw-bold">Member Information</h5>
                    <hr class="mb-4">
                </div>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label for="member-id" style="font-size: 1.1rem;">Member ID <span style="color: red;">*</span></label>
                        <input type="text" id="member-id" class="form-control" placeholder="Enter member ID" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="member-name" style="font-size: 1.1rem;">Name <span style="color: red;">*</span></label>
                        <input type="text" id="member-name" class="form-control" placeholder="Enter name" required>
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
                        <label for="search-book" style="font-size: 1.1rem;">Search a Book:</label>
                        <input type="text" id="search-book" class="form-control" placeholder="Search by title or ID">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label for="book-id" style="font-size: 1.1rem;">Book ID <span style="color: red;">*</span></label>
                        <input type="text" id="book-id" class="form-control" placeholder="Enter book ID" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="book-name" style="font-size: 1.1rem;">Book Name <span style="color: red;">*</span></label>
                        <input type="text" id="book-name" class="form-control" placeholder="Enter book name" required>
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
                                    <th>Genre</th>
                                    <th>ISBN</th>
                                    <th>Total Copies</th>
                                    <th>Floor</th>
                                    <th>Shelf Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>BK001</td>
                                    <td>The Great Gatsby</td>
                                    <td>F. Scott Fitzgerald</td>
                                    <td>Classic</td>
                                    <td>9780743273565</td>
                                    <td>10</td>
                                    <td>2</td>
                                    <td>A1</td>
                                </tr>
                                <tr>
                                    <td>BK002</td>
                                    <td>To Kill a Mockingbird</td>
                                    <td>Harper Lee</td>
                                    <td>Fiction</td>
                                    <td>9780061120084</td>
                                    <td>7</td>
                                    <td>3</td>
                                    <td>B2</td>
                                </tr>
                                <tr>
                                    <td>BK003</td>
                                    <td>1984</td>
                                    <td>George Orwell</td>
                                    <td>Dystopian</td>
                                    <td>9780451524935</td>
                                    <td>5</td>
                                    <td>1</td>
                                    <td>C3</td>
                                </tr>

                            </tbody>
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


    </div>
</x-sidebar>
@vite('resources/js/pagination.js') 
@vite('resources/js/lend.js') 