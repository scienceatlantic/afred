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
            // Columns
            $table->increments('id');
            $table->integer('directory_id')
                  ->unsigned();
            $table->integer('language_code_id')
                  ->unsigned();
            $table->integer('wp_post_id')
                  ->unsigned()
                  ->nullable();
            $table->string('name');
            $table->string('resource_folder');
            $table->string('pagination_section_object_key');
            $table->string('pagination_field_object_key');
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('directory_id')
                  ->references('id')
                  ->on('directories')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('language_code_id')
                  ->references('id')
                  ->on('language_codes')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');            
            $table->unique(['directory_id', 'name']);
            $table->unique(['id', 'resource_folder']);
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
