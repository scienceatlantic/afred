<?php

class DummyDirectoriesTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // NOTE: Remember not to commit actual production "wp_api_password" 
        // values into GitHub.
        
        $directories = [
            [
                'name'            => 'Atlantic Facilities and Research Equipment Database',
                'shortname'       => 'AFRED',
                'wp_base_url'     => 'http://localhost/afred-wp-demo',
                'wp_api_base_url' => 'http://localhost/afred-wp-demo/wp-json/wp/v2',
                'wp_api_password' => 'cm9vdDpkTFVrIG0wbFUgQTl5eiAzSUZ1IFpLTnMgV0R1bg==',
            ]
        ];

        self::saveModels('Directory', $directories);
    }
}
