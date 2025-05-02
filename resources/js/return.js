document.addEventListener('DOMContentLoaded', function () {
    // Attach event to ALL buttons with class "lending-detail"
    document.querySelectorAll('.lending-detail').forEach(function (button) {
        button.addEventListener('click', function () {
            const lendId = this.dataset.id;

            // Update header and show/hide panels
            document.getElementById('return-books-header').innerHTML = '<strong>Return > Return Details</strong>';
            document.getElementById('book-card').style.display = 'none';
            document.getElementById('add-book-form-card').style.display = 'block';

            // Fetch lending details
            fetchLendingDetails(lendId);
        });
    });
    

    // Fetch and render the lending details
    async function fetchLendingDetails(transId) {
        try {
            const response = await fetch(`/lending-details/${transId}`);
            const data = await response.json();
            console.log(data);

            if (data.error) {
                alert(data.error);
                return;
            }

            // Set member and transaction info
            if (data.user) {
                document.getElementById('member-name').textContent = `${data.user.first_name} ${data.user.last_name}`;
            } else {
                console.error('User data not found!');
            }

            document.getElementById('lend-id').textContent = data.trans_ID;
            document.getElementById('borrow-date').textContent = formatDate(data.borrow_date);
            document.getElementById('status').textContent = getStatus(data);
            document.getElementById('total-books').textContent = data.trans_details.length;
            document.getElementById('due-date').textContent = formatDate(data.due_date);

            
            // Render book table rows
            const tableBody = document.getElementById('book-table-body');
            tableBody.innerHTML = '';
            let totalFine = 0;

            data.trans_details.forEach(detail => {
                const fineAmt = detail.fines.reduce((sum, fine) => sum + parseFloat(fine.fine_amt), 0);
                totalFine += fineAmt;

                const row = `
                    <tr data-row-id="${detail.tdetail_ID}">
                        <td>${detail.book?.book_id || 'N/A'}</td>
                        <td>${detail.book?.title || 'Unknown Title'}</td>
                        <td>${detail.book?.isbn || 'N/A'}</td>
                        <td>
                            <select name="status[${detail.tdetail_ID}]" class="status-select" data-id="${detail.tdetail_ID}" id="status_${detail.tdetail_ID}">
                                <option value="returned" ${detail.td_status === 'returned' ? 'selected' : ''}>Returned</option>
                                <option value="lost" ${detail.td_status === 'lost' ? 'selected' : ''}>Lost</option>
                                <option value="damaged" ${detail.td_status === 'damaged' ? 'selected' : ''}>Damaged</option>
                                <option value="overdue" ${detail.td_status === 'overdue' ? 'selected' : ''}>Overdue</option>
                            </select>
                        </td>
                        <td class="fine-amount" id="fine_${detail.tdetail_ID}">₱${fineAmt.toFixed(2)}</td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });

            document.getElementById('total-fine').textContent = totalFine.toFixed(2);
            updateFines(); // Recalculate based on current statuses

        } catch (error) {
            console.error('Error fetching lending detail:', error);
        }
    }

    // Cancel button to go back
    document.getElementById('cancel-btn').addEventListener('click', function () {
        document.getElementById('return-books-header').innerHTML = '<strong>Return</strong>';
        document.getElementById('book-card').style.display = 'block';
        document.getElementById('add-book-form-card').style.display = 'none';
    });

    // Format date for display
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }

    // Determine status string
    function getStatus(data) {
        if (data.return_date) return 'Returned';
        if (new Date(data.due_date) < new Date()) return 'Overdue';
        return 'Pending';
    }

    // Fine handling
    const fineTotals = {
        overdue: 0,
        lost: 0,
        damaged: 0,
    };

    async function getFineAmount(reason) {
        try {
            const response = await fetch(`/get-fine-rate/${reason}`);
            const data = await response.json();
            return parseFloat(data.fine_amt) || 0;
        } catch (error) {
            console.error('Error fetching fine rate:', error);
            return 0;
        }
    }

    async function updateFines() {
        // Reset fine totals
        fineTotals.overdue = 0;
        fineTotals.lost = 0;
        fineTotals.damaged = 0;

        const rows = document.querySelectorAll('tr[data-row-id]');
        for (const row of rows) {
            const select = row.querySelector('select.status-select');
            const status = select.value;
            const fineCell = row.querySelector('.fine-amount');

            let fine = 0;

            if (status !== 'returned') {
                fine = await getFineAmount(status);
                fineTotals[status] += fine;
            }

            fineCell.textContent = `₱${fine.toFixed(2)}`;
        }

        // Update summary UI
        const overdueSpan = document.querySelector('.overdue-fine');
        const lostSpan = document.querySelector('.missing-fine');
        const damagedSpan = document.querySelector('.damaged-fine');
        const totalSpan = document.querySelector('.total-fine');

        if (overdueSpan) overdueSpan.textContent = `₱${fineTotals.overdue.toFixed(2)}`;
        if (lostSpan) lostSpan.textContent = `₱${fineTotals.lost.toFixed(2)}`;
        if (damagedSpan) damagedSpan.textContent = `₱${fineTotals.damaged.toFixed(2)}`;
        if (totalSpan) totalSpan.textContent = `₱${(fineTotals.overdue + fineTotals.lost + fineTotals.damaged).toFixed(2)}`;
    }

    // Use event delegation for dynamically loaded rows
    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('status-select')) {
            updateFines();
        }
    });
});
