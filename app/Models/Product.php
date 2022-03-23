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
        'stock'
    ];

    protected $cast = [
        'created_at',
        'updated_at'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    // public function shops(){
    //     return $this->belongsToMany(Shop::class)->as('stock')->withPivot('stock', 'price1', 'price2', 'price3');
    // }
}
