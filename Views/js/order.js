$(document).ready(function() {
    
    /****************  ORDER MANAGEMENT DELETE  ****************/
    const deleteOrderModal = document.getElementById('deleteUserModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const deleteOrderForm = document.getElementById('deleteUserForm');
    const orderIdToDelete = document.getElementById('orderIdToDelete');

    $('.delete-user').click(function(e) {
        e.preventDefault();

        const orderId = $(this).data('order-id');
        orderIdToDelete.value = orderId;
        deleteOrderModal.style.display = 'block';
    });

    closeDeleteModal.onclick = function() {
        deleteOrderModal.style.display = 'none';
    };

    cancelDeleteBtn.onclick = function() {
        deleteOrderModal.style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target === deleteOrderModal) {
            deleteOrderModal.style.display = 'none';
        }
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };

    deleteOrderForm.onsubmit = function(e) {
        e.preventDefault();

        const orderId = orderIdToDelete.value;

        $.ajax({
            url: '/delete-order/' + orderId,
            method: 'POST',
            data: {
                order_id: orderId
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
        
        deleteOrderModal.style.display = 'none';
    };

    /****************  ORDER MANAGEMENT ADD ****************/
    const modal = document.getElementById('addUserModal');
    const addUserBtn = document.getElementById('addUserBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');

    addUserBtn.onclick = function() {
        modal.style.display = 'block';
    }

    closeModalBtn.onclick = function() {
        modal.style.display = 'none';
    }

    // window.onclick = function(event) {
    //     if (event.target === modal) {
    //         modal.style.display = 'none';
    //     }
    // }

    $(".user-form").submit(function(event) {
        event.preventDefault();

        const form = $(this);
        $.ajax({
            url: '/create-order',
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $("#addUserModal").hide();
                $("#confirmationModal").show();
            },
            error: function(xhr, status, error) {
                alert("Error: Unable to create user.");
            }
        });
    });

    $("#closeConfirmationModal").click(function() {
        $("#confirmationModal").hide();
        window.location.href = '/dashboard/orders'; 
    });

    $("#closeConfirmationBtn").click(function() {
        $("#confirmationModal").hide();
        window.location.href = '/dashboard/orders'; 
    });

    /****************  GENERATE PDF  ****************/
    document.addEventListener('DOMContentLoaded', function () {
        const generatePdfButton = document.getElementById('generatePdf'); // Get the button element by its ID
    
        if (generatePdfButton) {
            // Add event listener to the button
            generatePdfButton.addEventListener('click', function () {
                const orderId = generatePdfButton.getAttribute('data-order-id'); // Get the order_id from the button's data attribute
                console.log('Order ID:', orderId);
    
                // Now you can proceed with the AJAX request
                $.ajax({
                    url: '/generate-pdf-order',
                    method: 'POST',
                    data: { order_id: orderId },
                    success: function (response) {
                        console.log(response);
                        if (response.success) {
                            alert('PDF generated successfully');
                        } else {
                            alert('Error generating PDF');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                        alert('Error generating PDF: ' + error);
                    }
                });
            });
        } else {
            console.log('Generate PDF button not found!');
        }
    });
    

    /****************  SEARCH ORDER FILTER  ****************/
    const searchInput = document.getElementById('searchBar');
    searchInput.addEventListener('input', function() {
        let filter = searchInput.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let orderName = row.cells[0].textContent.toLowerCase();
            let date = row.cells[2].textContent.toLowerCase();
            let status = row.cells[3].textContent.toLowerCase();
            let supplier = row.cells[4].textContent.toLowerCase();
            let user = row.cells[5].textContent.toLowerCase();


            if (orderName.includes(filter) || date.includes(filter) || status.includes(filter) || supplier.includes(filter) || user.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

});

