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
                ->onDelete('cascade');
            $table->integer('facilityId')
                ->unsigned();
            $table->foreign('facilityId')
                ->references('id')
                ->on('facilities')
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
        Schema::drop('discipline_facility');
    }
}
