<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings_text', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userSettingId')
                  ->unsigned()
                  ->unique();
            $table->foreign('userSettingId')
                  ->references('id')
                  ->on('user_settings')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_settings_text');
    }
}
