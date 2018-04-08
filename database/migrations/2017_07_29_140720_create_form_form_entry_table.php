<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFormEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_form_entry', function (Blueprint $table) {
            // Columns
            $table->integer('form_id')
                  ->unsigned();
            $table->integer('form_entry_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_id')
                  ->references('id')
                  ->on('forms')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('form_entry_id')
                  ->references('id')
                  ->on('form_entries')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->unique(['form_id', 'form_entry_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_form_entry');
    }
}
