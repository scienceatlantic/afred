<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabelledValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labelled_values', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('label');
            $table->timestamps();

            // Foreign Keys & Indices
            $table->unique('label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('labelled_values');
    }
}
