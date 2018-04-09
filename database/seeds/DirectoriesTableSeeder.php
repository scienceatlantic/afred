<?php

class DirectoriesTableSeeder extends BaseSeeder
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
                'name'              => 'Atlantic Facilities and Research Equipment Database',
                'shortname'         => 'AFRED',
                'resource_folder'   => 'afred',
                'wp_base_url'       => 'http://localhost/afred-wp-demo',
                'wp_admin_base_url' => 'http://localhost/afred-wp-demo/wp-admin',
                'wp_api_base_url'   => 'http://localhost/afred-wp-demo/wp-json/wp/v2',
                'wp_api_password'   => 'cm9vdDp2VFptIG1LZEMgSFpDNSBNV2JiIHBlS3MgU2RSVQ=='
            ],
            [
                'name'              => 'University of Calgary',
                'shortname'         => 'ucalgary',
                'resource_folder'   => 'ucalgary',
                'wp_base_url'       => 'http://localhost/afred-wp-demo2',
                'wp_admin_base_url' => 'http://localhost/afred-wp-demo2/wp-admin',
                'wp_api_base_url'   => 'http://localhost/afred-wp-demo2/wp-json/wp/v2',
                'wp_api_password'   => 'YWZyZWR3cGRlbW8yOnlIQTUgVlRBSSBqU3V4IGVXdkogUE8zNCBIZ1Aw'
            ],            
        ];

        self::saveModels('Directory', $directories);
    }
}
