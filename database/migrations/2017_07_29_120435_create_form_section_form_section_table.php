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
            $table->integer('root_form_section_id')
                  ->unsigned();
            $table->integer('target_form_section_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('root_form_section_id', 'rfs_id_foreign')
                  ->references('id')
                  ->on('form_sections')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('target_form_section_id', 'tfs_id_foreign')
                  ->references('id')
                  ->on('form_sections')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->unique(
                ['root_form_section_id', 'target_form_section_id'],
                'rfs_id_tfs_id_unique'
            );
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
