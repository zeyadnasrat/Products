document.addEventListener('DOMContentLoaded', () => {
    // Define a mapping of product types to their corresponding attribute templates
    const attributeTemplates = {
        Dvd: `
            <div class="form-group">
                <label for="size">Size (MB)</label>
                <input type="text" id="size" name="size" class="form-control" required>
                <p>Please provide size in MB</p>
            </div>
        `,
        Furniture: `
            <div class="form-group">
                <label for="height">Height (CM)</label>
                <input type="text" id="height" name="height" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="width">Width (CM)</label>
                <input type="text" id="width" name="width" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="length">Length (CM)</label>
                <input type="text" id="length" name="length" class="form-control" required>
                <p>Please provide dimensions in HxWxL format</p>
            </div>    
        `,
        Book: `
            <div class="form-group">
                <label for="weight">Weight (KG)</label>
                <input type="text" id="weight" name="weight" class="form-control" required>
                <p>Please provide weight in KG</p>
            </div>
        `
    };

    // Function to handle product type change
    const handleProductTypeChange = (event) => {
        const type = event.target.value;
        const typeSpecificAttributes = document.getElementById('typeSpecificAttributes');

        // Clear previous content
        typeSpecificAttributes.innerHTML = '';

        // Check if the selected product type has a corresponding attribute template
        if (attributeTemplates[type]) {
            typeSpecificAttributes.style.display = 'block';
            typeSpecificAttributes.innerHTML = attributeTemplates[type];
        } else {
            typeSpecificAttributes.style.display = 'none';
        }

        // Trigger revalidation of newly added fields
        const customEvent = new Event('fieldsUpdated');
        document.dispatchEvent(customEvent);
    };

    document.getElementById('productType').addEventListener('change', handleProductTypeChange);
});
