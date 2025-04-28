    // JavaScript to toggle between table and form
    document.getElementById('lending-detail').addEventListener('click', function() {
        // Change header to "Manage Books > Add Books"
        document.getElementById('return-books-header').innerHTML = '<strong>Return > Return Details</strong>';

        // Hide the table card and show the form card
        document.getElementById('book-card').style.display = 'none';
        document.getElementById('add-book-form-card').style.display = 'block';
    });

    document.getElementById('cancel-btn').addEventListener('click', function() {
        // Reset header back to "Manage Books"
        document.getElementById('return-books-header').innerHTML = '<strong>Return</strong>';

        // Show the table card and hide the form card
        document.getElementById('book-card').style.display = 'block';
        document.getElementById('add-book-form-card').style.display = 'none';
    });