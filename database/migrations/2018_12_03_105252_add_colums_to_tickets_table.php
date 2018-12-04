<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->text('first_name');
            $table->text('last_name');
            $table->text('email');
            $table->string('phone');
            $table->integer('country');
            $table->text('address');
            $table->text('zip');
            $table->text('city');
            $table->string('prof_of_identity');
            $table->string('prof_of_address');
            $table->integer('request_type');
            $table->text('request_description')->nullable();
            $table->string('from_site')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
        });
    }
}
