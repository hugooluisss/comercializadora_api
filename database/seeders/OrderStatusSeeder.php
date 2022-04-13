<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_status')->delete();

        $allStatus = [
            [
                'id' => 1,
                'name' => 'Registrada',
                'color' => 'blue'
            ],
            [
                'id' => 2,
                'name' => 'En proceso',
                'color' => 'yellow'
            ],
            [
                'id' => 3,
                'name' => 'En camino',
                'color' => 'blue'
            ],
            [
                'id' => 4,
                'name' => 'Entregado',
                'color' => 'green'
            ],
            [
                'id' => 5,
                'name' => "Problemas para la entrega",
                'color' => 'orange'
            ],
            [
                'id' => 6,
                'name' => 'Cancelado',
                'color' => 'red'
            ]
        ];

        foreach($allStatus as $status){
            DB::table('order_status')->insertGetId($status);
        }
    }
}
