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
            $table->integer('organizationId')
                ->unsigned()
                ->unique();
            $table->foreign('organizationId')
                ->references('id')
                ->on('organizations')
                ->onDelete('cascade');
            $table->string('firstName', 50);
            $table->string('lastName', 50);
            $table->string('email', 254);
            $table->char('telephone', 10);
            $table->string('extension', 10)
                ->nullable();
            $table->string('position', 200);
            $table->string('website', 2083)
                ->nullable();
            $table->datetime('dateAdded');
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
