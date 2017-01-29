<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)
                  ->unique();
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
            $table->integer('minAuthRoleOnGet')
                  ->unsigned()
                  ->nullable();
            $table->foreign('minAuthRoleOnGet')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->integer('minAuthRoleOnPut')
                  ->unsigned();
            $table->foreign('minAuthRoleOnPut')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
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
        Schema::dropIfExists('settings');
    }
}
