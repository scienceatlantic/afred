<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectoryEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_entity', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('directory_id')
                  ->unsigned();
            $table->integer('entity_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & columns
            $table->foreign('directory_id')
                  ->references('id')
                  ->on('directories')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('entity_id')
                  ->references('id')
                  ->on('entities')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique(['directory_id', 'entity_id']);                  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directory_entity');
    }
}
