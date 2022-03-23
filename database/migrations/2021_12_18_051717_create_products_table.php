<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('sku');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->decimal('price1', 12, 1, true)->default(0);
            $table->decimal('price2', 12, 1, true)->default(0);
            $table->decimal('price3', 12, 1, true)->default(0);
            $table->integer('limit1')->default(0);
            $table->integer('limit2')->default(10);
            $table->integer('limit3')->default(15);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
