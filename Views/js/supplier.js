$(document).ready(function() {
    /****************  SUPPLIER SEARCH FILTER ****************/
    const searchInput = document.getElementById('searchBar');
    searchInput.addEventListener('input', function() {
        let filter = searchInput.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let supplierName = row.cells[0].textContent.toLowerCase(); 
            let categoryName = row.cells[1].textContent.toLowerCase(); 
            let productCategory = row.cells[2].textContent.toLowerCase(); 
            let contactNumber = row.cells[3].textContent.toLowerCase();

            if (
                supplierName.includes(filter) ||
                categoryName.includes(filter) ||
                productCategory.includes(filter) ||
                contactNumber.includes(filter)
            ) {
                row.style.display = ''; 
            } else {
                row.style.display = 'none'; 
            }
        });
    });
});