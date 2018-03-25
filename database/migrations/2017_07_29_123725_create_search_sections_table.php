<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_sections', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_section_id')
                  ->unsigned();
            $table->string('label');
            $table->text('result_html');
            $table->string('input_placeholder')
                  ->nullable();
            $table->integer('placement_order')
                  ->unsigned();
            $table->boolean('is_default')
                  ->default(false);
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_section_id')
                  ->references('id')
                  ->on('form_sections')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->unique('form_section_id');
            $table->unique(['form_section_id', 'placement_order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_sections');
    }
}
