<?php

namespace App\Controllers;

require_once __DIR__ . '/../../../database/Config.php';
require_once __DIR__ . '/../../../database/ProductFactory.php';

use Database\Config;
use Database\ProductFactory;

class ProductController
{
    protected $pdo;

    public function __construct()
    {
        $config = new Config();
        $this->pdo = $config->getConnection();
    }
    
    public function get()
    {
        $stmt = $this->pdo->query("
            SELECT p.*,
                b.weight AS weight,
                d.size AS size,
                f.height AS height,
                f.width AS width,
                f.length AS length
            FROM products p
            LEFT JOIN book_products b ON p.id = b.product_id
            LEFT JOIN dvd_products d ON p.id = d.product_id
            LEFT JOIN furniture_products f ON p.id = f.product_id
            ORDER BY p.id
        ");

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create($productData)
    {
        $productType = null;

        // Define special attributes to class mapping
        $specialAttributesClassMapping = [
            'weight' => "Book",
            'size' => "Dvd",
            'height' => "Furniture",
            'width' => "Furniture",
            'length' => "Furniture",
        ];

        // Filter non-null attributes from product data
        $filteredProductData = array_filter($productData, function ($value) {
            return $value !== null;
        });

        $productSpecialAttributes = array_intersect_key($filteredProductData, $specialAttributesClassMapping);

        if (!empty($productSpecialAttributes)) {
            $firstAttribute = array_key_first($productSpecialAttributes);
            $productType = $specialAttributesClassMapping[$firstAttribute];
        }

        if (!$productType) {
            return null; 
        }

        // Instantiate appropriate product class object using the data from the database row
        $product = ProductFactory::createProduct(
            $productType,
            $productData['sku'],
            $productData['name'],
            $productData['price'],
            $productSpecialAttributes,
        );

        return $product;
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
                // Prepare a parameterized DELETE query using placeholders
                $placeholders = rtrim(str_repeat('?,', count($selectedProductSkus)), ',');
                $sql = "DELETE FROM products WHERE sku IN ($placeholders)";

                // Prepare the SQL statement to delete selected products
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($selectedProductSkus);

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
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE sku = ?");
        $stmt->execute([$sku]);
        $count = $stmt->fetchColumn();

        if($count) {
            echo 'exists ';
        } else {
            echo 'unique ';
        }
    }
}
