<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeKeywordFieldsRequired extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('form_fields')
            ->where('object_key', "keywords")
            ->update(['is_required' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::table('form_fields')
          ->where('object_key', "keywords")
          ->update(['is_required' => 0]);
    }
}
