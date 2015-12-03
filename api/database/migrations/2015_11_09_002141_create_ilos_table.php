<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIlosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ilos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('institutionId')->unsigned();
            $table->foreign('institutionId')->references('id')->
                on('institutions')->onDelete('cascade');
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 254);
            $table->char('telephone', 10);
            $table->string('extension', 10)->nullable();
            $table->string('position', 200);
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
        Schema::drop('ilos');
    }
}
