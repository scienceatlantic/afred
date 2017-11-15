<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_codes', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('name');
            $table->string('iso_639_1');
            $table->timestamps();

            // Foreign keys & indices
            $table->unique('name');
            $table->unique('iso_639_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_codes');
    }
}
