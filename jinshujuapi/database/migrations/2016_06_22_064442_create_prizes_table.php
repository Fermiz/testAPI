<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('prizes');
        Schema::create('prizes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->index();
            $table->string('form');
            $table->string('name')->unique();
            $table->integer('number');
            $table->float('chance');
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
        Schema::drop('tasks');
    }
}