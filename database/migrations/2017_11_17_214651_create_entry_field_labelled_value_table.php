<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntryFieldLabelledValueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_field_labelled_value', function (Blueprint $table) {
            // Columns
            $table->integer('entry_field_id')
                  ->unsigned();
            $table->integer('labelled_value_id')
                  ->unsigned();
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('entry_field_id')
                  ->references('id')
                  ->on('entry_fields')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('labelled_value_id')
                  ->references('id')
                  ->on('labelled_values')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique(
                ['entry_field_id', 'labelled_value_id'],
                'ef_id_lv_id_unique'
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
        Schema::dropIfExists('entry_field_labelled_value');
    }
}
