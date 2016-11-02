<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function($table) {
            $table->increments('id');
            $table->integer('facilityId')
                  ->unsigned();
            $table->foreign('facilityId')
                  ->references('id')
                  ->on('facilities')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->string('type', 200)
                  ->index();
            $table->string('manufacturer', 100)
                  ->nullable()
                  ->index();
            $table->string('model', 100)
                  ->nullable()
                  ->index();
            $table->text('purpose');
            $table->text('purposeNoHtml');
            $table->text('specifications')
                  ->nullable();
            $table->text('specificationsNoHtml')
                  ->nullable();
            $table->boolean('isPublic')
                  ->default(true);
            $table->boolean('hasExcessCapacity');
            $table->smallInteger('yearPurchased')
                  ->unsigned()
                  ->nullable();
            $table->smallInteger('yearManufactured')
                  ->unsigned()
                  ->nullable();
            $table->string('keywords', 500)
                  ->nullable();
        });

        // Add FULLTEXT indices to 'purposeNoHtml' and 'speficationsNoHtml'
        // columns.
        //DB::statement('ALTER TABLE `equipment` ADD FULLTEXT equipment_purposenohtml_index(`purposeNoHtml`)');
        //DB::statement('ALTER TABLE `equipment` ADD FULLTEXT equipment_specificationsnohtml_index(`specificationsNoHtml`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}
