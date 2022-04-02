<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'category_parent_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function category_parent(){
        return $this->hasOne(Category::class, 'id', 'category_parent_id');
    }

    public function subcategories(){
        return $this->hasMany(Category::class, 'category_parent_id', 'id');
    }

    public static function getParents(): array{
        $records = self::where(['category_parent_id' => 1])->get();

        $categories = [];

        foreach($records as $category){
            $categories[$category->id] = $category;
        }

        return $categories;
    }
}
