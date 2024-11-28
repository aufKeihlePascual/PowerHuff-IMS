$(document).ready(function() {
    $("#logo").click(function () {
        window.location.href = "/admin-dashboard";
    });

    const editUserModal = document.getElementById('editUserModal');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');

    // When the "Edit" button is clicked inside the user table
    $(document).ready(function() {
        $(document).on('click', '.edit-user', function() {
            const userId = $(this).data('user-id');
            const firstName = $(this).data('first-name');
            const lastName = $(this).data('last-name');
            const username = $(this).data('username');
            const role = $(this).data('role');
    
            // Set the modal fields with the user data
            $('#editUserId').val(userId);  // Make sure this is populated
            $('#edit_first_name').val(firstName);
            $('#edit_last_name').val(lastName);
            $('#edit_username').val(username);
            $('#edit_role').val(role);
    
            // Show the modal
            $('#editUserModal').show();
        });
    
        // Close modal logic
        $('#closeEditModalBtn').click(function() {
            $('#editUserModal').hide();
        });
    });
    
    // Close modal functionality when the close button is clicked
    closeEditModalBtn.onclick = function() {
        editUserModal.style.display = 'none';
    };

    // Close modal functionality when clicking outside the modal
    window.onclick = function(event) {
        if (event.target === editUserModal) {
            editUserModal.style.display = 'none';
        }
    };
    
    const deleteUserModal = document.getElementById('deleteUserModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const deleteUserForm = document.getElementById('deleteUserForm');
    const userIdToDelete = document.getElementById('userIdToDelete');

    // When the "Delete" button is clicked (inside the user table row)
    $('.delete-user').click(function(e) {
        e.preventDefault();

        const userId = $(this).data('user-id');
        
        // Set the user ID to the hidden input field
        userIdToDelete.value = userId;
        
        // Show the modal
        deleteUserModal.style.display = 'block';
    });

    // Close the modal when the "X" button is clicked
    closeDeleteModal.onclick = function() {
        deleteUserModal.style.display = 'none';
    };

    // Close the modal when the "Cancel" button is clicked
    cancelDeleteBtn.onclick = function() {
        deleteUserModal.style.display = 'none';
    };

    // Close the modal if the user clicks outside the modal
    window.onclick = function(event) {
        if (event.target === deleteUserModal) {
            deleteUserModal.style.display = 'none';
        }
    };

    // Handle the form submission when the "Delete" button is clicked in the modal
    deleteUserForm.onsubmit = function(e) {
        e.preventDefault();

        const userId = userIdToDelete.value;

        // Perform the deletion via AJAX
        $.ajax({
            url: '/delete-user/' + userId,
            method: 'POST',
            data: {
                user_id: userId
            },
            success: function(response) {
                location.reload();  // Reload the page to reflect changes
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });

        // Close the modal after deletion
        deleteUserModal.style.display = 'none';
    };

    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("password");

    togglePassword.addEventListener("click", function () {
        // Toggle the type attribute
        const type = passwordInput.type === "password" ? "text" : "password";
        passwordInput.type = type;

        // Toggle the icon
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });

    // Modal Toggle Functionality
    const modal = document.getElementById('addUserModal');
    const addUserBtn = document.getElementById('addUserBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');

    // Open Modal
    addUserBtn.onclick = function() {
        modal.style.display = 'block';
    }

    // Close modal
    closeModalBtn.onclick = function() {
        modal.style.display = 'none';
    }

    // Close modal if clicked outside of the modal
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }

    // $("#confirmationModal").hide(); // Initially hide the confirmation modal
    $(".user-form").submit(function(event) {
        event.preventDefault(); // Prevent form from submitting

        const form = $(this);
        $.ajax({
            url: '/create-user', // Submit the form data to create a user
            method: 'POST',
            data: form.serialize(), // Serialize form data
            success: function(response) {
                // On success, hide the add user modal and show the confirmation modal
                $("#addUserModal").hide();  // Close the "Add User" modal
                $("#confirmationModal").show(); // Show the confirmation modal
            },
            error: function(xhr, status, error) {
                alert("Error: Unable to create user.");
            }
        });
    });

    // Close confirmation modal
    $("#closeConfirmationModal").click(function() {
        $("#confirmationModal").hide();
        // Optionally, redirect to another page (e.g., user list)
        window.location.href = '/dashboard/users'; // Redirect to users list or any other page
    });

    // Close confirmation modal (when clicking "Close" button)
    $("#closeConfirmationBtn").click(function() {
        $("#confirmationModal").hide();
        // Optionally, redirect or reload
        window.location.href = '/dashboard/users'; // Redirect to users list or any other page
    });

    // Search User Filter
    const searchInput = document.getElementById('searchUser');
    searchInput.addEventListener('input', function() {
        let filter = searchInput.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let username = row.cells[1].textContent.toLowerCase(); // Assumes the username is in the second column
            let role = row.cells[4].textContent.toLowerCase();    // Assumes the role is in the third column

            if (username.includes(filter) || role.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

});
