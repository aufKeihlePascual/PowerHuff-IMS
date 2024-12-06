/****************  SUPPLIER MANAGEMENT DELETE  ****************/
const deleteSupplierModal = document.getElementById('deleteSupplierModal');
const closeDeleteSupplierModal = document.getElementById('closeDeleteSupplierModal');
const cancelDeleteBtn = document.getElementById('cancelDeleteSupplierBtn');
const deleteSupplierForm = document.getElementById('deleteSupplierForm');
const supplierIdToDelete = document.getElementById('supplierIdToDelete');

// When the "Delete" button is clicked (inside the supplier table row)
$('.delete-supplier').click(function(e) {
    e.preventDefault();
    const supplierId = $(this).data('supplier-id');
    supplierIdToDelete.value = supplierId;
    deleteSupplierModal.style.display = 'block';
});

// Close the modal when the "X" button is clicked
closeDeleteSupplierModal.onclick = function() {
    deleteSupplierModal.style.display = 'none';
};

// Close the modal when the "Cancel" button is clicked
cancelDeleteBtn.onclick = function() {
    deleteSupplierModal.style.display = 'none';
};

// Close the modal if the user clicks outside the modal
window.onclick = function(event) {
    if (event.target === deleteSupplierModal || event.target === addSupplierModal) {
        event.target.style.display = 'none';
    }
};

// Handle form submission to delete supplier
deleteSupplierForm.onsubmit = function(e) {
    e.preventDefault();
    const supplierId = supplierIdToDelete.value;
    // You can handle the deletion through the AJAX if needed
    deleteSupplierForm.submit(); // Submit the form normally
};

/****************  SUPPLIER CREATION MODAL ****************/
const addSupplierModal = document.getElementById('addSupplierModal');
const addSupplierBtn = document.getElementById('addSupplierBtn');
const closeSupplierModalBtn = document.getElementById('closeSupplierModalBtn');

// Open modal to create new supplier
addSupplierBtn.onclick = function() {
    addSupplierModal.style.display = 'block';
}

// Close the modal when the "X" button is clicked
closeSupplierModalBtn.onclick = function() {
    addSupplierModal.style.display = 'none';
}

// Close the modal if clicked outside of the modal
window.onclick = function(event) {
    if (event.target === addSupplierModal) {
        addSupplierModal.style.display = 'none';
    }
}

// Handle Supplier Creation Form Submission with AJAX
$(".supplier-form").submit(function(event) {
    event.preventDefault(); // Prevent form from submitting

    const form = $(this);
    $.ajax({
        url: '/create-supplier',
        method: 'POST',
        data: form.serialize(), // Serialize form data
        success: function(response) {
            // Close the add supplier modal and show confirmation modal
            $("#addSupplierModal").hide();
            $("#confirmationModal").show();
        },
        error: function(xhr, status, error) {
            alert("Error: Unable to create supplier.");
        }
    });
});

// Close confirmation modal and redirect to suppliers list
$("#closeConfirmationBtn").click(function() {
    $("#confirmationModal").hide();
    window.location.href = '/dashboard/suppliers';
});

/****************  SUPPLIER SEARCH FILTER ****************/
const searchInput = document.getElementById('searchSupplier');
searchInput.addEventListener('input', function() {
    let filter = searchInput.value.toLowerCase();
    let rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        let supplierName = row.cells[0].textContent.toLowerCase();
        let productCategory = row.cells[1].textContent.toLowerCase();

        if (supplierName.includes(filter) || productCategory.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
