document.addEventListener('DOMContentLoaded', function() {
    var deleteProductBtn = document.getElementById('delete-product-btn');

    if (deleteProductBtn) {
        deleteProductBtn.addEventListener('click', function() {
            var selectedProducts = [];

            // Get all checked checkboxes with the class 'delete-checkbox'
            var checkboxes = document.querySelectorAll('.delete-checkbox:checked');

            // Iterate over each checked checkbox and push its value (product SKU) to the array
            checkboxes.forEach(function(checkbox) {
                selectedProducts.push(checkbox.value);
            });

            // Check if there are selected products to delete
            if (selectedProducts.length === 0) {
                alert('Please select products to delete.');
                return;
            }
            
            // Send AJAX request using jQuery
            $.ajax({
                type: 'POST',
                url: '/delete-products',
                contentType: 'application/json',
                data: JSON.stringify({ selectedProducts: selectedProducts }),
                success: function(response) {
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting products:', error);
                    alert('Error deleting products: ' + error);
                }
            });
        });
    }

    // Uncheck all checkboxes when the page is loaded
    var allCheckboxes = document.querySelectorAll('.delete-checkbox');
    allCheckboxes.forEach(function(checkbox) {
        checkbox.checked = false;
    });
});
