$(document).ready(function() {

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

