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
}
