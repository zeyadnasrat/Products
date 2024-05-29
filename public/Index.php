<?php

require_once __DIR__ . '/../src/App/Controllers/ProductController.php';

use App\Controllers\ProductController;

$productController = new ProductController();
$productsData = $productController->get();

include __DIR__ . '/../src/Views/ProductList.php';
?>
