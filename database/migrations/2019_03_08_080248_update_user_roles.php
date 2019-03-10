<?php

use App\User;
use App\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $db_role = Role
          ::where('name', "database_user")
          ->first();

      $contributor_role = Role
          ::where('name', "Contributor")
          ->first();

      $users = User
          ::where('role_id', $contributor_role->id)
          ->get();

      /* Update each user with the new database_user id value */
      foreach ($users as $user) {
          DB::table('users')
              ->where('id', $user->id)
              ->update(['role_id' => $db_role->id]);
      }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        $db_role = Role
            ::where('name', "database_user")
            ->first();

        $contributor_role = Role
            ::where('name', "Contributor")
            ->first();

        $users = User
            ::where('role_id', $db_role->id)
            ->get();

        /* Update each user with the new database_user id value */
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['role_id' => $contributor_role->id]);
        }
    }
}
