<?php

namespace App\Services;

use Database\Config;
use Database\ProductFactory;
use PDOException;
use App\Models\Product;

require_once __DIR__ . '/../../../database/Config.php';
require_once __DIR__ . '/../../../database/ProductFactory.php';
require_once __DIR__ . '/../Models/Product.php';

class ProductService
{
    private $pdo;
    private $cacheDir;

    public function __construct()
    {
        $config = new Config();
        $this->pdo = $config->getConnection();
        $this->cacheDir = __DIR__ . '/../../../cache/';
    }

    public function get()
    {
        $cacheFile = $this->cacheDir . 'products_cache.json';
        $cacheTime = 300; // 5 minutes

        // Check if cache file exists and is still valid
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTime) {
            $productsData = json_decode(file_get_contents($cacheFile), true);
        } else {
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

            $productsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Save data to cache
            if (!is_dir($this->cacheDir)) {
                mkdir($this->cacheDir, 0777, true);
            }
            file_put_contents($cacheFile, json_encode($productsData));
        }

        return $productsData;
    }

    public function create($productData): ?Product
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

            // Delete cache file after saving new product
            $this->invalidateCache();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete(array $selectedProductSkus): void
    {
        try {
            // Prepare a parameterized DELETE query using placeholders
            $placeholders = rtrim(str_repeat('?,', count($selectedProductSkus)), ',');
            $sql = "DELETE FROM products WHERE sku IN ($placeholders)";

            // Prepare the SQL statement to delete selected products
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($selectedProductSkus);

            // Delete cache file after deleting products
            $this->invalidateCache();

        } catch (PDOException $e) {
            throw new \Exception("Error deleting products: " . $e->getMessage());
        }
    }

    public function checkSku($sku)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE sku = ?");
        $stmt->execute([$sku]);
        $count = $stmt->fetchColumn();

        if($count) {
            return 'exists ';
        } else {
            return 'unique ';
        }
    }

    private function invalidateCache()
    {
        $cacheFile = $this->cacheDir . 'products_cache.json';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
}
