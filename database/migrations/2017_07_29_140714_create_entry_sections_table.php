<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntrySectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_sections', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->integer('form_entry_id')
                  ->unsigned();
            $table->integer('form_section_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_entry_id')
                  ->references('id')
                  ->on('form_entries')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('form_section_id')
                  ->references('id')
                  ->on('form_sections')
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
        Schema::dropIfExists('entry_sections');
    }
}
