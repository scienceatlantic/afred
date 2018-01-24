<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_sections', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_id')
                  ->unsigned();
            $table->string('slug_prefix');
            $table->string('label_singular');
            $table->string('label_plural');
            $table->string('object_key');
            $table->string('intro_text')
                  ->nullable();
            $table->string('help_text')
                  ->nullable();
            $table->integer('min')
                  ->unsigned();
            $table->integer('max')
                  ->unsigned();
            $table->string('repeat_object_key')
                  ->nullable();
            $table->string('repeat_placeholder')
                  ->nullable();
            $table->integer('placement_order')
                  ->unsigned();
            $table->string('field_resource_title_object_key')
                  ->nullable();
            $table->boolean('is_resource');
            $table->boolean('is_active')
                  ->default(true);
            $table->boolean('is_searchable')
                  ->default(true);
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_id')
                  ->references('id')
                  ->on('forms')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique('slug_prefix');
            $table->unique(['form_id', 'label_singular']);
            $table->unique(['form_id', 'label_plural']);
            $table->unique(['form_id', 'object_key']);
            $table->unique(['form_id', 'placement_order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_sections');
    }
}
