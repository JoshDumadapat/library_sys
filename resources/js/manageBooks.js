document.getElementById("add-book-btn").addEventListener("click", function () {
    // Change header to "Manage Books > Add Books"
    document.getElementById("manage-books-header").innerHTML =
        "<strong>Manage Books > Add Books</strong>";

    // Hide the table card and show the form card
    document.getElementById("book-card").style.display = "none";
    document.getElementById("add-book-form-card").style.display = "block";

    // Load the create form dynamically (using Ajax)
    fetch("/admin/managebooks/create")
        .then((response) => response.text())
        .then((data) => {
            document.getElementById("add-book-form-card").innerHTML = data; // Inject the form into the 'add-book-form-card' div
        })
        .catch((error) => console.log("Error loading create form:", error));
});

document
    .getElementById("book-cancel-btn")
    .addEventListener("click", function () {
        // Reset header back to "Manage Books"
        document.getElementById("manage-books-header").innerHTML =
            "<strong>Manage Books</strong>";

        // Show the table card and hide the form card
        document.getElementById("book-card").style.display = "block";
        document.getElementById("add-book-form-card").style.display = "none";
    });

// Initialize the bootstrap modal for editing book
const bookModal = new bootstrap.Modal(document.getElementById("bookModal"));

// Adding event listeners to all "Edit" buttons
document.querySelectorAll(".btn-edit").forEach((button) => {
    button.addEventListener("click", () => {
        // Populate the modal form with data attributes from the clicked button
        [
            "title",
            "author",
            "genre",
            "isbn",
            "total-copies",
            "floor",
            "shelf-code",
        ].forEach((field) => {
            // Ensure the button has the correct data-attributes
            document.getElementById(field).value = button.getAttribute(
                `data-${field}`
            );
        });

        // Show the modal to edit the book
        bookModal.show();
    });
});
