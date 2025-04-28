 // JavaScript to toggle between table and form
 document.getElementById('employees-btn').addEventListener('click', function() {
    // Change header to "Manage Books > Add Books"
    document.getElementById('employees-header').innerHTML = '<strong>Employees > Employee Details</strong>';

    // Hide the table card and show the form card
    document.getElementById('book-card').style.display = 'none';
    document.getElementById('add-employee-form-card').style.display = 'block';
});

document.getElementById('cancel-btn').addEventListener('click', function() {
    // Reset header back to "Manage Books"
    document.getElementById('employees-header').innerHTML = '<strong>Employees</strong>';

    // Show the table card and hide the form card
    document.getElementById('book-card').style.display = 'block';
    document.getElementById('add-employee-form-card').style.display = 'none';
}); 

// Modal Part
const employeeModal = new bootstrap.Modal(document.getElementById('employeeModal'));

document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
        // Show the modal
        employeeModal.show();
    });
});