<?php

namespace App\Models\Templates;

use App\Models\Product;

class TItem{
    public Product $product;
    public float $amount;

    public static function createWithData(int $product_id, float $amount): TItem{
        $self = new self();

        $self->product = Product::findOrFail($product_id);
        $self->amount = $amount;

        return $self;
    }

    public function getPrice(): float{
        if ($this->amount <= $this->product->limite1) return $this->product->price1;
        if ($this->amount <= $this->product->limite2) return $this->product->price2;

        return $this->product->price3;
    }
}