<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormSectionFormSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_section_form_section', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_section_id')
                  ->unsigned();
            $table->integer('compatible_form_section_id')
                  ->unsigned();
            $table->string('resource_template');
            $table->string('search_index');
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_section_id', 'fs_id_foreign')
                  ->references('id')
                  ->on('form_sections')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('compatible_form_section_id', 'cfs_id_foreign')
                  ->references('id')
                  ->on('form_sections')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique(['form_section_id', 'compatible_form_section_id'], 'fs_id_cfs_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_section_form_section');
    }
}
