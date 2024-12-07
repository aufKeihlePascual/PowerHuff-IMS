$(document).ready(function() {

    /****************  SHOW/HIDE PASSWORD  ****************/
    $(window).on('load', function() {
        // Ensure the elements are available before adding event listener
        const togglePassword = $("#togglePassword");
        const passwordInput = $("#password");
    
        // Check if both elements exist
        if (togglePassword.length && passwordInput.length) {
            togglePassword.on("click", function() {
                // Toggle the type attribute
                const type = passwordInput.attr("type") === "password" ? "text" : "password";
                passwordInput.attr("type", type);
    
                // Toggle the icon
                $(this).toggleClass("fa-eye fa-eye-slash");
            });
        } else {
            console.error("Password input or toggle button not found.");
        }
    });

    /****************  SUBMENU TOGGLE  ****************/
    $(".sub-menu").hide();

    // Check if Products submenu should be open when the page is loaded
    function checkSubmenuVisibility() {
        // If on the Products, Categories, or Product Items pages, open the Products submenu
        if (window.location.pathname === '/dashboard/products' || 
            window.location.pathname === '/dashboard/categories' || 
            window.location.pathname === '/dashboard/product-items') {
            const productMenu = $('[data-content="products"]');
            productMenu.siblings('.sub-menu').slideDown(); // Show submenu
            productMenu.parent().addClass('active'); // Mark menu item as active
        }
    }

    // Call the function on page load to ensure submenu visibility
    checkSubmenuVisibility();

    // Toggle submenu when clicking on any menu item
    $(".sidebar .menu li").click(function(event) {
        const submenu = $(this).children('.sub-menu'); // Get the submenu of the clicked item

        // If the clicked item has a submenu
        if (submenu.length) {
            event.preventDefault(); // Prevent default link behavior for submenu items

            // Check if the submenu is already visible
            if (submenu.is(":visible")) {
                submenu.slideUp(); // Hide the submenu if it's open
                $(this).removeClass("active"); // Remove active class
            } else {
                // Hide any open submenus and remove active classes
                $(".sub-menu").slideUp();
                $(".sidebar .menu li").removeClass("active");

                // Show the clicked submenu and add active class
                submenu.stop(true, true).slideDown();
                $(this).addClass("active");
            }
        } else {
            // If it's a regular link, navigate to the link
            window.location.href = $(this).find('a').attr('href');
        }
    });

    // Optional: Close submenu when clicking outside the sidebar
    $(document).click(function(event) {
        if (!$(event.target).closest('.sidebar').length) {
            $(".sub-menu").slideUp(); // Slide up all submenus if clicked outside
            $(".sidebar .menu li").removeClass("active"); // Remove active class
        }
    });

    /****************  USER MANAGEMENT DELETE  ****************/
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

