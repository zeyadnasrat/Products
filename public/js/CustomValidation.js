// Define the function to assign custom validation logic
const assignCustomValidation = () => {
    const form = document.getElementById('product_form');
    const requiredFields = form.querySelectorAll('[required]');

    // Validate the form on submit
    form.addEventListener('submit', function (event) {
        let isFormValid = true;

        requiredFields.forEach(field => {
            if (!field.validity.valid) {
                isFormValid = false;
                field.setCustomValidity('Please, submit required data');
                field.reportValidity(); // Trigger validation message
            }
        });

        // If the form is invalid, prevent submission
        if (!isFormValid) {
            event.preventDefault(); // Prevent form submission
        }
    });

    // Assign input and invalid event listeners to each required field
    requiredFields.forEach(field => {
        field.addEventListener('input', function () {
            this.setCustomValidity(''); // Clear custom message on input
        });

        field.addEventListener('invalid', function () {
            this.setCustomValidity('Please, submit required data');
        });
    });
};

// Assign custom validation logic on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
    assignCustomValidation();
});

// Reassign custom validation logic when fields are updated
document.addEventListener('fieldsUpdated', function () {
    assignCustomValidation();
});
