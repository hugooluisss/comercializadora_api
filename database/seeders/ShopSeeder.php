<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("shops")->delete();
        DB::table('shops')->insert([
            'id' => 1,
            'name' => "Shop Default",
            'address' => '',
            'phone' => '',
            'folio' => 'D'
        ]);
    }
}
