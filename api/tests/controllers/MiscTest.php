<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MiscTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetFacilityRepositoryBreakdown()
    {
        $this->actingAs($this->getAdmin())
             ->get('/misc?item=facilityRepositoryBreakdown')
             ->assertResponseOk();
    }

    public function testGetFacilityRepositoryBreakdownWithoutAuth()
    {
        $this->get('/misc?item=facilityRepositoryBreakdown')
             ->assertResponseStatus(403);        
    }

    public function testGetRandomEquipment()
    {
        $this->get('/misc?item=randomEquipment')
             ->assertResponseOk();
    }

    public function testGetSearchFilters()
    {
        $this->get('/misc?item=searchFilters')
             ->assertResponseOk();
    }

    public function testGetRefreshSearchIndicesWithoutAlgoliaIdAndKey()
    {
        $this->actingAs($this->getAdmin())
             ->get('/misc?item=refreshSearchIndices')
             ->assertResponseStatus(500);
    }

    public function testGetRefreshSearchIndicesWithoutAuth()
    {
        $this->get('/misc?item=refreshSearchIndices')
             ->assertResponseStatus(403);
    }

    public function testGetSearchIndicesWithoutAlgoliaIdAndKey()
    {
        $this->actingAs($this->getAdmin())
             ->get('/misc?item=searchIndices')
             ->assertResponseStatus(500);
    }

    public function testGetSearchIndicesWithoutAuth()
    {
        $this->get('/misc?item=searchIndices')
             ->assertResponseStatus(403);
    }

    public function testGetFacilitiesByEmailWithUnclosedUpdateRequests()
    {
        $email = 'afred.dev+testing@scienceatlantic.ca';

        $this->get('/misc?item=facilitiesByEmailWithUnclosedUpdateRequests&email=' . $email)
             ->assertResponseOk();        
    }

    public function testGetFacilitiesByEmailWithUnclosedUpdateRequestsWithoutEmail()
    {
        $this->get('/misc?item=facilitiesByEmailWithUnclosedUpdateRequests&email=')
             ->seeJson([
                 'total' => 0
             ])
             ->assertResponseOk();
    }

    public function testGetInvalidItem()
    {
        $this->get('/misc?item=something')
             ->assertResponseStatus(404);
    }

    public function testGetWithoutItemAttr()
    {
        $this->get('/misc')
             ->assertResponseStatus(404);
    }
}
