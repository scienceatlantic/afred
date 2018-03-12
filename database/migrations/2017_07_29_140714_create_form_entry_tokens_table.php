<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormEntryTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_entry_tokens', function (Blueprint $table) {
            // Columns.
            $table->increments('id');
            $table->integer('before_update_form_entry_id')
                  ->unsigned();
            $table->integer('after_update_form_entry_id')
                  ->unsigned()
                  ->nullable();
            $table->integer('resource_id')
                  ->unsigned();
            $table->integer('user_id')
                  ->unsigned();
            $table->integer('form_entry_token_status_id')
                  ->unsigned();
            $table->string('value');
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('before_update_form_entry_id')
                  ->references('id')
                  ->on('form_entries')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('after_update_form_entry_id')
                  ->references('id')
                  ->on('form_entries')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');                  
            $table->foreign('resource_id')
                  ->references('resource_id')
                  ->on('form_entries')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('form_entry_token_status_id')
                  ->references('id')
                  ->on('form_entry_token_statuses')
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
        Schema::dropIfExists('form_entry_tokens');
    }
}
