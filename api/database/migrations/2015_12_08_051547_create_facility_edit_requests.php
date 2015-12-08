<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityEditRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_edit_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('frhIdBeforeEdit')
                ->unsigned();
            $table->foreign('frhIdBeforeEdit')
                ->references('id')
                ->on('facility_revision_history')
                ->onDelete('cascade');
            $table->integer('frhIdAfterEdit')
                ->unsigned()
                ->nullable();
            $table->foreign('frhIdAfterEdit')
                ->references('id')
                ->on('facility_revision_history')
                ->onDelete('cascade');
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 254);
            $table->char('token', 20);
            $table->datetime('dateRequested');
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
        Schema::drop('facility_edit_requests');
    }
}
