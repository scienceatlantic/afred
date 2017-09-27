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
            $table->string('form_label');
            $table->string('object_key');
            $table->string('intro_text')
                  ->nullable();
            $table->string('help_text')
                  ->nullable();
            $table->integer('repeat_min')
                  ->unsigned();
            $table->integer('repeat_max')
                  ->unsigned();
            $table->integer('form_placement_order')
                  ->unsigned();
            $table->boolean('is_active')
                  ->default(true);
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_id')
                  ->references('id')
                  ->on('forms')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique(['form_id', 'form_label']);
            $table->unique(['form_id', 'object_key']);
            $table->unique(['form_id', 'form_placement_order']);
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
