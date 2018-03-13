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
            $table->increments('id');

            $table->integer('form_id')
                  ->unsigned();
            $table->foreign('form_id')
                  ->references('id')
                  ->on('forms')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');                  
            
            /**
             * WordPress slug prefix for this form section.
             * 
             * E.g. 'facility' (when published, it will look like this
             * 'facility_<id>' --> 'facility_78')
             */
            $table->string('slug_prefix');
            $table->unique(['form_id', 'slug_prefix']);

            /**
             * Algolia search index this form section will update.
             * 
             * This is nullable because not all form sections are searchable
             * resources.
             */
            $table->string('search_index')
                  ->nullable();
            
            /**
             * Singular label for this form section.
             * 
             * E.g. 'facility'
             */
            $table->string('label_singular');
            $table->unique(['form_id', 'label_singular']);

            /**
             * Plural label for this form section.
             * 
             * E.g. 'facilities'
             */
            $table->string('label_plural');
            $table->unique(['form_id', 'label_plural']);

            /**
             * The object key that uniquely identifies (along with the form id)
             * this form section.
             */
            $table->string('object_key');
            $table->unique(['form_id', 'object_key']);

            /**
             * Introductory text that can be displayed on the form for this
             * particular form section.
             */
            $table->string('intro_text')
                  ->nullable();
            
            /**
             * Help text.
             */
            $table->string('help_text')
                  ->nullable();

            /**
             * The minimum ...
             */
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
            $table->unique(['form_id', 'placement_order']);

            $table->string('field_resource_title_object_key')
                  ->nullable();

            $table->boolean('is_primary_contact')
                  ->default(false);

            $table->boolean('is_editor')
                  ->default(false);
                  
            $table->boolean('is_resource');

            $table->boolean('is_active')
                  ->default(true);

            $table->timestamps();
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
