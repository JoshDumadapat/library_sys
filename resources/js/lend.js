// JavaScript to toggle between table and form
document.getElementById("lend-book-btn").addEventListener("click", function () {
    // Change header to "Manage Books > Add Books"
    document.getElementById("lend-books-header").innerHTML =
        "<strong>Lend > Lend Books</strong>";

    // Hide the table card and show the form card
    document.getElementById("book-card").style.display = "none";
    document.getElementById("add-book-form-card").style.display = "block";
});

document.getElementById("cancel-btn").addEventListener("click", function () {
    // Reset header back to "Manage Books"
    document.getElementById("lend-books-header").innerHTML =
        "<strong>Lend</strong>";

    // Show the table card and hide the form card
    document.getElementById("book-card").style.display = "block";
    document.getElementById("add-book-form-card").style.display = "none";
});
