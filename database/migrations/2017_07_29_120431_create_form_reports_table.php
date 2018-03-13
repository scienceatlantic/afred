<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_reports', function (Blueprint $table) {
            $table->increments('id');

            /**
             * Form that the form report belongs to.
             */
            $table->integer('form_id')
                  ->unsigned();
            $table->foreign('form_id')
                  ->references('id')
                  ->on('forms')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            /**
             * Name of the form.
             * 
             * E.g. "Published Equipment"
             */
            $table->string('name');
            
            /**
             * Name used when generating the actual file.
             * 
             * E.g. "Published Equipment"
             */
            $table->string('filename');

            /**
             * Columns for the report
             * 
             * Do not use spaces.
             * E.g. 'facilities.0.name,equipment.*.type'
             */
            $table->text('report_columns');

            /**
             * Cache that contains the parsed report_columns.
             * 
             * The API sets this value, do not set it yourself. Emptying this
             * column forces the API to regenerate the cache (this is needed
             * if we have updated the report_columns).
             * 
             * //TODO
             * It is a JSON encoded array in the following format:
             * [[headings], [form_section_object_keys], []]
             */
            $table->text('cache')
                  ->nullable();
            
            $table->timestamps();

            // Foreign keys & indices

            $table->unique(['form_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_reports');
    }
}
