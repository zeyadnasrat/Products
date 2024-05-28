<?php

namespace App\Services;

use Database\Config;
use Database\ProductFactory;
use PDOException;

// Load necessary classes
require_once __DIR__ . '/../../../database/Config.php';
require_once __DIR__ . '/../../../database/ProductFactory.php';

class SaveProductService
{
    private $pdo;

    public function __construct()
    {
        $config = new Config();
        $this->pdo = $config->getConnection();
    }

    public function saveProduct(array $postData): void
    {
        // Retrieve form data
        $sku = $postData['sku'] ?? '';
        $name = $postData['name'] ?? '';
        $price = $postData['price'] ?? '';
        $type = $postData['productType'] ?? '';

        // Additional attributes based on the product type
        $attributes = [];
        foreach ($postData as $key => $value) {
            if (in_array($key, ['size', 'weight', 'height', 'width', 'length'])) {
                $attributes[$key] = floatval($value);
            }
        }

        try {
            // Create product object using factory method
            $product = ProductFactory::createProduct($type, $sku, $name, $price, $attributes);

            // Insert new product into products table
            $stmt = $this->pdo->prepare("INSERT INTO products (sku, name, price) VALUES (?, ?, ?)");
            $stmt->execute([$product->getSku(), $product->getName(), $product->getPrice()]);

            // Insert new product into the corresponding product type table
            $productId = $this->pdo->lastInsertId();
            $typeSpecificAttributes = $product->getTypeSpecificAttributesForInsert();
            $columns = implode(', ', array_keys($typeSpecificAttributes));
            $values = implode(',', array_fill(0, count($typeSpecificAttributes), '?'));
            $productType = strtolower($type);

            $stmt = $this->pdo->prepare("INSERT INTO {$productType}_products (product_id, $columns) VALUES ($productId, $values)");
            $stmt->execute(array_values($typeSpecificAttributes));

            echo "Product saved successfully in the specific products table.";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
