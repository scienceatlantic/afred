<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->integer('roleId')->unsigned();
            $table->foreign('roleId')->references('id')->on('roles')
                ->onDelete('cascade');
            $table->integer('userId')->unsigned();
            $table->foreign('userId')->references('id')->on('users')
                ->onDelete('cascade');
            $table->dateTime('dateCreated');
            $table->dateTime('dateUpdated');
            $table->primary(['roleId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_user');
    }
}
