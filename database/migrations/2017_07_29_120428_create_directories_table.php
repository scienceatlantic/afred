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
            $table->string('email_folder');
            $table->string('wp_base_url');
            $table->string('wp_admin_base_url');
            $table->string('wp_api_base_url');
            $table->string('wp_api_password');
            $table->timestamps();

            // Foreign keys & indices
            $table->unique('name');
            $table->unique('email_folder');
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
