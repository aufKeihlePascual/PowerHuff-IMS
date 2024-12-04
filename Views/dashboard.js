$(document).ready(function() {
    $("#logo").click(function () {
        window.location.href = "/admin-dashboard";
    });
    
    const deleteUserModal = document.getElementById('deleteUserModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const deleteUserForm = document.getElementById('deleteUserForm');
    const userIdToDelete = document.getElementById('userIdToDelete');

    // When the "Delete" button is clicked (inside the user table row)
    $('.delete-user').click(function(e) {
        e.preventDefault();

        const userId = $(this).data('user-id');

        var form = $(this).closest("form");
        form.attr("action", "/delete-user/" + userId);

        form.submit();
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
                alert('User deleted successfully!');
                location.reload();  
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });

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

    // New functionality for Products tab
    $('.menu a').on('click', function(e) {
        e.preventDefault();
        $('.menu a').removeClass('active');
        $(this).addClass('active');
        
        var content = $(this).data('content');
        if (content === 'products') {
            $('#content').show();
        } else {
            $('#content').hide();
        }
    });

    // Initial load of products if on the products page
    if ($('.menu a[data-content="products"]').hasClass('active')) {
        $('#content').show();
    }
});

