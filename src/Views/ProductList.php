<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head section with necessary meta tags and CSS links -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php
        $cssPath = $action != '' ? '/../public/css/styles.css' : 'public/css/styles.css';
        $navScriptPath = $action != '' ? '/../public/js/navigation.js' : 'public/js/navigation.js';
        $deleteScriptPath = $action != '' ? '/../public/js/delete-products.js' : 'public/js/delete-products.js';
        
        echo '<link rel="stylesheet" href="' . $cssPath . '" type="text/css">';
        echo '<script src="' . $navScriptPath . '" type="text/javascript"></script>';
        echo '<script src="' . $deleteScriptPath . '" type="text/javascript"></script>';
    ?>
    <link rel="icon" type="image/x-icon" href="data:;base64,iVBORw0KGgo=">
</head>
<body>
<div class="container">
    <div class="row mb-3">
        <div class="col d-flex justify-content-between align-items-center">
            <h1>Product List</h1>
            <div>
                <button id="add-product-btn" class="btn btn-primary">ADD</button>
                <button id="delete-product-btn" class="btn btn-danger ml-2">MASS DELETE</button>
            </div>
        </div>
    </div>

    <div class="clearfix"></div> <!-- Clear floating elements -->
    <hr class="mb-4">
    <div class="row">
        <?php foreach ($productsData as $productData): ?>
            <div class="col-md-3 mb-4">
                <?php
                // Determine product type and instantiate product
                $product = $this->create($productData);

                if (!$product) {
                    continue;  // Skip iteration if product type is unknown
                }
                ?>
                <div class="product-card border rounded p-3">
                    <div class="form-check">
                        <input class="form-check-input delete-checkbox" type="checkbox" value="<?= htmlspecialchars($product->getSku(), ENT_QUOTES, 'UTF-8') ?>" name="selected_products[]">
                    </div>
                    <div class="product-details mt-3">
                        <p><?= htmlspecialchars($product->getSku(), ENT_QUOTES, 'UTF-8') ?></p>
                        <p><?= htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8') ?></p>
                        <p><?= htmlspecialchars($product->getPrice(), ENT_QUOTES, 'UTF-8') ?> $</p>
                        <p><?= htmlspecialchars($product->getTypeSpecificAttributesForDisplay(), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <hr class="mb-4">
</div>
<footer class="bg-light py-4">
</footer>
</body>
</html>
