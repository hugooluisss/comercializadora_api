<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'image',
        'stock',
        'brand',
        'with_discount1',
        'with_discount2',
        'with_discount3',
        'price1',
        'price2',
        'price3',
    ];

    protected $cast = [
        'created_at',
        'updated_at'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function customers(){
        return $this->belongsToMany(Customer::class, 'favorites')->withTimestamps();
    }
}
