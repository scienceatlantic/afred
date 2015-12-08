<?php

use Illuminate\Database\Seeder;

class DummySystemUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Delete existing entries.
        DB::table('system_users')->delete();
        
        DB::table('system_users')->insert([
            'firstName' => 'Prasad',
            'lastName'  => 'Rajandran',
            'username'  => 'prasad@scienceatlantic.ca',
            'password'  => Hash::make('password')
        ]);
    }
}
