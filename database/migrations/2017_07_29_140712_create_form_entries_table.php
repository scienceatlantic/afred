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
            $table->integer('reviewer_user_id')
                  ->unsigned()
                  ->nullable();
            $table->integer('author_user_id')
                  ->unsigned()
                  ->nullable();
            $table->integer('primary_contact_user_id')
                  ->unsigned()
                  ->nullable();
            $table->string('order_by_title')
                  ->nullable();
            $table->longText('cache')
                  ->nullable();
            $table->text('message')
                  ->nullable();
            $table->text('notes')
                  ->nullable();
            $table->boolean('is_edit')
                  ->default(false);
            $table->timestamp('reviewed_at')
                  ->nullable();
            $table->timestamps();

            // Foreign keys & indices
            $table->index('resource_id');
            $table->index('order_by_title');
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
            $table->foreign('reviewer_user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('author_user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('primary_contact_user_id')
                  ->references('id')
                  ->on('users')
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
