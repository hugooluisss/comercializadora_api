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
        if ($this->amount <= $this->product->limit1) return $this->product->price1;
        if ($this->amount <= $this->product->limit2) return $this->product->price2;

        return $this->product->price3;
    }

    public function getDiscount(): float | null{
        if ($this->amount <= $this->product->limit1) return $this->product->with_discount1??null;
        if ($this->amount <= $this->product->limit2) return $this->product->with_discount2??null;

        return $this->product->with_discount3??null;
    }

    public function getPriceSell(){
        $price = $this->getPrice();
        $discount = $this->getDiscount();

        return match(true){
            is_null($discount) => $price,
            $discount >= $price => $price,
            $discount < $price => $discount
        };
    }
}