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
        Schema::dropIfExists('prizes');
        Schema::create('prizes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user');
            $table->string('form');
            $table->tinyInteger('pid');
            $table->string('name');
            $table->integer('number');
            $table->decimal('chance',8,6);
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
        Schema::drop('prizes');
    }
}
