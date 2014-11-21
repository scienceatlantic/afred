<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacultiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('facilities', function($table) {
                    $table->increments('id');
                    $table->string('name', 50);
                    $table->string('institution', 50);
                    $table->string('city', 50);
                    $table->string('province', 50);
                    $table->string('website', 2083)->nullable(); //IE's maximum
                    $table->text('description');
                    $table->text('additional_info')->nullable();
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
		Schema::drop('facilities');
	}

}
