<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoryFormEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_form_entry', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('directory_id')
                  ->unsigned();
            $table->integer('form_entry_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & columns
            $table->foreign('directory_id')
                  ->references('id')
                  ->on('directories')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('form_entry_id')
                  ->references('id')
                  ->on('resources')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique(['directory_id', 'form_entry_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directory_form_entry');
    }
}
