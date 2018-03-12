<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchFacetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_facets', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('search_section_id')
                  ->unsigned();
            $table->integer('search_facet_operator_id')
                  ->unsigned();
            $table->string('label');
            $table->string('algolia_object_key');
            $table->integer('placement_order')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('search_section_id')
                  ->references('id')
                  ->on('search_sections')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('search_facet_operator_id')
                  ->references('id')
                  ->on('search_facet_operators')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');           
            $table->unique(['search_section_id', 'placement_order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_facets');
    }
}
