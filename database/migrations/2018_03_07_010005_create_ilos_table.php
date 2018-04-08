<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIlosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ilos', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('labelled_value_id')
                  ->unsigned();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('position');
            $table->string('telephone');
            $table->string('extension')
                  ->nullable();
            $table->string('website', 2083)
                  ->nullable();                  
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('labelled_value_id')
                  ->references('id')
                  ->on('labelled_values')
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
        Schema::dropIfExists('ilos');
    }
}
