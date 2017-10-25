<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStringValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('string_values', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_entry_id')
                  ->unsigned();
            $table->integer('form_field_id')
                  ->unsigned();
            $table->string('value');
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_field_id')
                  ->references('id')
                  ->on('form_fields')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('form_entry_id')
                  ->references('id')
                  ->on('form_entries')
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
        Schema::dropIfExists('string_values');
    }
}
