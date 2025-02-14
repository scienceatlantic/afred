<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormEntryStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_entry_statuses', function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('name');
            $table->boolean('show_in_dropdown')
                  ->default(true);
            $table->timestamps();

            // Foreign keys & indices
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_entry_statuses');
    }
}
