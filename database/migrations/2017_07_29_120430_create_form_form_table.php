<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_form', function (Blueprint $table) {
            // Columns
            $table->integer('form_id')
                  ->unsigned();
            $table->integer('compatible_form_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_id')
                  ->references('id')
                  ->on('forms')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');            
            $table->foreign('compatible_form_id')
                  ->references('id')
                  ->on('forms')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_form');
    }
}
