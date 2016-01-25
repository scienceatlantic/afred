<?php

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
                'id'    => 'appName',
                'value' => 'Atlantic Facilities and Research Equipment Database'
            ],
            [
                'id'    => 'appAcronym',
                'value' => 'AFRED'           
            ],
            [
                'id'    => 'apiAddress',
                'value' => 'http://localhost:8000'           
            ],
            [
                'id'    => 'appAddress',
                'value' => 'http://localhost:9000'           
            ],
            [
                'id'    => 'mailName',
                'value' => 'AFRED'           
            ],
            [
                'id'    => 'mailAddress',
                'value' => 'afred@scienceatlantic.ca'           
            ],
            [
                'id'    => 'mailSubjectPrefix',
                'value' => 'AFRED 2.0 TEST - '           
            ],
            [
                'id'    => 'generalContactName',
                'value' => 'Patty King'           
            ],
            [
                'id'    => 'generalContactEmailAddress',
                'value' => 'patty@scienceatlantic.ca'           
            ],
        ]);
    }
}
