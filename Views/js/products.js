$(document).ready(function() {
    /****************  PRODUCT SEARCH FILTER ****************/
    const searchInput = document.getElementById('searchBar');
    searchInput.addEventListener('input', function() {
        let filter = searchInput.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let productName = row.cells[0].textContent.toLowerCase(); 
            let categoryName = row.cells[1].textContent.toLowerCase(); 
            let productCategory = row.cells[2].textContent.toLowerCase(); 
            let price = row.cells[4].textContent.toLowerCase(); 
            let supplierName = row.cells[7].textContent.toLowerCase();

            if (
                productName.includes(filter) ||
                categoryName.includes(filter) ||
                productCategory.includes(filter) ||
                price.includes(filter) ||
                supplierName.includes(filter)
            ) {
                row.style.display = '';
            } else {
                row.style.display = 'none'; 
            }
        });
    });
});