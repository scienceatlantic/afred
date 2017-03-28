<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FacilityUpdateLinkTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetFacilityUpdateLinks()
    {
        $this->actingAs($this->getAdmin())
             ->get('/facility-update-links')
             ->assertResponseOk();
    }

    public function testGetFacilityUpdateLinksWithPagination()
    {
        $this->actingAs($this->getAdmin())
             ->get('/facility-update-links')
             ->seeJson([
                 'prevPageUrl' => null
             ]);
    }

    public function testGetFacilityUpdateLinksWithoutPagination()
    {
        $this->actingAs($this->getAdmin())
             ->get('/facility-update-links?paginate=0')->dontSeeJson([
                 'prevPageUrl' => null
             ]);        
    }
    
    public function testGetFacilityUpdateLinksWithoutAuth()
    {
        $this->get('/facility-update-links')->assertResponseStatus(403);
    }
    
    public function testPostOpenFacilityUpdateLinkWithPrimaryContactEmail()
    {
        $fr = $this->getPublishedFr('model');
        $pc = $fr->publishedFacility->primaryContact;
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $pc->email,
        ];

        $resp = $this->post('/facility-update-links', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updateRequest = json_decode($resp);

        // Token should not be returned when not logged in.
        $this->assertEquals($updateRequest->token, null);

        $updateRequest = App\FacilityUpdateLink::find($updateRequest->id);

        $this->seeInTable('facility_update_links', $updateRequest, null, [
            'frIdBefore' => $fr->id,
            'frIdAfter' => null,
            'editorFirstName' => $pc->firstName,
            'editorLastName' => $pc->lastName,
            'editorEmail' => $pc->email,
            'status' => 'OPEN',
            'datePending' => null,
            'dateClosed' => null
        ]);
    }
    
    public function testPostOpenFacilityUpdateLinkWithContactEmail()
    {
        $fr = $this->getPublishedFr('model');
        $c = $fr->publishedFacility->contacts()->first();
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $c->email,
        ];

        $resp = $this->post('/facility-update-links', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updateRequest = json_decode($resp);

        // Token should not be returned when not logged in.
        $this->assertEquals($updateRequest->token, null);

        $updateRequest = App\FacilityUpdateLink::find($updateRequest->id);

        // Do not check 'firstName' and 'lastName' columns because the email 
        // address could have been found in the 'primary_contacts' table under
        // a different name.
        $this->seeInTable('facility_update_links', $updateRequest, null, [
            'frIdBefore' => $fr->id,
            'frIdAfter' => null,
            'editorEmail' => $c->email,
            'status' => 'OPEN',
            'datePending' => null,
            'dateClosed' => null
        ]);
    }

    public function testPostOpenFacilityUpdateLinkAsAdmin()
    {
        $fr = $this->getPublishedFr('model');
        $admin = $this->getAdmin();
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $admin->email,
            'isAdmin' => true
        ];

        $resp = $this->actingAs($admin)
                     ->post('/facility-update-links', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updateRequest = json_decode($resp);

        // Token should be returned when logged in.
        $this->assertNotEquals($updateRequest->token, null);

        $updateRequest = App\FacilityUpdateLink::find($updateRequest->id);

        $this->seeInTable('facility_update_links', $updateRequest, null, [
            'frIdBefore' => $fr->id,
            'frIdAfter' => null,
            'editorfirstName' => $admin->firstName,
            'editorLastName' => $admin->lastName,
            'editorEmail' => $admin->email,
            'status' => 'OPEN',
            'datePending' => null,
            'dateClosed' => null
        ]);
    }

    public function testPostOpenFacilityUpdateLinkAsAdminWithoutAuth()
    {
        $fr = $this->getPublishedFr('model');
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $this->getAdmin()->email,
            'isAdmin' => true
        ];

        $this->post('/facility-update-links', $payload)
             ->assertResponseStatus(403);    
    }

    public function testPostOpenAlreadyOpenedFacilityUpdateLinksWithPrimaryContactEmail()
    {
        $fr = $this->getPublishedFr('model');
        $pc = $fr->publishedFacility->primaryContact;
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $pc->email,
        ];

        $this->post('/facility-update-links', $payload)
             ->assertResponseStatus(200);
        $this->post('/facility-update-links', $payload)
             ->assertResponseStatus(400);
    }

    public function testPostOpenAlreadyOpenedFacilityUpdateLinkWithContactEmail()
    {
        $fr = $this->getPublishedFr('model');
        $c = $fr->publishedFacility->contacts()->first();
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $c->email,
        ];

        $this->post('/facility-update-links', $payload)
             ->assertResponseStatus(200);
        $this->post('/facility-update-links', $payload)
             ->assertResponseStatus(400);
    }

    public function testPostOpenAlreadyOpenedFacilityUpdateLinkAsAdmin()
    {
        $fr = $this->getPublishedFr('model');
        $admin = $this->getAdmin();
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $admin->email,
            'isAdmin' => true
        ];

        $this->actingAs($admin)
             ->post('/facility-update-links', $payload)
             ->assertResponseStatus(200);
        $this->actingAs($admin)
             ->post('/facility-update-links', $payload)
             ->assertResponseStatus(400);
    }

    public function testPutCloseFacilityUpdateLinkOpenedByPrimaryContact()
    {
        $fr = $this->getPublishedFr('model');
        $pc = $fr->publishedFacility->primaryContact;
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $pc->email,
        ];

        $resp = $this->post('/facility-update-links', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();                       
        $updateRequest = json_decode($resp);

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-update-links/' . $updateRequest->id)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updateRequest = json_decode($resp);

        $updateRequest = App\FacilityUpdateLink::find($updateRequest->id);

        $this->seeInTable('facility_update_links', $updateRequest, null, [
            'frIdBefore' => $fr->id,
            'frIdAfter' => null,
            'editorfirstName' => $pc->firstName,
            'editorLastName' => $pc->lastName,
            'editorEmail' => $pc->email,
            'status' => 'CLOSED',
            'datePending' => null
        ]);
    }

    public function testPutCloseFacilityUpdateLinkOpenedByPrimaryContactWithoutAuth()
    {
        $fr = $this->getPublishedFr('model');
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $fr->publishedFacility->primaryContact->email
        ];

        $resp = $this->post('/facility-update-links', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();                       
        $updateRequest = json_decode($resp);

        $this->put('/facility-update-links/' . $updateRequest->id)
             ->assertResponseStatus(403);
    }

    public function testDeleteCloseFacilityUpdateLink()
    {
        $updateRequest = $this->getOpenFul('model');

        $this->actingAs($this->getAdmin())
             ->delete('/facility-update-links/' . $updateRequest->id)
             ->assertResponseStatus(200);
    }
    
    public function testDeleteAlreadyOpenedFacilityUpdateLinkOpenedByPrimaryContactWithoutAuth()
    {
        $fr = $this->getPublishedFr('model');
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $fr->publishedFacility->primaryContact->email,
        ];

        $resp = $this->post('/facility-update-links', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();                       
        $updateRequest = json_decode($resp);

        $this->delete('/facility-update-links/' . $updateRequest->id)
             ->assertResponseStatus(403);        
    }

    public function testDeleteAlreadyPendingFacilityUpdateLinkOpenedByPrimaryContact()
    {
        $fr = $this->getPublishedFr('model');
        $pc = $fr->publishedFacility()->first()->primaryContact()->first();
        $payload = [
            'facilityId' => $fr->facilityId,
            'email' => $pc->email,
        ];

        $resp = $this->post('/facility-update-links', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();                       
        $updateRequest = App\FacilityUpdateLink::find(json_decode($resp)->id);

        $updateRequest->status = 'PENDING';
        $updateRequest->save();

        $this->delete('/facility-update-links/' . $updateRequest->id)
             ->assertResponseStatus(403);        
    }
}
