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
            $table->integer('facility_id')->unsigned();
            $table->foreign('facility_id')->references('id')->
                on('facilities')->onDelete('cascade');
            $table->string('type', 200);
            $table->string('manufacturer', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->text('purpose');
            $table->text('specifications')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('has_excess_capacity');
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
