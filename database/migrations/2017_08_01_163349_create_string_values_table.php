<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStringValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('string_values', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('entry_field_id')
                  ->unsigned();
            $table->string('value');
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('entry_field_id')
                  ->references('id')
                  ->on('entry_fields')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('string_values');
    }
}
