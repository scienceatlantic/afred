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

        $superAdminId = DB::table('roles')->where('name', 'SUPER_ADMIN')
            ->first()->id;
        $adminId = DB::table('roles')->where('name', 'ADMIN')->first()->id;
        
        DB::table('settings')->insert([[
            'name'             => 'appName',
            'type'             => 'STRING',
            'value'            => 'Atlantic Facilities and Research Equipment Database',
            'minAuthRoleOnGet' => null,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'appShortName',
            'type'             => 'STRING',
            'value'            => 'AFRED',
            'minAuthRoleOnGet' => null,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'appAddress',
            'type'             => 'URL',
            'value'            => 'https://afred.ca',
            'minAuthRoleOnGet' => null,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'organizationName',
            'type'             => 'STRING',
            'value'            => 'Science Atlantic',
            'minAuthRoleOnGet' => null,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'emailSubjectPrefix',
            'type'             => 'STRING',
            'value'            => 'AFRED | ',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'twitterHandle',
            'type'             => 'STRING',
            'value'            => '@AFREDatabase',
            'minAuthRoleOnGet' => null,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'generalContactEmail',
            'type'             => 'EMAIL',
            'value'            => 'afred@scienceatlantic.ca',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'generalContactTelephone',
            'type'             => 'STRING',
            'value'            => '(902) 494-6910',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'personalContactName',
            'type'             => 'STRING',
            'value'            => 'Patty King',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'personalContactTitle',
            'type'             => 'STRING',
            'value'            => 'AFRED Program Manager',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'personalContactEmail',
            'type'             => 'EMAIL',
            'value'            => 'patty.king@scienceatlantic.ca',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'contactFormEmail',
            'type'             => 'EMAIL',
            'value'            => 'afred@scienceatlantic.ca',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'contactFormName',
            'type'             => 'STRING',
            'value'            => 'AFRED',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'springboardFormEmail',
            'type'             => 'EMAIL',
            'value'            => 'info@springboardatlantic.ca',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'springboardFormName',
            'type'             => 'EMAIL',
            'value'            => 'Springboard Atlantic',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'sitemapFilename',
            'type'             => 'STRING',
            'value'            => 'D:\Prasad\Documents\Files\Workspace\sitemap.xml',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'sitemapFixedUrls',
            'type'             => 'JSONTEXT',
            'value'            => null,
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'sitemapPing',
            'type'             => 'STRING',
            'value'            => '/sitemap.xml',
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'sitemapPingServices',
            'type'             => 'JSONTEXT',
            'value'            => null,
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'cronJobNumCycles',
            'type'             => 'INT',
            'value'            => 6,
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'cronJobSleepDuration',
            'type'             => 'INT',
            'value'            => 150,
            'minAuthRoleOnGet' => $superAdminId,
            'minAuthRoleOnPut' => $superAdminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now
        ], [
            'name'             => 'websiteNotice',
            'type'             => 'TEXT',
            'value'            => null,
            'minAuthRoleOnGet' => null,
            'minAuthRoleOnPut' => $adminId,
            'dateCreated'      => $now,
            'dateUpdated'      => $now            
        ]]);

        // Sitemap fixed URLs.
        $id = DB::table('settings')->where('name', 'sitemapFixedUrls')
            ->first()->id;
        DB::table('settings_text')->insert([
            'settingId' => $id,
            'value'  => json_encode([
                '/search',
                '/facilities/form/create',
                '/facilities/form/guide',
                '/facilities/update',
                '/about',
                '/legal/privacy-policy',
                '/legal/terms-of-service',
                '/legal/disclaimer',
                '/contact'
            ])
        ]);

        // Sitemap ping services.
        $id = DB::table('settings')->where('name', 'sitemapPingServices')
            ->first()->id;
        DB::table('settings_text')->insert([
            'settingId' => $id,
            'value'  => json_encode([
                'http://google.com/ping?sitemap=',
                'http://www.bing.com/ping?sitemap=',
            ])
        ]);      
    }
}
