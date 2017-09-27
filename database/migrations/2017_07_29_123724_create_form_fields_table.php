<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_fields', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_section_id')
                  ->unsigned();
            $table->integer('form_field_type_id')
                  ->unsigned();
            $table->string('form_label');
            $table->string('object_key');
            $table->string('intro_text')
                  ->nullable();
            $table->string('help_text')
                  ->nullable();
            $table->string('placeholder')
                  ->nullable();
            $table->integer('form_placement_order');
            $table->boolean('is_required');
            $table->boolean('is_active')
                  ->default(true);
            $table->boolean('is_searchable');                  
            $table->boolean('is_full_width')
                  ->default(false);
            $table->boolean('is_inline')
                  ->default(false);                  
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_section_id')
                  ->references('id')
                  ->on('form_sections')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('form_field_type_id')
                  ->references('id')
                  ->on('form_field_types')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique(['form_section_id', 'form_label']);
            $table->unique(['form_section_id', 'object_key']);
            $table->unique(['form_section_id', 'form_placement_order']);          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_fields');
    }
}
