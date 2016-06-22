<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('users');
        Schema::create('users', function (Blueprint $table) {
             $table->increments('id');
            $table->string('user');
            $table->string('form')->index();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('address');
            $table->tinyInteger('won')->default(0);
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
        Schema::drop('users');
    }
}
