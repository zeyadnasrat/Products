<?php

namespace Database;

use PDOException;
use Database\Config;

require __DIR__ . '/../vendor/autoload.php';

class InitDatabase
{
    private $pdo;

    public function __construct()
    {
        $config = new Config();
        $this->pdo = $config->getConnection();
    }

    public function createTables()
    {
        try {
            // SQL statements for table creation
            $sqlCreateProducts = "
                CREATE TABLE IF NOT EXISTS products (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    sku VARCHAR(255) UNIQUE,
                    name VARCHAR(255),
                    price DECIMAL(10,2)
                );
            ";

            $sqlCreateDVDProducts = "
                CREATE TABLE IF NOT EXISTS dvd_products (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    product_id INT,
                    size DECIMAL(10,2),
                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
                );
            ";

            $sqlCreateBookProducts = "
                CREATE TABLE IF NOT EXISTS book_products (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    product_id INT,
                    weight DECIMAL(10,2),
                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
                );
            ";

            $sqlCreateFurnitureProducts = "
                CREATE TABLE IF NOT EXISTS furniture_products (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    product_id INT,
                    height DECIMAL(10,2),
                    width DECIMAL(10,2),
                    length DECIMAL(10,2),
                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
                );
            ";

            // Execute SQL statements to create tables
            $this->pdo->exec($sqlCreateProducts);
            $this->pdo->exec($sqlCreateDVDProducts);
            $this->pdo->exec($sqlCreateBookProducts);
            $this->pdo->exec($sqlCreateFurnitureProducts);

            echo "Database tables creation completed successfully.";
        } catch (PDOException $e) {
            die("Database tables creation failed: " . $e->getMessage());
        }
    }
}

// Instantiate the class and create the tables
$initDatabase = new InitDatabase();
$initDatabase->createTables();
