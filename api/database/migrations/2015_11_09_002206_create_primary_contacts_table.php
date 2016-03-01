<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrimaryContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('primary_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('facilityId')
                ->unsigned()
                ->unique();
            $table->foreign('facilityId')
                ->references('id')
                ->on('facilities')
                ->onDelete('cascade');
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 254);
            $table->char('telephone', 10);
            $table->string('extension', 10)
                ->nullable();
            $table->string('position', 100);
            $table->string('website', 2083)
                ->nullable();
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
        Schema::drop('primary_contacts');
    }
}
