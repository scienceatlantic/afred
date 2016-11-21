<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_text', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('settingId')
                  ->unsigned()
                  ->unique();
            $table->foreign('settingId')
                  ->references('id')
                  ->on('settings')
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
        Schema::dropIfExists('settings_text');
    }
}
