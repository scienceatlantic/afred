<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('entry_section_id')
                  ->unsigned();
            $table->integer('form_section_id')
                  ->unsigned();
            $table->integer('published_entry_section_id')
                  ->unsigned();
            $table->integer('wp_post_id')
                  ->unsigned()
                  ->nullable();
            $table->string('wp_slug')
                  ->nullable();
            $table->boolean('is_in_wp')
                  ->default(false);
            $table->boolean('is_in_algolia')
                  ->default(false);
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('entry_section_id')
                  ->references('id')
                  ->on('entry_sections')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('form_section_id')
                  ->references('id')
                  ->on('form_sections')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique(['entry_section_id', 'form_section_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listings');
    }
}
