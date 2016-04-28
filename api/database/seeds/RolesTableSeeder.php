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
        
        $roles = [
            [
                'name'        => 'Admin',
                'permission'  => 10, // Arbitrary value, since we only have one
                                     // role at the moment.
                'dateCreated' => $now
            ],
        ];
        
        foreach($roles as $role) {
            Role::create($role);
        }
    }
}
