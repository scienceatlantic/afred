<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function($table) {
            $table->increments('id');
            $table->integer('facilityId')->unsigned();
            $table->foreign('facilityId')->references('id')->on('facilities')
                ->onDelete('cascade');
            $table->string('type', 200);
            $table->string('manufacturer', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->text('purpose');
            $table->text('purposeNoHtml');
            $table->text('specifications')->nullable();
            $table->text('specificationsNoHtml')->nullable();
            $table->boolean('isPublic')->default(true);
            $table->boolean('hasExcessCapacity');
            $table->smallInteger('yearPurchased')->unsigned()->nullable();
            $table->smallInteger('yearManufactured')->unsigned()->nullable();
            $table->string('keywords', 200)->nullable();
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
        Schema::drop('equipment');
    }
}
