<?php

use App\Directory;
use Illuminate\Database\Seeder;

class DirectoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $directories = [
            [
                'name'            => 'Atlantic Facilities and Research Equipment Database',
                'shortname'       => 'AFRED',
                'wp_api_url'      => 'https://afred.ca/wp/json',
                'wp_api_username' => 'afred'
            ]
        ];

        foreach($directories as $directory) {
            $d = new Directory();
            $d->name = $directory['name'];
            $d->shortname = $directory['shortname'];
            $d->wp_api_url = $directory['wp_api_url'];
            $d->wp_api_username = $directory['wp_api_username'];
            $d->save();
        }
    }
}
