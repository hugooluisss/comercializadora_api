<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function(Blueprint $table){
            $table->decimal('with_discount1')->nullable();
            $table->decimal('with_discount2')->nullable();
            $table->decimal('with_discount3')->nullable();
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
            $table->dropColumn('with_discount1');
            $table->dropColumn('with_discount2');
            $table->dropColumn('with_discount3');
        });
    }
}
