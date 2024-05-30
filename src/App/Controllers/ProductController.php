<?php

namespace App\Controllers;

use App\Services\ProductService;

require_once __DIR__ . '/../Services/ProductService.php';

class ProductController
{
    protected $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function showProductList($action = '')
    {
        $productsData =  $this->productService->get();

        $this->renderView('ProductList', ['productsData' => $productsData, 'action' => $action]);
    }

    public function showAddProductForm($action = '')
    {
        $this->renderView('AddProductForm', ['action' => $action]);
    }

    private function renderView($viewName, $data = [])
    {
        extract($data);
        include __DIR__ . "/../../../src/Views/{$viewName}.php";
    }
    
    public function create($productData)
    {
        $product = $this->productService->create($productData);
        return $product;
    }

    public function saveProduct(array $postData)
    {
        $this->productService->saveProduct($postData);
    }

    public function delete()
    {  
        // Get the raw POST data (JSON payload)
        $input = file_get_contents('php://input');

        // Decode the JSON payload into an associative array
        $data = json_decode($input, true);

        if (isset($data['selectedProducts'])) {
            $selectedProductSkus = $data['selectedProducts']; 
  
            try {
                $this->productService->delete($selectedProductSkus);
            } catch (PDOException $e) {
                echo "Error deleting products: " . $e->getMessage();
            }
        } else {
            // 'selectedProducts' key not found in the decoded data
            http_response_code(400);
            echo json_encode(['error' => 'Invalid data format']);
        }
    }

    public function checkSku($sku)
    {
        return $this->productService->checkSku($sku);
    }
}
