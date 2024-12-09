$(document).ready(function() {

    /****************  SHOW/HIDE PASSWORD  ****************/
    $(window).on('load', function() {
        const togglePassword = $("#togglePassword");
        const passwordInput = $("#password");
    
        if (togglePassword.length && passwordInput.length) {
            togglePassword.on("click", function() {
                const type = passwordInput.attr("type") === "password" ? "text" : "password";
                passwordInput.attr("type", type);
    
                $(this).toggleClass("fa-eye fa-eye-slash");
            });
        } else {
            console.error("Password input or toggle button not found.");
        }
    });
    
    /****************  USER MANAGEMENT DELETE  ****************/
    const deleteUserModal = document.getElementById('deleteUserModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const deleteUserForm = document.getElementById('deleteUserForm');
    const userIdToDelete = document.getElementById('userIdToDelete');

    $('.delete-user').click(function(e) {
        e.preventDefault();

        const userId = $(this).data('user-id');
        
        userIdToDelete.value = userId;
        
        deleteUserModal.style.display = 'block';
    });

    closeDeleteModal.onclick = function() {
        deleteUserModal.style.display = 'none';
    };

    cancelDeleteBtn.onclick = function() {
        deleteUserModal.style.display = 'none';
    };

    window.onclick = function(event) {
        if (event.target === deleteUserModal) {
            deleteUserModal.style.display = 'none';
        }
    };

    deleteUserForm.onsubmit = function(e) {
        e.preventDefault();

        const userId = userIdToDelete.value;

        $.ajax({
            url: '/delete-user/' + userId,
            method: 'POST',
            data: {
                user_id: userId
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });

        deleteUserModal.style.display = 'none';
    };

    /****************  USER MANAGEMENT ADD ****************/
    const modal = document.getElementById('addUserModal');
    const addUserBtn = document.getElementById('addUserBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');

    addUserBtn.onclick = function() {
        modal.style.display = 'block';
    }

    closeModalBtn.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }

    // $("#confirmationModal").hide(); // Initially hide the confirmation modal
    $(".user-form").submit(function(event) {
        event.preventDefault();

        const form = $(this);
        $.ajax({
            url: '/create-user',
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

    $("#closeConfirmationBtn").click(function() {
        $("#confirmationModal").hide();
        window.location.href = '/dashboard/users';
    });

    /****************  SEARCH USER FILTER  ****************/
    const searchInput = document.getElementById('searchBar');
    searchInput.addEventListener('input', function() {
        let filter = searchInput.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let username = row.cells[0].textContent.toLowerCase(); 
            let first_name = row.cells[1].textContent.toLowerCase();
            let last_name = row.cells[2].textContent.toLowerCase();
            let role = row.cells[3].textContent.toLowerCase();
            

            if (username.includes(filter) || role.includes(filter) || first_name.includes(filter) || last_name.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

});

