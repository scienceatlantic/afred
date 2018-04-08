<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntryFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_fields', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('entry_section_id')
                  ->unsigned();
            $table->integer('form_field_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('entry_section_id')
                  ->references('id')
                  ->on('entry_sections')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('form_field_id')
                  ->references('id')
                  ->on('form_fields')
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
        Schema::dropIfExists('entry_fields');
    }
}
