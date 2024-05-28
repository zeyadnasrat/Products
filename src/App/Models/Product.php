<?php

namespace App\Models;

abstract class Product
{
    protected int $id;
    protected string $sku;
    protected string $name;
    protected float $price;
    
    public function __construct(string $sku, string $name, float $price)
    {
        $this->setSku($sku);
        $this->setName($name);
        $this->setPrice($price);
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
    
    abstract public function getTypeSpecificAttributesForInsert(): array;

    abstract public function getTypeSpecificAttributesForDisplay(): string;
}
