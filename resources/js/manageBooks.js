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
            document.getElementById("add-book-form-card").innerHTML = data;

            // Initialize Select2 AFTER the form loads
            $(".select2-authors").select2({
                tags: true,
                multiple: true,
                tokenSeparators: [","],
                placeholder: "Search or add authors",
                minimumInputLength: 1,
                ajax: {
                    url: "/authors/search",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page,
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: params.page * 10 < data.total_count,
                            },
                        };
                    },
                    cache: true,
                },
                createTag: function (params) {
                    if (/^\d+$/.test(params.term)) {
                        return null;
                    }

                    var term = params.term.trim();
                    var names = term.split(" ");

                    return {
                        id: "NEW:" + term,
                        text: term + " (new)",
                        newOption: true,
                    };
                },
                templateResult: function (data) {
                    var $result = $("<span></span>");

                    if (data.newOption) {
                        $result.text(data.text);
                        $result.append(
                            " <span class='badge badge-info'>New</span>"
                        );
                    } else {
                        $result.text(data.text);
                    }

                    return $result;
                },
            });

            // Initialize genre select2
            $(".select2-genres").select2({
                tags: true,
                multiple: true,
                tokenSeparators: [","],
                placeholder: "Search or add genres",
                minimumInputLength: 1,
                ajax: {
                    url: "/genres/search",
                    dataType: "json",
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page,
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: params.page * 10 < data.total_count,
                            },
                        };
                    },
                    cache: true,
                },
                createTag: function (params) {
                    // Don't create tag if it's a number
                    if (/^\d+$/.test(params.term)) {
                        return null;
                    }

                    return {
                        id: "NEW:" + params.term.trim(),
                        text: params.term.trim() + " (new)",
                        newOption: true,
                    };
                },
                templateResult: function (data) {
                    var $result = $("<span></span>");

                    if (data.newOption) {
                        $result.text(data.text);
                        $result.append(
                            " <span class='badge badge-info'>New</span>"
                        );
                    } else {
                        $result.text(data.text);
                    }

                    return $result;
                },
            });
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
