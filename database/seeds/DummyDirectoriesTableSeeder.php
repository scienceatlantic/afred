<?php

use App\Directory;
use Illuminate\Database\Seeder;

class DummyDirectoriesTableSeeder extends Seeder
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
                'wp_base_url'     => 'http://localhost/afred-wp-demo',
                'wp_api_base_url' => 'http://localhost/afred-wp-demo/wp-json/wp/v2',
                'wp_api_password' => 'cm9vdDpkTFVrIG0wbFUgQTl5eiAzSUZ1IFpLTnMgV0R1bg==', // TODO: Commit only dummy credentials to GitHub!
            ]
        ];

        foreach($directories as $directory) {
            $d = new Directory();
            foreach($directory as $key => $value) {
                $d->$key = $directory[$key];
            }
            $d->save();
        }
    }
}
