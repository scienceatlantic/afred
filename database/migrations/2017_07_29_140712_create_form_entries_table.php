<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_entries', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('resource_id')
                  ->unsigned();
            $table->integer('form_id')
                  ->unsigned();
            $table->integer('form_entry_status_id')
                  ->unsigned();
            $table->json('cache')
                  ->nullable();
            $table->boolean('isCached')
                  ->default(false);
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_id')
                  ->references('id')
                  ->on('forms')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');                  
            $table->foreign('form_entry_status_id')
                  ->references('id')
                  ->on('form_entry_statuses')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');                  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_entries');
    }
}
