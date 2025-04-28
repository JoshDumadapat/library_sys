document.addEventListener("DOMContentLoaded", function() {
    const rowsPerPage = 10;
    const tableBody = document.getElementById("book-table-body");
    const paginationContainer = document.getElementById("pagination");

    const rows = Array.from(tableBody.querySelectorAll("tr"));
    const pageCount = Math.ceil(rows.length / rowsPerPage);

    let currentPage = 1;

    function displayPage(page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
            row.style.display = index >= start && index < end ? "" : "none";
        });
    }

    function createButton(label, page = null, disabled = false) {
        const btn = document.createElement("button");
        btn.innerText = label;
        btn.className = "btn btn-sm mx-1 btn-outline-primary";
        if (disabled) btn.disabled = true;
        if (page === currentPage) btn.classList.add("active");

        if (page !== null) {
            btn.addEventListener("click", () => {
                currentPage = page;
                displayPage(currentPage);
                setupPagination();
            });
        }

        return btn;
    }

    function setupPagination() {
        paginationContainer.innerHTML = "";

        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(pageCount, currentPage + 2);

        if (endPage - startPage < maxVisiblePages - 1) {
            if (startPage === 1) {
                endPage = Math.min(pageCount, startPage + maxVisiblePages - 1);
            } else if (endPage === pageCount) {
                startPage = Math.max(1, pageCount - maxVisiblePages + 1);
            }
        }

        // Prev
        paginationContainer.appendChild(
            createButton("« Prev", currentPage - 1, currentPage === 1)
        );

        // First page + dots
        if (startPage > 1) {
            paginationContainer.appendChild(createButton(1, 1));
            if (startPage > 2) {
                const dots = document.createElement("span");
                dots.innerText = "...";
                dots.className = "mx-1";
                paginationContainer.appendChild(dots);
            }
        }

        // Middle page numbers
        for (let i = startPage; i <= endPage; i++) {
            paginationContainer.appendChild(createButton(i, i));
        }

        // Dots + last page
        if (endPage < pageCount) {
            if (endPage < pageCount - 1) {
                const dots = document.createElement("span");
                dots.innerText = "...";
                dots.className = "mx-1";
                paginationContainer.appendChild(dots);
            }
            paginationContainer.appendChild(createButton(pageCount, pageCount));
        }

        // Next
        paginationContainer.appendChild(
            createButton("Next »", currentPage + 1, currentPage === pageCount)
        );
    }

    displayPage(currentPage);
    setupPagination();
});