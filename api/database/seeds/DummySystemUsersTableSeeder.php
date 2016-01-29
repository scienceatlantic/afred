<?php

// Laravel.
use Illuminate\Database\Seeder;

// Models.
use App\SystemUser;

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
        
        $systemUsers = [
            [
                'firstName' => 'Prasad',
                'lastName'  => 'Rajandran',
                'role'      => 'ADMINISTRATOR',
                'username'  => 'prasad@scienceatlantic.ca',
                'password'  => Hash::make('password')
            ]
        ];
        
        foreach($systemUsers as $systemUser) {
            SystemUser::create($systemUser);
        }
    }
}
