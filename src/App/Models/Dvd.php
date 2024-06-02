<?php

namespace App\Models;

use App\Models\Product;

class Dvd extends Product
{
    protected float $size;
    
    public function __construct(string $sku, string $name, float $price, array $specialAttributes)
    {
        parent::__construct($sku, $name, $price);
        $this->setSize((float) $specialAttributes['size']);
    }

    public function setSize(float $size): void
    {
        $this->size = $size;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function getTypeSpecificAttributesForInsert(): array
    {
        return ['size' => $this->getSize()];
    }
    
    public function getTypeSpecificAttributesForDisplay(): string
    {
        return "Size: {$this->getSize()} MB";
    }
}
