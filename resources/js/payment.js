$(document).ready(function () {
    // Initialize modals
    const finesModal = new bootstrap.Modal(
        document.getElementById("finesDetailsModal")
    );
    const paymentModal = new bootstrap.Modal(
        document.getElementById("paymentModal")
    );
    const receiptModal = new bootstrap.Modal(
        document.getElementById("receiptModal")
    );

    // View button click handler
    $(".view-fines-btn").on("click", function () {
        const transactionId = $(this).data("transaction-id");
        const memberId = $(this).data("member-id");
        const $modal = $("#finesDetailsModal");

        // Show loading state
        $modal
            .find("#finesDetailsBody")
            .html(
                '<tr><td colspan="5" class="text-center">Loading fines details...</td></tr>'
            );
        $modal.find("#modalTransactionId").text(transactionId);

        // Load fines details via AJAX
        $.ajax({
            url: `/admin/fines/transaction/${transactionId}`,
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                console.log("API Response:", response); // Debugging
                console.log("Complete API Response:", response);
                console.log("First fine object structure:", response.fines[0]);

                if (response.success) {
                    let html = "";
                    let totalAmount = 0;
                    let hasUnpaid = false;
                    let paymentItems = []; // For payment processing
                    let fineItems = []; // For receipt generation

                    if (response.fines && response.fines.length > 0) {
                        response.fines.forEach((fine) => {
                            const isPaid = fine.status === "paid";
                            if (!isPaid) hasUnpaid = true;

                            html += `
                                <tr>
                                    <td>${
                                        fine.book_title || "Unknown Book"
                                    }</td>
                                    <td>₱${parseFloat(fine.amount).toFixed(
                                        2
                                    )}</td>
                                    <td>${fine.reason || "N/A"}</td>
                                    <td>
                                        <span class="badge ${
                                            isPaid ? "bg-success" : "bg-danger"
                                        }">
                                            ${fine.status}
                                        </span>
                                    </td>
                                    <td>${fine.created_at || "N/A"}</td>
                                </tr>
                            `;

                            totalAmount += parseFloat(fine.amount);

                            // Prepare data for payment submission
                            if (!isPaid) {
                                paymentItems.push({
                                    tdetail_id: fine.fine_id, // Actual fine_id from your API
                                    original_tdetail_id:
                                        fine.tdetail_id || fine.tdetail_ID, // Preserve for other UIs
                                    fine_amount: parseFloat(fine.amount),
                                });
                            }

                            // Prepare data for receipt
                            fineItems.push({
                                book_title: fine.book_title || "Unknown Book",
                                amount: parseFloat(fine.amount),
                                reason: fine.reason || "N/A",
                                status: fine.status,
                                date: fine.created_at || "N/A",
                            });
                        });
                    }

                    // Update UI
                    $modal
                        .find("#finesDetailsBody")
                        .html(
                            html ||
                                '<tr><td colspan="5" class="text-center">No fines found.</td></tr>'
                        );
                    $modal
                        .find("#totalAmountDue")
                        .text("₱" + totalAmount.toFixed(2));
                    $modal
                        .find("#modalMemberName")
                        .text(response.transaction.member_name || "N/A");

                    // Store data for payment processing
                    $("#paymentTransactionId").val(transactionId);
                    $("#paymentFineIds").val(JSON.stringify(paymentItems));
                    $("#paymentAmount").val(totalAmount.toFixed(2));
                    $("#paymentAmountInput").val(totalAmount.toFixed(2));

                    // Store receipt data
                    $("#paymentForm").data(
                        "member-name",
                        response.transaction.member_name || "N/A"
                    );
                    $("#paymentForm").data("fine-items", fineItems);

                    $("#payAllFinesBtn").prop("disabled", !hasUnpaid);
                    finesModal.show();
                }
            },
            error: function (xhr) {
                console.error("Error:", xhr.responseText);
                $modal
                    .find("#finesDetailsBody")
                    .html(
                        '<tr><td colspan="5" class="text-center text-danger">Error loading data.</td></tr>'
                    );
                finesModal.show();
            },
        });
    });

    // Pay all fines button
    $("#payAllFinesBtn").click(function () {
        finesModal.hide();
        paymentModal.show();

        // Initialize payment method change handler
        $("#paymentMethod")
            .off("change")
            .on("change", function () {
                const method = $(this).val();
                if (method === "cash") {
                    $("#cashFields").show();
                    $("#amountTendered").val("").trigger("input");
                } else {
                    $("#cashFields").hide();
                    $("#amountTendered")
                        .val($("#paymentAmountInput").val())
                        .trigger("input");
                }
            })
            .trigger("change");
    });

    // Amount tendered calculation
    $("#amountTendered").on("input", function () {
        const amountDue = parseFloat($("#paymentAmountInput").val());
        const amountTendered = parseFloat($(this).val()) || 0;
        const change = amountTendered - amountDue;

        if (change >= 0) {
            $("#changeAmount")
                .text(`Change: ₱${change.toFixed(2)}`)
                .show();
        } else {
            $("#changeAmount").hide();
        }
    });

    // Payment form submission
    $("#paymentForm").on("submit", function (e) {
        e.preventDefault();
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const paymentMethod = $("#paymentMethod").val();
        const amountDue = parseFloat($("#paymentAmountInput").val());
        const amountTendered =
            paymentMethod === "cash"
                ? parseFloat($("#amountTendered").val()) || 0
                : amountDue;

        // Validation
        if (paymentMethod === "cash" && amountTendered < amountDue) {
            Swal.fire("Error", "Insufficient amount tendered", "error");
            return;
        }

        $submitBtn
            .prop("disabled", true)
            .html(
                '<span class="spinner-border spinner-border-sm"></span> Processing...'
            );

        // Prepare payment data that matches server validation
        console.log("Raw paymentFineIds value:", $("#paymentFineIds").val());
        console.log(
            "Parsed paymentFineIds:",
            JSON.parse($("#paymentFineIds").val())
        );

        const paymentData = {
            trans_id: $("#paymentTransactionId").val(),
            payment_method: paymentMethod,
            items: JSON.parse($("#paymentFineIds").val()).map((item) => ({
                tdetail_id: item.tdetail_id || item.tdetail_ID, // Try both possible property names
                fine_amount: item.fine_amount,
            })),
            amount: amountDue,
            amount_tendered: amountTendered,
            reference:
                paymentMethod === "cash"
                    ? `Cash payment - tendered: ₱${amountTendered.toFixed(2)}`
                    : "Online payment",
            collected_by: "admin",
            _token: $('meta[name="csrf-token"]').attr("content"),
        };

        console.log("Submitting payment:", paymentData); // Debugging

        $.ajax({
            url: "/fines/pay-now",
            method: "POST",
            data: paymentData,
            success: function (response) {
                console.log("Payment response:", response);
                if (response.success) {
                    paymentModal.hide();
                    generateReceipt({
                        transactionId: paymentData.trans_id,
                        memberName: $form.data("member-name"),
                        fineItems: $form.data("fine-items"),
                        totalAmount: amountDue,
                        paymentMethod: paymentMethod,
                        amountTendered: amountTendered,
                        change: amountTendered - amountDue,
                        paymentDate: new Date().toLocaleString(),
                    });
                    receiptModal.show();
                } else {
                    Swal.fire(
                        "Error",
                        response.message || "Payment failed",
                        "error"
                    );
                }
            },
            error: function (xhr) {
                console.error("Payment error:", xhr.responseText);
                let errorMsg = "Payment failed";

                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join("<br>");
                } else if (xhr.responseJSON?.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                Swal.fire({
                    title: "Error",
                    html: errorMsg,
                    icon: "error",
                });
            },
            complete: function () {
                $submitBtn.prop("disabled", false).text("Submit Payment");
            },
        });
    });

    // Generate receipt function
    function generateReceipt(data) {
        let itemsHtml = "";
        data.fineItems.forEach((item) => {
            itemsHtml += `
                <tr>
                    <td>${item.book_title}</td>
                    <td>${item.reason}</td>
                    <td class="text-end">₱${item.amount.toFixed(2)}</td>
                </tr>
            `;
        });

        const receiptHtml = `
            <div class="receipt-container">
                <h4 class="text-center mb-3">Library Fine Payment Receipt</h4>
                <div class="receipt-header mb-3">
                    <p><strong>Transaction ID:</strong> TRANS-${
                        data.transactionId
                    }</p>
                    <p><strong>Member:</strong> ${data.memberName}</p>
                    <p><strong>Payment Date:</strong> ${data.paymentDate}</p>
                    <p><strong>Payment Method:</strong> ${
                        data.paymentMethod.charAt(0).toUpperCase() +
                        data.paymentMethod.slice(1)
                    }</p>
                </div>
                
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Reason</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2">Total:</th>
                            <th class="text-end">₱${data.totalAmount.toFixed(
                                2
                            )}</th>
                        </tr>
                        <tr>
                            <th colspan="2">Amount Tendered:</th>
                            <th class="text-end">₱${data.amountTendered.toFixed(
                                2
                            )}</th>
                        </tr>
                        <tr>
                            <th colspan="2">Change:</th>
                            <th class="text-end">₱${data.change.toFixed(2)}</th>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="text-center mt-4">
                    <p>Thank you for your payment!</p>
                    <button id="printReceiptBtn" class="btn btn-sm btn-primary me-2">Print Receipt</button>
                    <button id="closeReceiptBtn" class="btn btn-sm btn-secondary">Close</button>
                </div>
            </div>
        `;

        $("#receiptContent").html(receiptHtml);

        // Add print functionality
        $("#printReceiptBtn").on("click", function () {
            const printWindow = window.open("", "", "width=600,height=600");
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Payment Receipt</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                        <style>
                            body { padding: 20px; }
                            .receipt-container { max-width: 500px; margin: 0 auto; }
                            @media print {
                                button { display: none !important; }
                                body { font-size: 12px; }
                            }
                        </style>
                    </head>
                    <body>
                        ${receiptHtml}
                        <script>
                            window.onload = function() {
                                setTimeout(function() {
                                    window.print();
                                    window.close();
                                }, 200);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        });

        // Close receipt button
        $("#closeReceiptBtn").on("click", function () {
            receiptModal.hide();
            window.location.reload(); // Refresh to update status
        });
    }
});
