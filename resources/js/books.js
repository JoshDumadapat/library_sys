document.addEventListener("DOMContentLoaded", function () {
    const addBtn = document.getElementById("add-book-btn");
    const cancelBtn = document.getElementById("cancel-btn");
    const saveBtn = document.getElementById("save-book-btn");

    const header = document.getElementById("manage-books-header");
    const bookCard = document.getElementById("book-card");
    const formCard = document.getElementById("add-book-form-card");

    addBtn.addEventListener("click", () => {
        header.innerHTML = "<strong>Manage Books > Add Books</strong>";
        bookCard.style.display = "none";
        formCard.style.display = "block";
    });

    cancelBtn.addEventListener("click", () => {
        header.innerHTML = "<strong>Manage Books</strong>";
        bookCard.style.display = "block";
        formCard.style.display = "none";
    });

    saveBtn.addEventListener("click", () => {
        let title = document.getElementById("title").value;
        let author = document.getElementById("author").value;

        if (title && author) {
            let table = document.querySelector(".custom-table tbody");
            let newRow = table.insertRow();

            let checkboxCell = newRow.insertCell(0);
            let titleCell = newRow.insertCell(1);
            let authorCell = newRow.insertCell(2);
            let actionsCell = newRow.insertCell(3);

            checkboxCell.innerHTML = '<input type="checkbox">';
            titleCell.textContent = title;
            authorCell.textContent = author;

            let editBtn = document.createElement("button");
            editBtn.textContent = "Edit";
            editBtn.classList.add("btn", "btn-secondary");

            let deleteBtn = document.createElement("button");
            deleteBtn.textContent = "Delete";
            deleteBtn.classList.add("btn", "btn-danger");

            actionsCell.appendChild(editBtn);
            actionsCell.appendChild(deleteBtn);

            formCard.style.display = "none";
            bookCard.style.display = "block";

            document.getElementById("title").value = "";
            document.getElementById("author").value = "";
        } else {
            alert("Please fill in both fields");
        }
    });
});
