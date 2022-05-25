<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPricelistOrderDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_detail', function (Blueprint $table) {
            $table->decimal('price_list', 12, 2);
        });

        $items = DB::table('order_detail')->get();
        foreach($items as $item){
            DB::table('order_detail')
                ->where([
                    'order_id' => $item->order_id,
                    'product_id'=> $item->product_id
                ])
                ->update(['price_list' => $item->price]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_detail', function (Blueprint $table) {
            $table->dropColumn('price_list');
        });
    }
}
