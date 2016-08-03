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
            'type'        => 'STRING',
            'value'       => 'Atlantic Facilities and Research Equipment Database',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'appShortName',
            'type'        => 'STRING',
            'value'       => 'AFRED',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'appAddress',
            'type'        => 'URL',
            'value'       => 'https://afred.ca/app',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'organizationName',
            'type'        => 'STRING',
            'value'       => 'Science Atlantic',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'emailSubjectPrefix',
            'type'        => 'STRING',
            'value'       => 'AFRED | ',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'twitterHandle',
            'type'        => 'STRING',
            'value'       => '@AFREDatabase',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'generalContactEmail',
            'type'        => 'EMAIL',
            'value'       => 'afred@scienceatlantic.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'generalContactTelephone',
            'type'        => 'STRING',
            'value'       => '(902) 494-6910',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'personalContactName',
            'type'        => 'STRING',
            'value'       => 'Patty King',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'personalContactTitle',
            'type'        => 'STRING',
            'value'       => 'AFRED Program Manager',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'personalContactEmail',
            'type'        => 'EMAIL',
            'value'       => 'patty.king@scienceatlantic.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'contactFormEmail',
            'type'        => 'EMAIL',
            'value'       => 'afred@scienceatlantic.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'contactFormName',
            'type'        => 'STRING',
            'value'       => 'AFRED',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'springboardFormEmail',
            'type'        => 'EMAIL',
            'value'       => 'info@springboardatlantic.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'springboardFormName',
            'type'        => 'EMAIL',
            'value'       => 'Springboard Atlantic',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ]]);
    }
}
