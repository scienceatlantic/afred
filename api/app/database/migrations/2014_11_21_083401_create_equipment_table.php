<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    public function up()
    {
        Schema::create('equipment', function($table) {
            $table->increments('id');
            $table->integer('facility_id')->unsigned();
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->string('name');
            $table->text('purpose');
            $table->text('specifications')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('equipment');
    }
}
