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
            $table->increments('id');
            
            /**
             * User's role.
             */
            $table->integer('role_id')
                  ->unsigned();
            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            
            /**
             * WordPress user id.
             * 
             * Is nullable in case the user doesn't have an associated 
             * WordPress account. It is unique - therefore usernames cannot be
             * shared across multiple WordPress installations.
             */
            $table->integer('wp_user_id')
                  ->unsigned()
                  ->nullable();

            /**
             * WordPress user's "home".
             * 
             * This is simply to identify the WordPress installation the user
             * belongs to.
             * 
             * TODO: determine if this is still needed
             */
            $table->string('wp_home')
                  ->nullable();

            /**
             * WordPress username.
             * 
             * It is nullable in case the user doesn't have an associated
             * WordPress account. It is unique - therefore usernames cannot be
             * shared across multiple WordPress installations.
             */
            $table->string('wp_username')
                  ->unique()
                  ->nullable();
            
            /**
             * User's first name
             * 
             * Is nullable in order to be compatible with WordPress.
             */
            $table->string('first_name')
                  ->nullable();

            /**
             * User's last name
             * 
             * Is nullable in order to be compatible with WordPress.
             */                  
            $table->string('last_name')
                  ->nullable();
            
            /**
             * User's email address.
             * 
             * This is used to uniquely identify the user.
             */
            $table->string('email')
                  ->unique();
            
            /**
             * User's password.
             * 
             * Note: We do not store plaintext passwords in the database.
             */
            $table->string('password');

            // TODO: is this needed?
            $table->rememberToken();

            $table->timestamps();

            // TODO
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
