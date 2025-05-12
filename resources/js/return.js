document.addEventListener("DOMContentLoaded", function () {
    let currentTransId = null;
    let currentFineData = null;

    document.querySelectorAll(".lending-detail").forEach(function (button) {
        button.addEventListener("click", function () {
            currentTransId = this.dataset.id;

            document.getElementById("return-books-header").innerHTML =
                "<strong>Return > Return Details</strong>";
            document.getElementById("book-card").style.display = "none";
            document.getElementById("add-book-form-card").style.display =
                "block";

            fetchLendingDetails(currentTransId);
        });
    });

    // Fetch and render the lending details
    async function fetchLendingDetails(transId) {
        try {
            const response = await fetch(`/lending-details/${transId}`);
            const data = await response.json();
            console.log("Lending Details:", data);

            if (data.error) {
                alert(data.error);
                return;
            }

            document.querySelector(
                "#add-book-form-card form"
            ).action = `/${transId}/update`;

            if (data.user) {
                document.getElementById(
                    "member-name"
                ).textContent = `${data.user.first_name} ${data.user.last_name}`;
            } else {
                console.error("User data not found!");
            }

            document.getElementById("lend-id").textContent = data.trans_ID;
            document.getElementById("borrow-date").textContent = formatDate(
                data.borrow_date
            );
            document.getElementById("status").textContent = getStatus(data);
            document.getElementById("total-books").textContent =
                data.trans_details.length;
            document.getElementById("due-date").textContent = formatDate(
                data.due_date
            );

            // Render book table rows
            const tableBody = document.getElementById("book-table-body");
            tableBody.innerHTML = "";

            if (
                Array.isArray(data.trans_details) &&
                data.trans_details.length > 0
            ) {
                data.trans_details.forEach((detail) => {
                    const row = `
                    <tr data-row-id="${detail.tdetail_ID}" data-due-date="${
                        data.due_date
                    }">
                        <td>${
                            detail.book?.book_id || detail.book_ID || "N/A"
                        }</td>
                        <td>${detail.book?.title || "Unknown Title"}</td>
                        <td>${detail.book?.isbn || "N/A"}</td>
                        <td>
                            <select name="status[${
                                detail.tdetail_ID
                            }]" class="status-select" data-id="${
                        detail.tdetail_ID
                    }">
                                <option value="returned" ${
                                    detail.td_status === "returned"
                                        ? "selected"
                                        : ""
                                }>Returned</option>
                                <option value="lost" ${
                                    detail.td_status === "lost"
                                        ? "selected"
                                        : ""
                                }>Lost</option>
                                <option value="damaged" ${
                                    detail.td_status === "damaged"
                                        ? "selected"
                                        : ""
                                }>Damaged</option>
                                <option value="overdue" ${
                                    detail.td_status === "overdue"
                                        ? "selected"
                                        : ""
                                }>Overdue</option>
                            </select>
                        </td>
                        <td class="fine-amount">₱0.00</td>
                    </tr>
                    `;
                    tableBody.insertAdjacentHTML("beforeend", row);
                });

                await updateFines();
            } else {
                console.error("No books found in the lending details.");
            }
        } catch (error) {
            console.error("Error fetching lending detail:", error);
            alert("Failed to load lending details");
        }
    }

    // Cancel button to go back
    document
        .getElementById("cancel-btn")
        .addEventListener("click", function () {
            document.getElementById("return-books-header").innerHTML =
                "<strong>Return</strong>";
            document.getElementById("book-card").style.display = "block";
            document.getElementById("add-book-form-card").style.display =
                "none";
        });

    // Format date for display
    function formatDate(dateString) {
        const options = { year: "numeric", month: "long", day: "numeric" };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }

    // Determine status string
    function getStatus(data) {
        if (data.return_date) return "Returned";
        if (new Date(data.due_date) < new Date()) return "Overdue";
        return "Pending";
    }

    // Fetch fine rate from server
    async function getFineRate(reason) {
        try {
            const response = await fetch(`/get-fine-rate/${reason}`);
            const data = await response.json();
            return {
                amount: parseFloat(data.fine_amount) || 0,
                isPerDay: data.is_per_day || false,
            };
        } catch (error) {
            console.error(`Error fetching ${reason} fine rate:`, error);
            return { amount: 0, isPerDay: false };
        }
    }

    // Calculate days overdue
    function calculateOverdueDays(dueDate) {
        const today = new Date();
        const due = new Date(dueDate);
        const diffTime = today - due;
        return Math.max(0, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));
    }

    // Main fines calculation function
    async function updateFines() {
        const rows = document.querySelectorAll("tr[data-row-id]");
        const finesContainer = document.getElementById(
            "book-status-fines-container"
        );
        finesContainer.innerHTML = "";

        let totalFine = 0;

        const returnDateInput = document.createElement("input");
        returnDateInput.type = "hidden";
        returnDateInput.name = "return_date";
        returnDateInput.value = new Date().toISOString().split("T")[0];
        finesContainer.appendChild(returnDateInput);

        for (const row of rows) {
            const select = row.querySelector("select.status-select");
            const status = select.value;
            const fineCell = row.querySelector(".fine-amount");
            const tdetailId = row.dataset.rowId;
            let fineAmount = 0;

            if (status !== "returned") {
                const rate = await getFineRate(status);

                if (status === "overdue") {
                    const daysLate = calculateOverdueDays(row.dataset.dueDate);
                    fineAmount = daysLate * rate.amount;
                } else {
                    fineAmount = rate.amount;
                }
            }

            fineCell.textContent = `₱${fineAmount.toFixed(2)}`;
            totalFine += fineAmount;

            const statusInput = document.createElement("input");
            statusInput.type = "hidden";
            statusInput.name = `status[${tdetailId}]`;
            statusInput.value = status;
            finesContainer.appendChild(statusInput);

            const fineInput = document.createElement("input");
            fineInput.type = "hidden";
            fineInput.name = `fine[${tdetailId}]`;
            fineInput.value = fineAmount.toFixed(2);
            finesContainer.appendChild(fineInput);
        }

        document.querySelector(
            ".total-fine"
        ).textContent = `₱${totalFine.toFixed(2)}`;
    }

    document.addEventListener("change", function (e) {
        if (e.target && e.target.classList.contains("status-select")) {
            updateFines();
        }
    });

    // Modified form submission handler
    document
        .querySelector("#add-book-form-card form")
        .addEventListener("submit", async function (e) {
            e.preventDefault();

            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML =
                '<i class="bi bi-hourglass"></i> Processing...';
            submitButton.disabled = true;

            try {
                const response = await fetch(this.action, {
                    method: "POST",
                    body: new FormData(this),
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                const result = await response.json();

                if (response.ok) {
                    currentFineData = {
                        ...result,
                        items: Array.from(
                            document.querySelectorAll("tr[data-row-id]")
                        ).map((row) => ({
                            tdetail_id: row.dataset.rowId,
                            book_id: row.cells[0].textContent.trim(),
                            title: row.cells[1].textContent.trim(),
                            fine: parseFloat(
                                row
                                    .querySelector(".fine-amount")
                                    .textContent.replace("₱", "")
                            ),
                        })),
                    };

                    showReceiptModal({
                        transactionId: currentTransId,
                        memberName:
                            document.getElementById("member-name").textContent,
                        items: Array.from(
                            document.querySelectorAll("tr[data-row-id]")
                        ).map((row) => ({
                            book_id: row.cells[0].textContent,
                            title: row.cells[1].textContent,
                            fine: parseFloat(
                                row
                                    .querySelector(".fine-amount")
                                    .textContent.replace("₱", "")
                            ),
                        })),
                        totalFine: result.total_fine || 0,
                    });

                    if (result.has_fines) {
                        setTimeout(() => {
                            showPaymentOptions(result.total_fine);
                        }, 1000);
                    }
                } else {
                    throw new Error(
                        result.message || "Failed to process return"
                    );
                }
            } catch (error) {
                console.error("Submission error:", error);
                alert("Error: " + error.message);
            } finally {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });

    // Updated showReceiptModal function with validation
    function showReceiptModal(data) {
        const totalFine = data.totalFine || 0;

        const receiptHTML = `
        <div class="text-center mb-4">
            <h4>Library Book Return Receipt</h4>
            <p class="mb-1">Date: ${new Date().toLocaleDateString()}</p>
            <p>Transaction #: ${data.transactionId}</p>
        </div>
        
        <div class="row mb-3">
            <div class="col-6">
                <strong>Member:</strong> ${data.memberName}
            </div>
            <div class="col-6 text-end">
                <strong>Status:</strong> ${data.paymentStatus || "Completed"}
            </div>
        </div>
        
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th class="text-end">Status</th>
                    <th class="text-end">Fine</th>
                </tr>
            </thead>
            <tbody>
                ${data.items
                    .map((item) => {
                        const fineAmount = item.fine || 0;
                        return `
                    <tr>
                        <td>${item.book_id}</td>
                        <td>${item.title}</td>
                        <td class="text-end">${
                            item.status || getBookStatusText(item.condition)
                        }</td>
                        <td class="text-end">₱${fineAmount.toFixed(2)}</td>
                    </tr>
                    `;
                    })
                    .join("")}
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">Total Fine:</th>
                    <th class="text-end">₱${totalFine.toFixed(2)}</th>
                </tr>
                ${
                    data.paymentMethod
                        ? `
                <tr>
                    <th colspan="3" class="text-end">Payment Method:</th>
                    <th class="text-end">${data.paymentMethod}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Amount Tendered:</th>
                    <th class="text-end">₱${(data.amountPaid || 0).toFixed(
                        2
                    )}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">Change:</th>
                    <th class="text-end">₱${(
                        (data.amountPaid || 0) - totalFine
                    ).toFixed(2)}</th>
                </tr>
                `
                        : ""
                }
            </tfoot>
        </table>
    `; // Call this after showing any modal
        setupModalClose();

        document.getElementById("receiptContent").innerHTML = receiptHTML;
        const receiptModal = new bootstrap.Modal(
            document.getElementById("receiptModal")
        );
        receiptModal.show();
    }

    function showPaymentOptions(totalFine) {
        document.getElementById(
            "paymentTotalAmount"
        ).textContent = `₱${totalFine.toFixed(2)}`;
        const paymentOptionsModal = new bootstrap.Modal(
            document.getElementById("paymentOptionsModal")
        );
        paymentOptionsModal.show();
    }
    setupModalClose();

    document.getElementById("payNowBtn").addEventListener("click", function () {
        const totalFine = parseFloat(
            document
                .getElementById("paymentTotalAmount")
                .textContent.replace("₱", "")
        );

        document.getElementById("amountDue").value = `₱${totalFine.toFixed(2)}`;
        bootstrap.Modal.getInstance(
            document.getElementById("paymentOptionsModal")
        ).hide();
        const paymentFormModal = new bootstrap.Modal(
            document.getElementById("paymentFormModal")
        );
        paymentFormModal.show();
    });

    document
        .getElementById("payLaterBtn")
        .addEventListener("click", function () {
            // Mark fines as unpaid and redirect
            processPaymentLater(currentTransId);
        });

    // Payment method change handler
    document
        .getElementById("paymentMethod")
        .addEventListener("change", function () {
            const cashFields = document.getElementById("cashFields");
            cashFields.style.display = this.value === "cash" ? "block" : "none";
        });

    function getBookStatusText(condition) {
        const statusMap = {
            lost: "Lost",
            damaged: "Damaged",
            overdue: "Overdue",
            good: "No Fine",
            default: "With Fine",
        };
        return statusMap[condition] || statusMap["default"];
    }

    // Amount tendered calculation
    document
        .getElementById("amountTendered")
        .addEventListener("input", function () {
            const amountDue = parseFloat(
                document.getElementById("amountDue").value.replace("₱", "")
            );
            const amountTendered = parseFloat(this.value) || 0;
            const change = amountTendered - amountDue;

            const changeAmount = document.getElementById("changeAmount");
            if (change >= 0) {
                changeAmount.textContent = `Change: ₱${change.toFixed(2)}`;
                changeAmount.style.display = "block";
            } else {
                changeAmount.style.display = "none";
            }
        });

    // Updated confirm payment handler
    document
        .getElementById("confirmPaymentBtn")
        .addEventListener("click", async function () {
            const btn = this;
            const paymentMethod =
                document.getElementById("paymentMethod").value;
            const amountDue = parseFloat(
                document.getElementById("amountDue").value.replace("₱", "")
            );
            const amountTendered =
                paymentMethod === "cash"
                    ? parseFloat(
                          document.getElementById("amountTendered").value
                      ) || 0
                    : amountDue;

            // UI Loading State
            btn.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Processing...';
            btn.disabled = true;

            try {
                const response = await fetch("/fines/pay-now", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                    },
                    body: JSON.stringify({
                        trans_id: currentTransId,
                        amount: amountDue,
                        payment_method: paymentMethod,
                        amount_tendered: amountTendered,
                        items: currentFineData.items.map((item) => ({
                            tdetail_id: item.tdetail_id,
                            fine_amount: item.fine,
                            condition: item.condition, // Make sure this is passed from backend
                        })),
                    }),
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(
                        result.message || result.error || "Payment failed"
                    );
                }

                // Show receipt with payment details
                showReceiptModal({
                    transactionId: currentTransId,
                    memberName:
                        document.getElementById("member-name").textContent,
                    items: currentFineData.items.map((item) => ({
                        book_id: item.book_id,
                        title: item.title,
                        status: getBookStatusText(item.condition),
                        fine: item.fine,
                        condition: item.condition,
                    })),
                    totalFine: amountDue,
                    paymentStatus: "Paid",
                    paymentMethod:
                        paymentMethod === "cash"
                            ? "Cash"
                            : paymentMethod === "gcash"
                            ? "GCash"
                            : "Other",
                    amountPaid: amountTendered,
                });

                // Hide payment form modal
                bootstrap.Modal.getInstance(
                    document.getElementById("paymentFormModal")
                ).hide();
            } catch (error) {
                console.error("Payment Error:", error);
                alert("Payment Error: " + error.message);
            } finally {
                btn.innerHTML =
                    '<i class="bi bi-check-circle"></i> Confirm Payment';
                btn.disabled = false;
            }
        });

    // Enhanced processPaymentLater function
    async function processPaymentLater(transId) {
        const btn = document.getElementById("payLaterBtn");
        btn.innerHTML =
            '<span class="spinner-border spinner-border-sm"></span> Processing...';
        btn.disabled = true;

        try {
            if (!currentFineData?.items || currentFineData.items.length === 0) {
                throw new Error("No fine items found to process");
            }

            // Prepare data
            const itemsToSend = currentFineData.items.map((item) => ({
                tdetail_id: item.tdetail_id,
                fine_amount: item.fine,
                book_id: item.book_id,
            }));

            // Send to backend
            const response = await fetch("/fines/pay-later", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    trans_id: transId,
                    items: itemsToSend,
                }),
            });

            if (!response.ok) {
                const errorResult = await response.json().catch(() => ({}));
                throw new Error(
                    errorResult.message ||
                        errorResult.error ||
                        `Server responded with status ${response.status}`
                );
            }

            const result = await response.json();

            if (result.success) {
                // Show updated receipt with "Pay Later" status
                showReceiptModal({
                    transactionId: currentTransId,
                    memberName:
                        document.getElementById("member-name").textContent,
                    items: currentFineData.items.map((item) => ({
                        ...item,
                        status: "To Pay Later", // Add status for receipt
                    })),
                    totalFine: result.total_fine || 0,
                    paymentStatus: "Unpaid", // Add payment status
                    paymentMethod: "Pay Later", // Add payment method
                });

                // Add a button to proceed to fines UI
                const receiptFooter = document.createElement("div");
                receiptFooter.className = "mt-3 text-center";
                receiptFooter.innerHTML = `
                <button id="proceedToFinesBtn" class="btn btn-primary">
                    Proceed to Fines Management
                </button>
            `;
                document
                    .getElementById("receiptContent")
                    .appendChild(receiptFooter);

                // Handle proceed button click
                document
                    .getElementById("proceedToFinesBtn")
                    .addEventListener("click", () => {
                        bootstrap.Modal.getInstance(
                            document.getElementById("receiptModal")
                        ).hide();
                        window.location.href =
                            result.redirect_url || "/admin/fine";
                    });
            } else {
                throw new Error(result.error || "Failed to record fines");
            }
        } catch (error) {
            console.error("Payment Processing Error:", error);
            alert(
                "Error: " + (error.message || "Failed to process payment later")
            );
        } finally {
            btn.innerHTML = '<i class="bi bi-clock"></i> Pay Later';
            btn.disabled = false;
        }
    }

    // Enhanced print receipt handler
    document
        .getElementById("printReceiptBtn")
        .addEventListener("click", function () {
            // Store original content
            const originalContent = document.body.innerHTML;
            const modal = bootstrap.Modal.getInstance(
                document.getElementById("receiptModal")
            );

            // Create print view
            document.body.innerHTML = `
        <div class="container p-4">
            ${document.getElementById("receiptContent").innerHTML}
            <div class="text-center mt-3">
                <button id="closePrint" class="btn btn-secondary">Close</button>
            </div>
        </div>
    `;

            // Print after short delay
            setTimeout(() => {
                window.print();
            }, 200);

            // Close handler
            document
                .getElementById("closePrint")
                .addEventListener("click", function () {
                    location.reload();
                });

            // Auto-refresh if print dialog is canceled
            setTimeout(() => {
                if (document.getElementById("closePrint")) {
                    location.reload();
                }
            }, 10000); // 10 second fallback
        });

    // Proper modal closing handler
    function setupModalClose() {
        const receiptModal = document.getElementById("receiptModal");
        const modalInstance = bootstrap.Modal.getInstance(receiptModal);

        // Clean up any existing backdrops
        document
            .querySelectorAll(".modal-backdrop")
            .forEach((el) => el.remove());

        // Proper close handler
        receiptModal.addEventListener("hidden.bs.modal", function () {
            document.body.classList.remove("modal-open");
            document.body.style.overflow = "";
            document.body.style.paddingRight = "";
        });

        // Manual close button
        document
            .querySelector("#receiptModal .btn-close")
            .addEventListener("click", function () {
                modalInstance.hide();
            });
    }
});
