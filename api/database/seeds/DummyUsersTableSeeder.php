<?php

// Laravel.
use Illuminate\Database\Seeder;

// Misc.
use \Log;

// Models.
use Carbon\Carbon;
use App\User;

class DummyUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        
        // Delete existing entries.
        DB::table('users')->delete();
        
        $users = [[
            'firstName'   => 'Prasad',
            'lastName'    => 'Rajandran',
            'email'       => 'prasad@scienceatlantic.ca',
            'password'    => bcrypt('password'),
            'dateCreated' => $now,
            'dateUpdated' => $now
        ]];
        
        foreach($users as $user) {
            $u = User::create($user);
            
            // Attach the admin role (we're assuming it's ID is 1).
            $u->roles()->attach(1, ['dateCreated' => $now]);
        }
    }
}
