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
            'name'        => 'appAcronym',
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
            'value'       => 'http://localhost:9000/#',
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
            'value'       => 'AFRED 2.0 | Science Atlantic - (test) ',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'generalContactName',
            'value'       => 'Patty King',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ], [
            'name'        => 'generalContactEmailAddress',
            'value'       => 'patty@scienceatlantic.ca',
            'dateCreated' => $now,
            'dateUpdated' => $now
        ]]);
    }
}
