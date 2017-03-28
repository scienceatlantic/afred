<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FacilityTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetFacilities()
    {
        $this->get('/facilities')->assertResponseOk();
    }

    public function testGetFacilitiesWithPagination()
    {
        $this->get('/facilities')->seeJson([
            'prevPageUrl' => null
        ]);
    }

    public function testGetFacilitiesWithoutPagination()
    {
        $this->get('/facilities?paginate=0')->dontSeeJson([
            'prevPageUrl' => null
        ]);        
    }

    public function testGetFacility()
    {
        $fr = $this->getPublishedFr();

        $this->get('/facilities/' . $fr->facilityId)->seeJson([
            'id' => $fr->facilityId
        ]);
    }

    public function testPostFacility()
    {
        $this->post('/facilities')
             ->assertResponseStatus(405);   
    }

    public function testPostFacilityWithAuth()
    {
        $this->actingAs($this->getAdmin())
             ->post('/facilities')
             ->assertResponseStatus(405);  
    }

    public function testPutHideFacility()
    {
        $fr = $this->getPublishedFr('model');
        $payload = ['isPublic' => 0];

        $oriF= $fr->publishedFacility;
        $oriF->isPublic = 1;
        $oriF->save();

        $this->seeInTable('facilities', $oriF, [
            'isPublic' => 1
        ]);

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facilities/' . $fr->facilityId, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $hiddenF = App\Facility::find(json_decode($resp)->id);

        $this->seeInTable('facilities', $hiddenF, [
            'isPublic' => 0
        ]);
    }

    public function testPutShowFacility()
    {
        $fr = $this->getPublishedFr('model');
        $payload = ['isPublic' => 1];

        $oriF = $fr->publishedFacility;
        $oriF->isPublic = 0;
        $oriF->save();

        $this->seeInTable('facilities', $oriF, [
            'isPublic' => 0
        ]);

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facilities/' . $fr->facilityId, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $hiddenF = App\Facility::find(json_decode($resp)->id);

        $this->seeInTable('facilities', $hiddenF, [
            'isPublic' => 1
        ]);
    }

    public function testPutHideFacilityWithoutAuth()
    {
        $fr = $this->getPublishedFr('model');
        $payload = ['isPublic' => 0];

        $this->put('/facilities/' . $fr->facilityId, $payload)
             ->assertResponseStatus(403);
    }

    public function testPutShowFacilityWithoutAuth()
    {
        $fr = $this->getPublishedFr('model');
        $payload = ['isPublic' => 1];

        $this->put('/facilities/' . $fr->facilityId, $payload)
             ->assertResponseStatus(403);
    }

    public function testPutShowFacilityWithoutIsPublicAttr()
    {
        $fr = $this->getPublishedFr('model');

        $this->actingAs($this->getAdmin())
             ->put('/facilities/' . $fr->facilityId)
             ->assertResponseStatus(302);        
    }

    public function testPutHideFacilityWithInvalidIsPublicAttr()
    {
        $fr = $this->getPublishedFr('model');
        $payload = ['isPublic' => 2];

        $this->actingAs($this->getAdmin())
             ->put('/facilities/' . $fr->facilityId, $payload)
             ->assertResponseStatus(302);
    }

    public function testDeleteFacility()
    {
        $fr = $this->getPublishedFr('model');
        $f = $fr->publishedFacility;

        // Check that the facility and all its relationships exist.
        $this->seeInTable('facilities', $f);
        $this->seeInTable('contacts', $f->contacts);
        $this->seeInBridgeTable('discipline_facility', 
            $f->disciplines->pluck('id')->toArray(), $f->id);
        $this->seeInTable('organizations', $f->organization);
        $this->seeInTable('primary_contacts', $f->primaryContact);
        $this->seeInBridgeTable('facility_sector', $f->id, 
            $f->sectors->pluck('id')->toArray());

        $this->actingAs($this->getAdmin())
             ->delete('/facilities/' . $f->id)
             ->assertResponseOk();

        // Alias to shorten code.
        $row = ['facilityId' => $f->id];

        // Assert that the facility and all its relationships no longer exist.
        $this->notSeeInTable('facilities', ['id' => $f->id]);
        $this->notSeeInTable('discipline_facility', $row);
        $this->notSeeInTable('primary_contacts', $row);
        $this->notSeeInTable('facility_sector', $row);

        // Assert that the facility repository record still exists.
        $this->seeInTable('facility_repository', $fr, [
            'data', 
            'publishedFacility'
        ]);

        // Assert that the facility repository model relationship no longer
        // exists.
        $this->assertEquals($fr->publishedFacility()->first(), null);     
    }

    public function testDeleteFacilityWithOpenUpdateRequest()
    {
        $updateRequest = $this->getOpenFul('model');
        $fr = $updateRequest->originalFr;

        $this->actingAs($this->getAdmin())
             ->delete('/facilities/' . $fr->facilityId)
             ->assertResponseStatus(403);
    }

    public function testDeleteFacilityWithPendingUpdateRequest()
    {
        $fr = $this->getPendingEditApprovalFr('model');

        $this->actingAs($this->getAdmin())
             ->delete('/facilities/' . $fr->facilityId)
             ->assertResponseStatus(403);
    }

    public function testDeleteFacilityWithOpenUpdateRequestWithoutAuth()
    {
        $updateRequest = $this->getOpenFul('model');
        $fr = $updateRequest->originalFr;

        $this->delete('/facilities/' . $fr->facilityId)
             ->assertResponseStatus(403);
    }

    public function testDeleteFacilityWithPendingUpdateRequestWithoutAuth()
    {
        $fr = $this->getPendingEditApprovalFr('model');

        $this->delete('/facilities/' . $fr->facilityId)
             ->assertResponseStatus(403);
    }
}
