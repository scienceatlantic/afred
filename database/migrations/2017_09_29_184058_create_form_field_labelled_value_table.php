<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldLabelledValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_field_labelled_value', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_field_id')
                  ->unsigned();
            $table->integer('labelled_value_id')
                  ->unsigned();
            $table->boolean('is_active')
                  ->default(true);
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_field_id')
                  ->references('id')
                  ->on('form_fields')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('labelled_value_id')
                  ->references('id')
                  ->on('labelled_values')
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
        Schema::dropIfExists('form_field_labelled_value');
    }
}
