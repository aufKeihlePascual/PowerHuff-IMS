$(document).ready(function() {
    /****************  SUPPLIER SEARCH FILTER ****************/
    const searchInput = document.getElementById('searchUser');
    searchInput.addEventListener('input', function() {
        let filter = searchInput.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            // Correct column indices based on your table structure
            let supplierName = row.cells[0].textContent.toLowerCase(); // Supplier Name is in the first column
            let categoryName = row.cells[1].textContent.toLowerCase(); // Category is in the second column
            let productCategory = row.cells[2].textContent.toLowerCase(); // Product Category is in the third column
            let contactNumber = row.cells[3].textContent.toLowerCase(); // Contact Number is in the fourth column
            let createdOn = row.cells[4].textContent.toLowerCase(); // Created On is in the fifth column

            // Filter by multiple columns
            if (
                supplierName.includes(filter) ||
                categoryName.includes(filter) ||
                productCategory.includes(filter) ||
                contactNumber.includes(filter) ||
                createdOn.includes(filter)
            ) {
                row.style.display = ''; // Show row if it matches
            } else {
                row.style.display = 'none'; // Hide row if it doesn't match
            }
        });
    });
});