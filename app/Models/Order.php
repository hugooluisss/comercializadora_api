<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function status(){
        return $this->belongsTo(OrderStatus::class);
    }

    public function items(){
        return $this->belongsToMany(Product::class, 'order_detail')->withTimestamps()->withPivot([
            'amount',
            'price',
            'price_list'
        ]);
    }
}
