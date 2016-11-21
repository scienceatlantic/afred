<?php

use Illuminate\Support\Facades\Schema;
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
            $table->integer('facilityRepositoryId')
                  ->unsigned();
            $table->foreign('facilityRepositoryId')
                   ->references('id')
                   ->on('facility_repository')
                   ->onDelete('restrict')
                   ->onUpdate('cascade');
            $table->integer('organizationId')
                  ->unsigned();
            $table->foreign('organizationId')
                  ->references('id')
                  ->on('organizations')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->integer('provinceId')
                  ->unsigned();
            $table->foreign('provinceId')
                  ->references('id')
                  ->on('provinces')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            $table->string('name', 200)
                  ->index();
            $table->string('city', 150)
                  ->nullable()
                  ->index();
            $table->string('website', 2083)
                  ->nullable();
            $table->text('description');
            $table->text('descriptionNoHtml')
                  ->nullable();
            $table->boolean('isPublic')
                  ->default(true);
            $table->dateTime('datePublished');
            $table->dateTime('dateUpdated');
        });

        // Add FULLTEXT index to 'descriptionNoHtml' column.
        //DB::statement('ALTER TABLE `facilities` ADD FULLTEXT facilities_descriptionnohtml_index(`descriptionNoHtml`)');      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facilities');
    }
}
