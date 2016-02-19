<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityUpdateLinksTable extends Migration
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
            $table->integer('frIdBefore')
                ->unsigned();
            $table->foreign('frIdBefore')
                ->references('id')
                ->on('facility_repository')   
                ->onDelete('cascade');
            $table->integer('frIdAfter')
                ->unsigned()
                ->unique()
                ->nullable();
            $table->foreign('frIdAfter')
                ->references('id')
                ->on('facility_repository')
                ->onDelete('cascade');
            $table->string('editorFirstName', 50);
            $table->string('editorLastName', 50);
            $table->string('editorEmail', 254);
            $table->char('token', 25)
                ->unique();
            $table->enum('status', ['OPEN', 'PENDING', 'CLOSED']);
            $table->unique(['frIdBefore','frIdAfter']);
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
