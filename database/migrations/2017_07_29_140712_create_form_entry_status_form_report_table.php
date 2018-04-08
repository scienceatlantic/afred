<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormEntryStatusFormReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_entry_status_form_report', function (Blueprint $table) {
            // Columns
            $table->integer('form_entry_status_id')
                  ->unsigned();
            $table->integer('form_report_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('form_entry_status_id', 'fes_id_foreign')
                  ->references('id')
                  ->on('form_entry_statuses')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('form_report_id', 'fr_id_foreign')
                  ->references('id')
                  ->on('form_reports')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->unique(
                ['form_entry_status_id', 'form_report_id'],
                'fes_id_fr_id_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_entry_status_form_report');
    }
}
