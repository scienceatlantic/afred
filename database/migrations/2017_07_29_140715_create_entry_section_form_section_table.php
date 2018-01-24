<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntrySectionFormSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_section_form_section', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_section_id')
                  ->unsigned();
            $table->integer('entry_section_id')
                  ->unsigned();
            $table->integer('wp_post_id')
                  ->unsigned()
                  ->nullable();
            $table->string('wp_slug')
                  ->nullable();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_section_id')
                  ->references('id')
                  ->on('form_sections')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('entry_section_id')
                  ->references('id')
                  ->on('entry_sections')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique(['form_section_id', 'entry_section_id'], 'fs_id_es_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entry_section_form_section');
    }
}
