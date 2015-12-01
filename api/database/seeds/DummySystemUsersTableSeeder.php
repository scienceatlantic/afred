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
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'john.doe@gmail.com',
            'password' => Hash::make('password')
        ]);
    }
}
