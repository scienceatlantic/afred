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
            $table->integer('institutionId')
                ->unsigned();
            $table->foreign('institutionId')
                ->references('id')
                ->on('institutions')
                ->onDelete('restrict');
            $table->integer('provinceId')
                ->unsigned();
            $table->foreign('provinceId')
                ->references('id')
                ->on('provinces')
                ->onDelete('restrict');
            $table->string('name', 200);
            $table->string('city', 150);
            $table->string('website', 2083)
                ->nullable();
            $table->text('description');
            $table->boolean('isPublic')
                ->default(true);
            $table->datetime('dateSubmitted');
            $table->datetime('dateUpdated');
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
