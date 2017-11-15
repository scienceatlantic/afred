<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNumberValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('number_values', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_entry_id')
                  ->unsigned();
            $table->integer('form_field_id')
                  ->unsigned();
            $table->integer('section_repeat_index')
                  ->unsigned();                  
            $table->double('value');
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_entry_id')
                  ->references('id')
                  ->on('form_entries')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('form_field_id')
                  ->references('id')
                  ->on('form_fields')
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
        Schema::dropIfExists('number_values');
    }
}
