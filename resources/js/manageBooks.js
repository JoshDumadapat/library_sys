    // JavaScript to toggle between table and form
    document.getElementById('add-book-btn').addEventListener('click', function() {
        // Change header to "Manage Books > Add Books"
        document.getElementById('manage-books-header').innerHTML = '<strong>Manage Books > Add Books</strong>';

        // Hide the table card and show the form card
        document.getElementById('book-card').style.display = 'none';
        document.getElementById('add-book-form-card').style.display = 'block';
    });

    document.getElementById('cancel-btn').addEventListener('click', function() {
        // Reset header back to "Manage Books"
        document.getElementById('manage-books-header').innerHTML = '<strong>Manage Books</strong>';

        // Show the table card and hide the form card
        document.getElementById('book-card').style.display = 'block';
        document.getElementById('add-book-form-card').style.display = 'none';
    });

    const bookModal = new bootstrap.Modal(document.getElementById('bookModal'));

document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', () => {
        ['title', 'author', 'genre', 'isbn', 'available-copies', 'floor', 'shelf-code'].forEach(field => {
            document.getElementById(field).value = button.getAttribute(`data-${field}`);
        });
        bookModal.show();
    });
});
