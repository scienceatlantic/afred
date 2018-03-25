<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');

            /**
             * Name of role.
             * 
             * E.g. 'Administrator', 'Editor', etc.
             */
            $table->string('name');

            /**
             * Permission level.
             * 
             * Higher value = higher permission level.
             * Useful for scenarios where we have to determine if a user has "at
             * least" a certain permission level.
             */
            $table->integer('level')
                  ->unsigned();
                              
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
        Schema::dropIfExists('roles');
    }
}
