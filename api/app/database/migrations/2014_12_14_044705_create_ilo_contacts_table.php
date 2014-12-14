<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIloContactsTable extends Migration {
    public function up()
    {
        Schema::create('ilo_contacts', function($table) {
            $table->increments('id');
            $table->integer('institution_id')->unsigned();
            $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('cascade');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 254);
            $table->char('telephone', 10);
            $table->string('position', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('ilo_contacts');
    }
}