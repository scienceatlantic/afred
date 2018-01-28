<?php

use Illuminate\Support\Facades\Schema;
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
            // Columns.
            $table->increments('id');
            $table->integer('role_id')
                  ->unsigned();
            $table->string('name');
            $table->string('email')
                  ->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Foreign keys & indices.
            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
