<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacultiesTable extends Migration
{
    public function up()
    {
        Schema::create('facilities', function($table) {
            $table->increments('id');
            $table->integer('institution_id')->unsigned();
            $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('restrict');
            $table->string('name', 50);
            $table->string('city', 50);
            $table->string('province', 50);
            $table->string('website', 2083)->nullable(); //Internet Explorer's limit
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('facilities');
    }
}
