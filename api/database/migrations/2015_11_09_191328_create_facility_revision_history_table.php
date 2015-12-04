<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityRevisionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_revision_history', function (Blueprint
            $table) {
                $table->increments('id');
                $table->integer('facilityId')->unsigned()->nullable();
                $table->foreign('facilityId')->references('id')->
                    on('facilities')->onDelete('no action');
                $table->enum('state', ['PENDING_APPROVAL',
                                       'PUBLISHED',
                                       'REJECTED',
                                       'EDIT_DRAFT',
                                       'PENDING_EDIT_APPROVAL'.
                                       'REJECTED_EDIT']);
                $table->string('accessKey', 60)->nullable();
                $table->longText('facilityInJson');
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
        Schema::drop('facility_revision_history');
    }
}
