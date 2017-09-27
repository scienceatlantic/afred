<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormEntryFormFieldDropdownValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_entry_form_field_dropdown_value', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_entry_id')
                  ->unsigned();
            
            $table->integer('form_field_dropdown_value_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_entry_id')
                  ->references('id')
                  ->on('form_entries')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('form_field_dropdown_value_id', 'feffdv_ffdv_id_foreign')
                  ->references('id')
                  ->on('form_field_dropdown_values')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_entry_form_field_dropdown_value');
    }
}
