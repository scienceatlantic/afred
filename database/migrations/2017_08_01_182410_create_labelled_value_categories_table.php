<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabelledValueCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labelled_value_categories', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('language_code_id')
                  ->unsigned();
            $table->string('name');
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('language_code_id')
                  ->references('id')
                  ->on('language_codes')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique(['language_code_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('labelled_value_categories');
    }
}
