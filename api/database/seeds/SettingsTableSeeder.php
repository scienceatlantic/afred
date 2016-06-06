<?php

// Laravel.
use Illuminate\Database\Seeder;

// Misc.
use Carbon\Carbon;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('settings')->delete();
        
        DB::table('settings')->insert([[
            'name'        => 'appName',
            'value'       => 'Atlantic Facilities and Research Equipment Database',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'appShortName',
            'value'       => 'AFRED',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'apiAddress',
            'value'       => 'http://localhost:8000',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'appAddress',
            'value'       => 'http://localhost:9000',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'organizationName',
            'value'       => 'Science Atlantic',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'emailName',
            'value'       => 'AFRED',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'emailAddress',
            'value'       => 'afred@scienceatlantic.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'emailSubjectPrefix',
            'value'       => 'AFRED | ',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'twitterHandle',
            'value'       => '@AFREDatabase',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'generalContactEmail',
            'value'       => 'afred@scienceatlantic.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'generalContactTelephone',
            'value'       => '(902) 494-6910',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'personalContactName',
            'value'       => 'Patty King',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'personalContactTitle',
            'value'       => 'AFRED Program Manager',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'personalContactEmail',
            'value'       => 'patty.king@scienceatlantic.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'contactFormEmail',
            'value'       => 'afred@scienceatlantic.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'contactFormName',
            'value'       => 'AFRED',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'springboardFormEmail',
            'value'       => 'info@springboardatlantic.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'springboardFormName',
            'value'       => 'Springboard Atlantic',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ]]);
    }
}
