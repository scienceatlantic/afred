<?php

// Laravel.
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();
        
        DB::table('settings')->insert([
            [
                'name'  => 'APP_NAME',
                'value' => 'Atlantic Facilities and Research Equipment Database'
            ],
            [
                'name'  => 'APP_ACRONYM',
                'value' => 'AFRED'           
            ],
            [
                'name'  => 'API_ADDRESS',
                'value' => 'http://localhost:8000'           
            ],
            [
                'name'  => 'APP_ADDRESS',
                'value' => 'http://localhost:9000'           
            ],
            [
                'name'  => 'ORGANIZATION_NAME',
                'value' => 'Science Atlantic'           
            ],           
            [
                'name'  => 'EMAIL_NAME',
                'value' => 'AFRED'           
            ],
            [
                'name'  => 'EMAIL_ADDRESS',
                'value' => 'afred@scienceatlantic.ca'           
            ],
            [
                'name'  => 'EMAIL_SUBJECT_PREFIX',
                'value' => 'AFRED 2.0 | Science Atlantic - (test) '           
            ],
            [
                'name'  => 'GENERAL_CONTACT_NAME',
                'value' => 'Patty King'           
            ],
            [
                'name'  => 'GENERAL_CONTACT_EMAIL_ADDRESS',
                'value' => 'patty@scienceatlantic.ca'           
            ],
        ]);
    }
}
