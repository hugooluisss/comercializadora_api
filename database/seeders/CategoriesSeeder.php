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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        for($i = 0 ; $i < 5 ; $i++){
            $idCategorie = DB::table('categories')->insertGetId([
                'name' => "Category $i",
                'description' => "Description of category $i"
            ]);

            for($iProduct = 0 ; $iProduct < 5 ; $iProduct++){
                $idProduct = DB::table('products')->insertGetId([
                    'name' => "Product $iProduct",
                    'category_id' => $idCategorie,
                    'sku' => strtoupper(uniqid()),
                    'name' => "Product $iProduct - Category $i",
                    'description' => "Description of product $iProduct of category $i",
                    'image' => ''
                ]);

                DB::table('product_shop')->insert([
                    'product_id' => $idProduct,
                    'shop_id' => 1,
                    'stock' => rand(1, 100),
                    'price1' => 100,
                    'price2' => 95,
                    'price3' => 80,
                ]);
            }
        }
    }
}