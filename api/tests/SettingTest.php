<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SettingTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetSettings()
    {
        $name = \App\Setting::find(1)->name;
        $value = \App\Setting::lookup($name); 

        $this->actingAs($this->getSuperAdmin())
             ->get('/settings')
             ->seeJson([
                 'name'  => $name, 
                 'value' => $value
             ])
             ->assertResponseOk();
    }

    public function testGetSettingsAsAdmin()
    {
        $this->actingAs($this->getAdmin())
             ->get('/settings')
             ->assertResponseStatus(403);
    }

    public function testGetSettingWebsiteNotice()
    {
        $this->get('/settings?name=websiteNotice')
            ->assertResponseOk();
    }

    public function testGetSettingPersonalContactEmail()
    {
        $this->actingAs($this->getSuperAdmin())
             ->get('/settings?name=personalContactEmail')
             ->assertResponseOk();
    }

    public function testGetSettingPersonalContactEmailAsAdmin()
    {
        $this->actingAs($this->getAdmin())
             ->get('/settings?name=personalContactEmail')
             ->assertResponseStatus(403);
    }

    public function testGetSettingPersonalContactEmailWithoutAuth()
    {
        $this->get('/settings?name=personalContactEmail')
             ->assertResponseStatus(403);
    }

    public function testGetSettingWebsiteNoticeAndAppName()
    {
        $websiteNotice = App\Setting::where('name', 'websiteNotice')->first()
            ->toArray();
        $appName = App\Setting::where('name', 'appName')->first()->toArray();

        $this->get('/settings?name[]=websiteNotice&name[]=appName')
             ->seeJson([
                 'websiteNotice' => $websiteNotice,
                 'appName'       => $appName
             ])
             ->assertResponseOk();
    }

    public function testGetSettingWebsiteNoticeAndPersonalContactEmail()
    {
        $this->actingAs($this->getSuperAdmin())
             ->get('/settings?name[]=websiteNotice&name[]=personalContactEmail')
             ->assertResponseOk();
    }

    public function testGetSettingWebsiteNoticeAndPersonalContactEmailWithoutAuth()
    {
        $this->get('/settings?name[]=websiteNotice&name[]=personalContactEmail')
             ->assertResponseStatus(403);
    }

    public function testGetSettingWebsiteNoticeAndNonexistentSetting()
    {
        $setting = str_random(10);

        $this->actingAs($this->getSuperAdmin())
             ->get('/settings?name[]=websiteNotice&name[]=' . $setting)
             ->assertResponseStatus(404);        
    }

    public function testGetSetting()
    {
        $this->get('/settings/1')
             ->assertResponseStatus(405);
    }

    public function testPostSetting()
    {
        $this->post('/settings')
             ->assertResponseStatus(405);
    }

    public function testPostSettingWithAuth()
    {
        $this->actingAs($this->getSuperAdmin())
             ->post('/settings')
             ->assertResponseStatus(405);
    }

    public function testPutSettingWebsiteNotice()
    {
        $notice = App\Setting::where('name', 'websiteNotice')->first();
        $payload = ['value' => 'something_else'];

        $this->actingAs($this->getAdmin())
             ->put('/settings/' . $notice->id, $payload)
             ->seeJson($payload)
             ->assertResponseOk();
    }

    public function testPutSettingWebsiteNoticeWithoutAuth()
    {
        $notice = App\Setting::where('name', 'websiteNotice')->first();

        $this->put('/settings/' . $notice->id, ['value' => 'something_else'])
             ->assertResponseStatus(403);
    }

    public function testDeleteSetting()
    {
        $this->delete('/settings/1')
             ->assertResponseStatus(405);
    }
}
