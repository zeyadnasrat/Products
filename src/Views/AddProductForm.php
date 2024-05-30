<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php
        $cssPath = $action != '' ? '/../public/css/styles.css' : 'public/css/styles.css';
        echo '<link rel="stylesheet" href="' . $cssPath . '">';
    ?>
</head>
<body>
    <div class="container">
        <h1>Product Add</h1>
        <hr class="separator">

        <form id="product_form">
            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" id="sku" name="sku" class="form-control" required>
                <p id="skuError" class="text-danger"></p>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="text" id="price" name="price" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="productType">Type Switcher</label>
                <select id="productType" name="productType" class="form-control" required>
                    <option value="">Type Switcher</option>
                    <option id="DVD" value="Dvd">DVD</option>
                    <option id="Furniture" value="Furniture">Furniture</option>
                    <option id="Book" value="Book">Book</option>
                </select>
            </div>

            <div id="typeSpecificAttributes" style="display: none;">
                <!-- Attributes specific to each product type will be dynamically added here -->
            </div>

            <button type="submit" class="btn btn-primary" id="saveButton" disabled>Save</button>
            <button type="button" id="btnCancel" class="btn btn-secondary">Cancel</button>
        </form>

        <hr class="separator">
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <?php
        $jsPath = $action != '' ? '/../public/js/' : 'public/js/';
        echo '<script src="' . $jsPath . 'product-form.js"></script>';
        echo '<script src="' . $jsPath . 'save-product.js"></script>';
        echo '<script src="' . $jsPath . 'custom-validation.js"></script>';
    ?>
</body>
</html>
