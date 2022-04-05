<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBritdayCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function(Blueprint $table){
            $table->date('birthday');
            $table->string('between_streets');

            $table->dropColumn('birtday');
            $table->dropColumn('between_street');

            $table->string('zip_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function(Blueprint $table){
            $table->dropColumn('birthday');
            $table->date('birtday');
            $table->dropColumn('between_streets');
            $table->string('between_street');

            $table->dropColumn('zip_code');
        });
    }
}
