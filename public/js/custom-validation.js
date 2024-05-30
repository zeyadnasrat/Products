// Define the function to assign custom validation logic
const assignCustomValidation = () => {
    const form = document.getElementById('product_form');
    const requiredFields = form.querySelectorAll('[required]');
    const numericFields = ['price', 'size', 'weight', 'height', 'width', 'length'];

    // Validate the form on submit
    form.addEventListener('submit', function (event) {
        let isFormValid = true;

        requiredFields.forEach(field => {
            if (!field.validity.valid || (numericFields.includes(field.id) && isNaN(field.value))) {
                isFormValid = false;
                if (field.value === '') {
                    field.setCustomValidity('Please, submit required data');
                } else if (numericFields.includes(field.id) && isNaN(field.value)) {
                    field.setCustomValidity('Please, provide the data of indicated type');
                }
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
            if (numericFields.includes(this.id) && isNaN(this.value)) {
                this.setCustomValidity('Please, provide the data of indicated type');
            }
        });

        field.addEventListener('invalid', function () {
            if (this.value === '') {
                this.setCustomValidity('Please, submit required data');
            } else if (numericFields.includes(this.id) && isNaN(this.value)) {
                this.setCustomValidity('Please, provide the data of indicated type');
            }
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
