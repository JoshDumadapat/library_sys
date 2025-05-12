document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('myTable');
    const rows = table.querySelectorAll('tbody tr');
    const rowsPerPage = 5; // Changed to 5 as requested
    const pageCount = Math.ceil(rows.length / rowsPerPage);
    const pageNumbers = document.getElementById('pageNumbers');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const pagination = document.getElementById('pagination');
    
    let currentPage = 1;
    
    // Function to display rows for current page
    function displayRows() {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        
        rows.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? '' : 'none';
        });
    }
    
    // Function to update pagination buttons
    function updatePagination() {
        pageNumbers.innerHTML = '';
        
        // Create page number buttons
        for (let i = 1; i <= pageCount; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = 'btn' + (i === currentPage ? ' active' : '');
        pageBtn.textContent = i;
        pageBtn.addEventListener('click', () => {
            currentPage = i;
            displayRows();
            updatePagination();
        });
        pageNumbers.appendChild(pageBtn);
        }
        
        // Enable/disable prev/next buttons
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === pageCount;
    }
    
    // Event listeners for prev/next buttons
    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
        currentPage--;
        displayRows();
        updatePagination();
        }
    });
    
    nextBtn.addEventListener('click', () => {
        if (currentPage < pageCount) {
        currentPage++;
        displayRows();
        updatePagination();
        }
    });
    
    // Initialize
    if (rows.length > rowsPerPage) {
        displayRows();
        updatePagination();
        pagination.style.display = 'flex'; // Show pagination
    } else {
        pagination.style.display = 'none'; // Hide pagination if not needed
    }
});

const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

const toggle = document.getElementById('darkModeToggle');
const icon = document.querySelector('.icon-toggle');
const tooltipElement = document.querySelector('.switch');
const body = document.body;

// Check if dark mode was previously enabled in localStorage
if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
    icon.classList.remove('bi-sun-fill');
    icon.classList.add('bi-moon-fill');
    tooltipElement.setAttribute('title', 'Switch to Light Mode');
    toggle.checked = true; // Ensure the toggle is checked
}

// Dark mode toggle event listener
toggle.addEventListener('change', function() {
    if (this.checked) {
        icon.classList.remove('bi-sun-fill');
        icon.classList.add('bi-moon-fill');
        tooltipElement.setAttribute('title', 'Switch to Light Mode');
        body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'enabled'); // Save dark mode state
    } else {
        icon.classList.remove('bi-moon-fill');
        icon.classList.add('bi-sun-fill');
        tooltipElement.setAttribute('title', 'Switch to Dark Mode');
        body.classList.remove('dark-mode');
        localStorage.removeItem('darkMode'); // Remove dark mode state
    }

    bootstrap.Tooltip.getInstance(tooltipElement).hide();
    bootstrap.Tooltip.getInstance(tooltipElement).setContent({
        '.tooltip-inner': tooltipElement.getAttribute('title')
    });
});

// Tooltip event listeners
tooltipElement.addEventListener('mouseenter', function() {
    bootstrap.Tooltip.getInstance(tooltipElement).show();
});

tooltipElement.addEventListener('mouseleave', function() {
    bootstrap.Tooltip.getInstance(tooltipElement).hide();
});