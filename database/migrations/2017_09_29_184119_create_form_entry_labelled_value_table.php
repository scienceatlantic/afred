<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormEntryLabelledValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_entry_labelled_value', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_entry_id')
                  ->unsigned();
            $table->integer('labelled_value_id')
                  ->unsigned();
            $table->integer('section_repeat_index')
                  ->unsigned();                  
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_entry_id')
                  ->references('id')
                  ->on('form_entries')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('labelled_value_id')
                  ->references('id')
                  ->on('labelled_values')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->index('section_repeat_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_entry_labelled_value');
    }
}
