<?php

use App\Directories;
use App\User;

class DummyUsersTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'role_id'     => 1,
                'wp_user_id'  => 1,
                'wp_home'     => 'http://localhost/afred-wp-demo',
                'wp_username' => 'root',
                'first_name'  => 'Prasad',
                'last_name'   => 'Rajandran',
                'email'       => 'prasad@scienceatlantic.ca',
                'password'    => '$2y$10$Q.vdV2MZG53Rqwt2pmQZheDh.KGbbbTc.JJ6P1aY5M.rsRTDWlpC6'
            ]
        ];

        self::saveModels('User', $users);

        // Attach AFRED directory to user.
        $user = User::findByWpUsername('root');
        $user->directories()->attach(1);
    }
}
