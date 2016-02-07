<?php

// Laravel.
use Illuminate\Database\Seeder;

// Models.
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
        // Delete existing entries.
        DB::table('users')->delete();
        
        $users = [
            [
                'firstName' => 'Prasad',
                'lastName'  => 'Rajandran',
                'role'      => 'ADMINISTRATOR',
                'email'     => 'prasad@scienceatlantic.ca',
                'password'  => bcrypt('password')
            ]
        ];
        
        foreach($users as $user) {
            User::create($user);
        }
    }
}
