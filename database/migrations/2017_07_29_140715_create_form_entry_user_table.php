<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormEntryUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_entry_user', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_entry_id')
                  ->unsigned();
            $table->integer('user_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_entry_id')
                  ->references('id')
                  ->on('form_entries')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
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
        Schema::dropIfExists('form_entry_user');
    }
}
