<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();

        $current = date('Y-m-d H:i:s');

        $CategoryInit = DB::table('categories')->insertGetId([
            'name' => "Categoría padre",
            'category_parent_id' => 0,
            'created_at' => $current,
            'updated_at' => $current
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        for($i = 0 ; $i < 5 ; $i++){
            $idCategory = DB::table('categories')->insertGetId([
                'name' => "Category $i",
                'description' => "Description of category $i",
                'category_parent_id' => $CategoryInit,
                'created_at' => $current,
                'updated_at' => $current
            ]);

            for($x = 0 ; $x < 3 ; $x++){
                $idChild = DB::table('categories')->insertGetId([
                    'name' => "Subcategory $x of Category $i with id $idCategory",
                    'description' => "Description of category $i",
                    'category_parent_id' => $idCategory,
                    'created_at' => $current,
                    'updated_at' => $current
                ]);

                for($iProduct = 0 ; $iProduct < 5 ; $iProduct++){
                    $idProduct = DB::table('products')->insertGetId([
                        'name' => "Product $iProduct",
                        'category_id' => $idChild,
                        'sku' => strtoupper(uniqid()),
                        'name' => "Product $iProduct - Category $x of $idChild",
                        'description' => "Product $iProduct - Category $x of $idChild",
                        'image' => '',
                        'stock' => 10,
                        'created_at' => $current,
                        'updated_at' => $current
                    ]);
                }
            }
        }
    }
}
