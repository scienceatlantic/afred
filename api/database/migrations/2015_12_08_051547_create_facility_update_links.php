<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityUpdateLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_update_links', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fr_id_before')
                ->unsigned()
                ->unique();
            $table->foreign('fr_id_before')
                ->references('id')
                ->on('facility_repository')   
                ->onDelete('cascade');
            $table->integer('fr_id_after')
                ->unsigned()
                ->unique()
                ->nullable();
            $table->foreign('fr_id_after')
                ->references('id')
                ->on('facility_repository')
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
        Schema::drop('facility_update_links');
    }
}
