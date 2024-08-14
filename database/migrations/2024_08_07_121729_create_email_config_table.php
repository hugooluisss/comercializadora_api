<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_config', function (Blueprint $table) {
            $table->id();
            $table->string('host', 500);
            $table->integer('port');
            $table->string('username', 100);
            $table->string('password', 100);
            $table->string('encryption', 100);
            $table->string('fromAddress', 100);
            $table->string('fromName', 100);
            $table->text('administrators');
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
        Schema::dropIfExists('email_config');
    }
}