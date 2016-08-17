<?php

// Laravel.
use Illuminate\Database\Seeder;

// Models.
use Carbon\Carbon;
use App\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        
        // Delete existing roles.
        DB::table('roles')->delete();
        
        // Use this to add more roles. We've set the maximum permission level at
        // 10. So that means that we can have a maximum of 10 roles.
        $roles = [[
            'name'        => 'SUPER_ADMIN',
            'permission'  => 10,
            'dateCreated' => $now
        ], [
            'name'        => 'ADMIN',
            'permission'  => 9,
            'dateCreated' => $now
        ]];
        
        foreach($roles as $role) {
            Role::create($role);
        }
    }
}
