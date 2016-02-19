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
        
        DB::table('roles')->delete();
        
        $roles = [
            [
                'name'        => 'Admin',
                'dateCreated' => $now
            ]
        ];
        
        foreach($roles as $role) {
            Role::create($role);
        }
    }
}
