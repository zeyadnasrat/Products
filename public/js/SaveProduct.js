$(document).ready(function () {
    let skuInputTimer;
    
    // SKU validation AJAX request
    $('#sku').on('input', function() {
        clearTimeout(skuInputTimer); // Clear previous timer

        // Start a new timer after user stops typing for 1000 milliseconds
        skuInputTimer = setTimeout(() => {
            const sku = $('#sku').val();

            $.ajax({
                type: 'POST',
                url: 'routes/Router.php',
                data: { sku: sku },
                success: function(response) {
                    if (response === 'exists ') {
                        $('#skuError').text('SKU already in use');
                        $('#saveButton').prop('disabled', true);
                    } else {
                        $('#skuError').text('');
                        $('#saveButton').prop('disabled', false);
                    }
                },
                error: function() {
                    console.error('Error checking SKU');
                }
            });
        }, 1000); // Delay in milliseconds
    });

    $('#product_form').submit(function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Serialize form data
        const formData = $(this).serialize();

        // Send AJAX request to save product
        $.ajax({
            type: 'POST',
            url: '/src/App/Services/SaveProductService.php', // URL of the PHP script handling the database insertion
            data: formData,
            success: function () {
                window.location.href = '/routes/Router.php?action=/';
            },
            error: function () {
                alert('Error saving product.');
            }
        });
    });
});
