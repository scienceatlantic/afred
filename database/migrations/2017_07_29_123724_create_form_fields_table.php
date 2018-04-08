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
            $table->integer('field_type_id')
                  ->unsigned();            
            $table->string('label');
            $table->string('object_key');
            $table->integer('max_length')
                  ->unsigned()
                  ->nullable();
            $table->integer('min_value')
                  ->nullable();
            $table->integer('max_value')
                  ->nullable();                  
            $table->text('notes')
                  ->nullable();
            $table->text('help_text')
                  ->nullable();
            $table->string('placeholder')
                  ->nullable();
            $table->integer('placement_order');
            $table->string('input_pattern')
                  ->nullable();
            $table->text('tinymce_init')
                  ->nullable();
            $table->boolean('has_ilo')
                  ->default(false);
            $table->boolean('is_required');
            $table->boolean('is_active')
                  ->default(true);
            $table->boolean('is_searchable');
            $table->boolean('is_single_column')
                  ->default(false);
            $table->boolean('is_inline')
                  ->default(false);
            $table->boolean('is_split_list')
                  ->default(false);              
            $table->boolean('show_select_all')
                  ->default(false);
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_section_id')
                  ->references('id')
                  ->on('form_sections')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('field_type_id')
                  ->references('id')
                  ->on('field_types')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');                  
            $table->unique(['form_section_id', 'placement_order']);
            $table->unique(['form_section_id', 'object_key']);
            $table->unique(['form_section_id', 'label']);
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
