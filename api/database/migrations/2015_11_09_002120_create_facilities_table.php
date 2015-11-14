<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function($table) {
            $table->increments('id');
            $table->integer('institution_id')->unsigned();
            $table->foreign('institution_id')->references('id')->
                on('institutions')->onDelete('restrict');
            $table->integer('province_id')->unsigned();
            $table->foreign('province_id')->references('id')->
                on('provinces')->onDelete('restrict');
            $table->string('name', 200);
            $table->string('city', 150);
            $table->string('website', 2083)->nullable();
            $table->text('description');
            $table->boolean('is_public')->default(true);
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
        Schema::drop('facilities');
    }
}
