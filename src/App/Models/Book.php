<?php

namespace App\Models;

use App\Models\Product;

require_once 'Product.php';

class Book extends Product
{
    protected float $weight;

    public function __construct(string $sku, string $name, float $price, array $specialAttributes)
    {
        parent::__construct($sku, $name, $price);
        $this->setWeight((float) $specialAttributes['weight']);
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getTypeSpecificAttributesForInsert(): array
    {
        return ['weight' => $this->getWeight()];
    }

    public function getTypeSpecificAttributesForDisplay(): string
    {
        return "Weight: {$this->getWeight()} KG";
    }
}
