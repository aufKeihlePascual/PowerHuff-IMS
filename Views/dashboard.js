$(document).ready(function () {
    // Sidebar link click handler for dynamic content loading
    $(".sidebar a").click(function (e) {
        e.preventDefault();
        var content = $(this).data("content");

        if (content === "dashboard") {
            // Replace content with server-rendered dashboard content
            $(".main-content .content").html(DASHBOARD_CONTENT);
        } else {
            // Load other content dynamically
            $.get("/dashboard/content", { content: content }, function (response) {
                $(".main-content .content").html(response);
            });
        }

        // Highlights clicked link
        $(".sidebar a").removeClass("active");
        $(this).addClass("active");
    });

    // Handler for logo click
    $("#logo").click(function () {
        $(".main-content .content").html(DASHBOARD_CONTENT);
        $(".sidebar a").removeClass("active"); // Highlight the "Dashboard" link
        $(".sidebar a[data-content='dashboard']").addClass("active");
    });

    // Triggers click for the "Dashboard" link to show its content by default
    $(".sidebar a[data-content='dashboard']").trigger("click");
});

// For when a card is clicked
document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('click', () => {
        alert('Card clicked!');
    });
});