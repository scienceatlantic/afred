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
            $table->integer('institution_id')->unsigned();
            $table->foreign('institution_id')->references('id')->
                on('institutions')->onDelete('cascade');
            $table->string('first_name', env('DB_LEN_FNAME'));
            $table->string('last_name', env('DB_LEN_LNAME'));
            $table->string('email', env('DB_LEN_EMAIL'));
            $table->char('telephone', env('DB_LEN_PHONE'));
            $table->string('extension', env('DB_LEN_EXTENSION'))->nullable();
            $table->string('position', 50);
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
