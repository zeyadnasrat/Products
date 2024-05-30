const skuValidator = (function () {
    let skuInputTimer;

    return {
        validate: function() {
            clearTimeout(skuInputTimer); // Clear previous timer

            // Start a new timer after user stops typing for 1000 milliseconds
            skuInputTimer = setTimeout(() => {
                const sku = $('#sku').val();

                $.ajax({
                    type: 'POST',
                    url: '/check-sku',
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
        }
    };
})();
