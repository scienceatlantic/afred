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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 254)
                  ->unique();
            $table->string('password', 60);
            $table->rememberToken();
            $table->boolean('isActive')
                  ->default(true);
            $table->dateTime('dateLastLogin')
                  ->nullable();
            $table->dateTime('dateCreated');
            $table->dateTime('dateUpdated');
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
