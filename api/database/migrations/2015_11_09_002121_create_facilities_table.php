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
            $table->integer('facilityRepositoryId')->unsigned();
            $table->foreign('facilityRepositoryId')->references('id')
                ->on('facility_repository')->onDelete('restrict');
            $table->integer('organizationId')->unsigned();
            $table->foreign('organizationId')->references('id')
                ->on('organizations')->onDelete('restrict');
            $table->integer('provinceId')->unsigned();
            $table->foreign('provinceId')->references('id')->on('provinces')
                ->onDelete('restrict');
            $table->string('name', 200);
            $table->string('city', 150)->nullable();
            $table->string('website', 2083)->nullable();
            $table->text('description');
            $table->text('descriptionNoHtml');
            $table->boolean('isPublic')->default(true);
            $table->dateTime('datePublished');
            $table->dateTime('dateUpdated');
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
