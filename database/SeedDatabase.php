<?php

namespace Database;

use PDOException;

class SeedDatabase
{
    private $pdo;

    public function __construct()
    {
        $config = new Config();
        $this->pdo = $config->getConnection();
    }

    public function seed()
    {
        try {
            // Insert sample data into products table
            $this->pdo->exec("
                INSERT INTO products (sku, name, price) VALUES
                ('JVC200123', 'Acme DISC', '1.00'),
                ('JVC200124', 'Acme DISC', '1.00'),
                ('JVC200125', 'Acme DISC', '1.00'),
                ('JVC200126', 'Acme DISC', '1.00'),
                ('GGWP0006', 'War and Peace', '20.00'),
                ('GGWP0007', 'War and Peace', '20.00'),
                ('GGWP0008', 'War and Peace', '20.00'),
                ('GGWP0009', 'War and Peace', '20.00'),
                ('TR120555', 'Chair', '40.00'),
                ('TR120556', 'Chair', '40.00'),
                ('TR120557', 'Chair', '40.00'),
                ('TR120558', 'Chair', '40.00');
            ");
            
            // Insert sample data into dvd_products table
            $this->pdo->exec("
                INSERT INTO dvd_products (product_id, size)
                SELECT id, 700 FROM products WHERE sku IN ('JVC200123', 'JVC200124', 'JVC200125', 'JVC200126');
            ");

            // Insert sample data into book_products table
            $this->pdo->exec("
                INSERT INTO book_products (product_id, weight)
                SELECT id, 2 FROM products WHERE sku IN ('GGWP0006', 'GGWP0007', 'GGWP0008', 'GGWP0009');
            ");

            // Insert sample data into furniture_products table
            $this->pdo->exec("
                INSERT INTO furniture_products (product_id, height, width, length)
                SELECT id, 24, 45, 15 FROM products WHERE sku IN ('TR120555', 'TR120556', 'TR120557', 'TR120558');
            ");

            echo "Data seeding completed successfully.";
        } catch (PDOException $e) {
            die("Data seeding failed: " . $e->getMessage());
        }
    }
}

// Instantiate the class and seed the database
$seedDatabase = new SeedDatabase();
$seedDatabase->seed();
