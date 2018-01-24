<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDateValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_values', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('entry_field_id')
                  ->unsigned();
            $table->dateTime('value');
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
        Schema::dropIfExists('date_values');
    }
}
