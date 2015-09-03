<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::create('users', function(Blueprint $table)
	{
	    $table->increments('id');
	    $table->string('username', 254);
	    $table->string('password', 40);
	    $table->string('firstName', 50);
	    $table->string('lastName', 50);
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
	Schema::drop('users');
    }
}
