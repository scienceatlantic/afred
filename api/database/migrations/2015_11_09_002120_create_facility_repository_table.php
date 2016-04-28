<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityRepositoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_repository', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reviewerId')->unsigned()->nullable();
            $table->foreign('reviewerId')->references('id')->on('users')
                ->onDelete('restrict');
            $table->integer('facilityId')->unsigned()->nullable();
            $table->enum('state', ['PENDING_APPROVAL',
                                   'PUBLISHED',
                                   'REJECTED',
                                   'PENDING_EDIT_APPROVAL',
                                   'PUBLISHED_EDIT',
                                   'REJECTED_EDIT']);
            $table->text('reviewerMessage')->nullable();
            $table->longText('data'); // Data stored in JSON
            $table->dateTime('dateSubmitted');
            $table->dateTime('dateReviewed')->nullable();
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
        Schema::drop('facility_repository');
    }
}
