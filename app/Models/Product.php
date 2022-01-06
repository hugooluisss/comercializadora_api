<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'image'
    ];

    protected $cast = [
        'created_at',
        'updated_at'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function shops(){
        return $this->belongsToMany(Shop::class)->as('stock')->withPivot('stock', 'price1', 'price2', 'price3');
    }
}