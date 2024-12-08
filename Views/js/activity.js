$(document).ready(function() {

    /****************  SEARCH ORDER FILTER  ****************/
    const searchInput = document.getElementById('searchBar');
    searchInput.addEventListener('input', function() {
        let filter = searchInput.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let full_name = row.cells[0].textContent.toLowerCase(); 
            let username = row.cells[1].textContent.toLowerCase();
            let role = row.cells[3].textContent.toLowerCase();
            

            if (username.includes(filter) || full_name.includes(filter) || role.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

});