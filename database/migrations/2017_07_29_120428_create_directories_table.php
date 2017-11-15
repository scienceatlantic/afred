<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directories', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('name');
            $table->string('shortname')
                  ->nullable();
            $table->string('wp_api_url');
            $table->string('wp_api_username');
            $table->timestamps();

            // Foreign keys & indices
            $table->unique('name');
            $table->index('shortname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directories');
    }
}
