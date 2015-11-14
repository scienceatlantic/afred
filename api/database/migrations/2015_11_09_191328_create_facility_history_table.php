<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('facility_id')->unsigned()->nullable();
            $table->enum('state', array('NEW_DRAFT, PENDING_APPROVAL,
                PENDING_CORRECTIONS, PUBLISHED, EDIT_DRAFT,
                PENDING_EDIT_APPROVAL, PENDING_EDIT_CORRECTIONS'));
            $table->longText('data_in_json');
            $table->dateTime('date_published');
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
        Schema::drop('facility_history');
    }
}
