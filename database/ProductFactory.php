<?php

namespace Database;

require_once __DIR__ . '/../src/App/Models/Book.php';
require_once __DIR__ . '/../src/App/Models/Dvd.php';
require_once __DIR__ . '/../src/App/Models/Furniture.php';

use App\Models\Dvd;
use App\Models\Furniture;
use App\Models\Book;

class ProductFactory
{
    public static function createProduct($type, $sku, $name, $price, $attributes)
    {
        // Map product types to their respective classes
        $classMap = [
            'Dvd' => Dvd::class,
            'Furniture' => Furniture::class,
            'Book' => Book::class,
        ];

        // Check if the provided type exists in the class map
        if (isset($classMap[$type])) {
            $className = $classMap[$type];
            return new $className($sku, $name, $price, $attributes);
        } else {
            throw new \InvalidArgumentException("Invalid product type: $type");
        }
    }
}
