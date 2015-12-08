<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('facilityId')
                ->unsigned();
            $table->foreign('facilityId')
                ->references('id')
                ->on('facilities')
                ->onDelete('cascade');
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 254);
            $table->char('telephone', 10)
                ->nullable();
            $table->string('extension', 10)
                ->nullable();
            $table->string('position', 100)
                ->nullable();
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
        Schema::drop('contacts');
    }
}
