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
                $table->integer('facility_id')->unsigned()->nullable();
                $table->foreign('facility_id')->references('id')->
                    on('facilities')->onDelete('no action');
                $table->integer('institution_id')->unsigned()->nullable();
                $table->foreign('institution_id')->references('id')->
                    on('institutions')->onDelete('restrict');
                $table->integer('province_id')->unsigned();
                $table->foreign('province_id')->references('id')->
                    on('provinces')->onDelete('restrict');
                $table->enum('state', ['PENDING_APPROVAL',
                                       'PUBLISHED',
                                       'REJECTED',
                                       'EDIT_DRAFT',
                                       'PENDING_EDIT_APPROVAL'.
                                       'REJECTED_EDIT']);
                $table->string('access_key', 60)->nullable();
                $table->longText('facility_in_json');
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
