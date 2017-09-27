<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldFormFieldRadioValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_field_form_field_radio_value', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_field_id')
                  ->unsigned();
            $table->integer('form_field_radio_value_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_field_id')
                  ->references('id')
                  ->on('form_fields')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('form_field_radio_value_id', 'fffrv_ffrv_id_foreign')
                  ->references('id')
                  ->on('form_field_radio_values')
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
        Schema::dropIfExists('form_field_form_field_radio_value');
    }
}
