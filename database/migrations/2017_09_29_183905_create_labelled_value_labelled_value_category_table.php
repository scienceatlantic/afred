<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabelledValueLabelledValueCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labelled_value_labelled_value_category', function (Blueprint $table) {
            // Columns
            $table->integer('labelled_value_id')
                  ->unsigned();
            $table->integer('labelled_value_category_id')
                  ->unsigned();                  
            $table->timestamps();

            // Foreign keys & indices
            $table->foreign('labelled_value_id', 'lv_id_foreign')
                  ->references('id')
                  ->on('labelled_values')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->foreign('labelled_value_category_id', 'lvc_id_foreign')
                  ->references('id')
                  ->on('labelled_value_categories')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
            $table->unique(
                ['labelled_value_id', 'labelled_value_category_id'],
                'lv_id_lvc_id_unique'
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
        Schema::dropIfExists('labelled_value_labelled_value_category');
    }
}
