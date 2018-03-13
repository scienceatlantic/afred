<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('directory_id')
                  ->unsigned();
            $table->foreign('directory_id')
                  ->references('id')
                  ->on('directories')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->integer('language_code_id')
                  ->unsigned();
            $table->foreign('language_code_id')
                  ->references('id')
                  ->on('language_codes')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            /**
             * WordPress post ID of the page that contains this form.
             */
            $table->integer('wp_post_id')
                  ->unsigned()
                  ->nullable();
            
            /**
             * Name of the form.
             * 
             * E.g. 'Facilities'
             */
            $table->string('name');
            $table->unique(['directory_id', 'name']);

            /**
             * Name of the folder that contains all the resources (blade 
             * templates) related to this form.
             * 
             * E.g. If the folder is set to 'facilities', and it belongs to a
             * directory with a resource folder called 'afred' the API will look
             * for its related form entry email resources in:
             * resources/views/emails/afred/form-entries/facilities/...
             *                          ^                    ^
             *                      directory              form
             */
            $table->string('resource_folder')
                  ->unique();

            /**
             * Form section's object key for the form section that contains the
             * field used to title a form entry.
             */
            $table->string('pagination_section_object_key');
            
            /**
             * Form field's object key for the form field that contains the
             * value that will be used to title a form entry.
             */
            $table->string('pagination_field_object_key');

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
        Schema::dropIfExists('forms');
    }
}
