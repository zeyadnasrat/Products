<?php

namespace App\Models;

use App\Models\Product;

class Furniture extends Product
{
    protected float $height;
    protected float $width;
    protected float $length;

    public function __construct(string $sku, string $name, float $price, array $specialAttributes)
    {
        parent::__construct($sku, $name, $price);
        $this->setHeight((float) $specialAttributes['height']);
        $this->setWidth((float) $specialAttributes['width']);
        $this->setLength((float) $specialAttributes['length']);
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function setLength(float $length): void
    {
        $this->length = $length;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function getTypeSpecificAttributesForInsert(): array
    {
        return [
            'height' => $this->getHeight(),
            'width' => $this->getWidth(),
            'length' => $this->getLength(),
        ];
    }

    public function getTypeSpecificAttributesForDisplay(): string
    {
        return "Dimensions: {$this->getHeight()} x {$this->getWidth()} x {$this->getLength()}";
    }
}
