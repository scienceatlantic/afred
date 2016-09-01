<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplineFacilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discipline_facility', function (Blueprint $table) {
            $table->integer('disciplineId')
                  ->unsigned();
            $table->foreign('disciplineId')
                  ->references('id')
                  ->on('disciplines')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->integer('facilityId')
                  ->unsigned();
            $table->foreign('facilityId')
                  ->references('id')
                  ->on('facilities')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->primary(['disciplineId', 'facilityId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('discipline_facility');
    }
}
