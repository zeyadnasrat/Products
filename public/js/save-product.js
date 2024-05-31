$(document).ready(function () {
    $('#sku').on('input', function() {
        skuValidator.validate();
    });

    $('#product_form').submit(function (event) {
        event.preventDefault(); // Prevent the default form submission

        // Serialize form data
        const formData = $(this).serialize();

        // Send AJAX request to save product
        $.ajax({
            type: 'POST',
            url: '/save-product', // URL of the PHP script handling the database insertion
            data: formData,
            success: function () {
                window.location.href = '/';
            },
            error: function () {
                alert('Error saving product.');
            }
        });
    });
});
