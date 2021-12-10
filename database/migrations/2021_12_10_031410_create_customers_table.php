<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string('firstname');
            $table->string('lastname');
            $table->enum('gender', ['woman', 'man'])->default('man');
            $table->date('birtday');
            $table->string('phone_movil');
            $table->string('phone_ofi');
            $table->string('address');
            $table->string('address_2');
            $table->string('between_street');
            $table->string('suburb');
            $table->string('municipality');
            $table->string('state');
            $table->boolean('confirmed');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
