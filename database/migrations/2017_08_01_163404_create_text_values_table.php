<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_values', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('entry_field_id')
                  ->unsigned();
            $table->text('value');
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('entry_field_id')
                  ->references('id')
                  ->on('entry_fields')
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
        Schema::dropIfExists('text_values');
    }
}
