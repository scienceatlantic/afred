<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId')
                  ->unsigned();
            $table->foreign('userId')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->string('name', 255);
            $table->enum('type', ['INT',
                                  'BOOLEAN',
                                  'DOUBLE',
                                  'DATE',
                                  'DATETIME',
                                  'EMAIL',
                                  'URL',
                                  'STRING',
                                  'TEXT',
                                  'JSON',
                                  'JSONTEXT']);
            $table->string('value', 255)
                  ->nullable();
            $table->dateTime('dateCreated');
            $table->dateTime('dateUpdated');
            $table->unique(['userId','name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
}
