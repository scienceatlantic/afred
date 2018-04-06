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
            // Columns
            $table->increments('id');
            $table->integer('role_id')
                  ->unsigned();
            $table->integer('wp_user_id')
                  ->unsigned()
                  ->nullable();
            $table->string('wp_home')
                  ->nullable();
            $table->string('wp_username')
                  ->nullable();
            $table->string('first_name')
                  ->nullable();
            $table->string('last_name')
                  ->nullable();
            $table->string('email');
            $table->string('password');
            $table->boolean('is_active')
                  ->default(true);
            $table->rememberToken();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique('wp_username');
            $table->unique('email');
            $table->unique(['wp_user_id', 'wp_home']);
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
