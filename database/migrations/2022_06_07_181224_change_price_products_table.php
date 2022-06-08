<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePriceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function(Blueprint $table){
            $table->decimal('price1', 12, 2, true)->default(0)->change();
            $table->decimal('price2', 12, 2, true)->default(0)->change();
            $table->decimal('price3', 12, 2, true)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function(Blueprint $table){
            $table->decimal('price1', 12, 1, true)->default(0)->change();
            $table->decimal('price2', 12, 1, true)->default(0)->change();
            $table->decimal('price3', 12, 1, true)->default(0)->change();
        });
    }
}
