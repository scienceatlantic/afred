<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilitySectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_sector', function (Blueprint $table) {
            $table->integer('facilityId')
                  ->unsigned();
            $table->foreign('facilityId')
                  ->references('id')
                  ->on('facilities')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');           
            $table->integer('sectorId')
                  ->unsigned();
            $table->foreign('sectorId')
                  ->references('id')
                  ->on('sectors')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->primary(['facilityId', 'sectorId']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('facility_sector');
    }
}
