<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration {
    public function up()
    {
        Schema::create('contacts', function($table) {
            $table->increments('id');
            $table->integer('facility_id')->unsigned();
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 254);
            $table->char('telephone', 10);
            $table->string('position', 50)->nullable();
            $table->string('department', 100)->nullable();
            $table->boolean('editor');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('contacts');
    }
}
