$(document).ready(function() {
    /****************  SEARCH FILTER ****************/
    const searchInput = document.getElementById('searchBar');
    searchInput.addEventListener('input', function() {
        let filter = searchInput.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let name = row.cells[0].textContent.toLowerCase(); 

            if (name.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none'; 
            }
        });
    });
});