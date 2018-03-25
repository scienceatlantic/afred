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
            /**
             * Form this relationship belongs to.
             */
            $table->integer('form_id')
                  ->unsigned();
            $table->foreign('form_id')
                  ->references('id')
                  ->on('forms')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            /**
             * Form that we're saying the form above is compatible with.
             */
            $table->integer('compatible_form_id')
                  ->unsigned();
            $table->foreign('compatible_form_id')
                  ->references('id')
                  ->on('forms')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

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
        Schema::dropIfExists('form_form');
    }
}
