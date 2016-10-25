<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PutFacilityRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testPutPublishedFacilityRepository()
    {
        $pdnFr = $this->getPendingApprovalFr('model');
        $params = $pdnFr->id . '?state=PUBLISHED';

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-repository/' . $params)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updFr = json_decode($resp);

        // Aliases to shorten code.
        $updFid = $updFr->facilityId; 
        $pData = $pdnFr->data;
        $uData = $updFr->data;

        // Assert facility repository record.
        $this->seeInTable('facility_repository', $pdnFr, ['data'], [
            'state' => 'PUBLISHED'
        ]);

        // Assert published data.
        $this->seeInTable('contacts', $pData['contacts']);
        $this->seeInBridgeTable('discipline_facility', $pData['disciplines'], 
            $updFid);
        $this->seeInTable('equipment', $pData['equipment']);
        $this->seeInTable('facilities', $pData['facility']);
        $this->seeInTable('primary_contacts', $pData['primaryContact']);
        $this->seeInBridgeTable('facility_sector', $updFid, $pData['sectors']);
    }
    
    public function testPutRejectedFacilityRepository()
    {
        $pdnFr = $this->getPendingApprovalFr('model');      
        $params = $pdnFr->id . '?state=REJECTED';

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-repository/' . $params)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updFr = json_decode($resp);

        // Assert facility repository record.
        $this->seeInTable('facility_repository', $pdnFr, ['data'], [
            'state' => 'REJECTED'
        ]);

        // Check that the data was not published.
        $this->assertEquals($updFr->facilityId, null);
        $this->notSeeInDatabase('facilities', [
            'facilityRepositoryId' => $updFr->id
        ]);
    }

    public function testPutPublishedFacilityWithNullOrganizationIdAndWithOrganizationNameAttr()
    {
        $pdnFr = $this->getPendingApprovalFr('model');
        $params = $pdnFr->id . '?state=PUBLISHED';

        $pData = $pdnFr->data;
        $pData['facility']['organizationId'] = null;
        $pData['organization'] = ['name' => 'some new organization'];
        $pdnFr->data = $pData;
        $pdnFr->save();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-repository/' . $params)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updFr = App\FacilityRepository::find(json_decode($resp)->id);

        $this->seeInTable('organizations', $pData['organization'], null, [
            'id' => $updFr->publishedFacility->organizationId
        ]);
    }

    public function testPutPublishedFacilityWithNullOrganizationIdAndWithOrganizationNameAttrThatAlreadyExists()
    {
        $pdnFr = $this->getPendingApprovalFr('model');
        $params = $pdnFr->id . '?state=PUBLISHED';

        $o = factory(App\Organization::class)->create([
            'name' => 'some new organization'
        ]);
        $pData = $pdnFr->data;
        $pData['facility']['organizationId'] = null;
        $pData['organization'] = ['name' => 'some new organization'];
        $pdnFr->data = $pData;
        $pdnFr->save();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-repository/' . $params)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updFr = App\FacilityRepository::find(json_decode($resp)->id);

        $this->assertEquals($updFr->publishedFacility->organizationId, $o->id);
    }
    
    public function testPutPublishedFacilityRepositoryWithoutAuth()
    {
        $fr = $this->getPendingApprovalFr();      
        $params = $fr->id . '?state=PUBLISHED';

        $this->put('/facility-repository/' . $params)
             ->assertResponseStatus(403);
    } 

    public function testPutRejectedFacilityRepositoryWithoutAuth()
    {
        $fr = $this->getPendingApprovalFr();       
        $params = $fr->id . '?state=REJECTED';

        $this->put('/facility-repository/' . $params)
             ->assertResponseStatus(403);
    }

    public function testPutPublishedFacilityRepositoryWithInvalidCurrentState()
    {
        $fr = $this->getPendingApprovalFr('model');
        $fr->state = 'PENDING_EDIT_APPROVAL';
        $fr->save();    
        $params = $fr->id . '?state=PUBLISHED';

        $this->actingAs($this->getAdmin())
             ->put('/facility-repository/' . $params)
             ->assertResponseStatus(403);
    }

    public function testPutRejectedFacilityRepositoryWithInvalidCurrentState()
    {
        $fr = $this->getPendingApprovalFr('model');
        $fr->state = 'PENDING_EDIT_APPROVAL';
        $fr->save();      
        $params = $fr->id . '?state=REJECTED';

        $this->actingAs($this->getAdmin())
             ->put('/facility-repository/' . $params)
             ->assertResponseStatus(403);
    }

    public function testPutPublishedFacilityRepositoryWithInvalidCurrentStateAndWithoutAuth()
    {
        $fr = $this->getPendingApprovalFr('model');
        $fr->state = 'PENDING_EDIT_APPROVAL';
        $fr->save();      
        $params = $fr->id . '?state=PUBLISHED';

        $this->put('/facility-repository/' . $params)
             ->assertResponseStatus(403);
    }

    public function testPutRejectedFacilityRepositoryWithInvalidCurrentStateAndWithoutAuth()
    {
        $fr = $this->getPendingApprovalFr('model');
        $fr->state = 'PENDING_EDIT_APPROVAL';
        $fr->save();        
        $params = $fr->id . '?state=REJECTED';

        $this->put('/facility-repository/' . $params)
             ->assertResponseStatus(403);
    }

    public function testPutPendingEditApprovalFacilityRepository()
    {
        $updateRequest = $this->getOpenFul('model');
        $pdnFr = $updateRequest->originalFr;
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PENDING_EDIT_APPROVAL',
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['facility']['id'] = $pdnFr->data['facility']['id'];
        $params = $pdnFr->id . '?token=' . $updateRequest->token;

        $resp = $this->put('/facility-repository/' . $params, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $apvFr = App\FacilityRepository::find(json_decode($resp)->id);

        // Aliases to shorten code
        $pdnFId = $pdnFr->facilityId;
        $pData = $pdnFr->data;

        // Check that the original facility repository record and the published 
        // data remain the same.
        $this->seeInBridgeTable('discipline_facility', $pData['disciplines'], 
            $pdnFId);
        $this->seeInTable('contacts', $pData['contacts']);
        $this->seeInTable('equipment', $pData['equipment']);
        $this->seeInTable('facilities', $pData['facility']);
        $this->seeInTable('facility_repository', $pdnFr, ['data']);
        $this->seeInTable('primary_contacts', $pData['primaryContact']);
        $this->seeInBridgeTable('facility_sector', $pdnFId, $pData['sectors']);

        // Check the details of the newly submitted facility repository record
        // and the updated facility update link record.
        $this->seeInTable('facility_repository', $apvFr, ['data'], [
            'facilityId' => $pdnFr->facilityId,
            'state' => 'PENDING_EDIT_APPROVAL',
        ]);
        $this->seeInTable('facility_update_links', $updateRequest, [
            'originalFr'
        ], [
            'frIdAfter' => $apvFr->id,
            'status' => 'PENDING',
            'datePending' => $apvFr->originRequest->datePending,
            'dateClosed' => null
        ]);
    }

    public function testPutPendingEditApprovalFacilityRepositoryWithAuth()
    {
        $updateRequest = $this->getOpenFul('model');
        $pdnFr = $updateRequest->originalFr;
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PENDING_EDIT_APPROVAL',
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['facility']['id'] = $pdnFr->data['facility']['id'];
        $params = $pdnFr->id . '?token=' . $updateRequest->token;

        $this->actingAs($this->getAdmin())
             ->put('/facility-repository/' . $params, $payload)
             ->assertResponseStatus(200);
    }

    public function testPutPendingEditApprovalFacilityRepositoryWithoutToken()
    {
        $ful = $this->getOpenFul('model');
        $fr = App\FacilityRepository::find($ful->frIdBefore);
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PENDING_EDIT_APPROVAL',
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['facility']['id'] = $fr->data['facility']['id'];

        $this->put('/facility-repository/' . $fr->id, $payload)
             ->assertResponseStatus(403);
    }

    public function testPutPendingEditApprovalFacilityRepositoryWithoutFacilityIdInDataAttr()
    {
        $updateRequest = $this->getOpenFul('model');
        $pdnFr = $updateRequest->originalFr;
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PENDING_EDIT_APPROVAL',
            'data' => self::createFrDataAttr()
        ])->toArray();
        $params = $pdnFr->id . '?token=' . $updateRequest->token;

        $this->put('/facility-repository/' . $params, $payload)
             ->assertResponseStatus(302);
    }

    public function testPutPendingEditApprovalFacilityRepositoryWithPublishedEditState()
    {
        $updateRequest = $this->getOpenFul('model');
        $pdnFr = $updateRequest->originalFr;
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PUBLISHED_EDIT',
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['facility']['id'] = $pdnFr->data['facility']['id'];
        $params = $pdnFr->id . '?token=' . $updateRequest->token;

        $this->put('/facility-repository/' . $params, $payload)
             ->assertResponseStatus(403);
    }

    public function testPutPendingEditApprovalFacilityRepositoryWithRejectedEditState()
    {
        $updateRequest = $this->getOpenFul('model');
        $pdnFr = $updateRequest->originalFr;
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'REJECTED_EDIT',
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['facility']['id'] = $pdnFr->data['facility']['id'];
        $params = $pdnFr->id . '?token=' . $updateRequest->token;

        $this->put('/facility-repository/' . $params, $payload)
             ->assertResponseStatus(403);
    }
    
    public function testPutPublishedEditFacilityRepository()
    {
        $pdnFr = $this->getPendingEditApprovalFr('model');
        $updateRequest = $pdnFr->originRequest;
        $oriFr = $updateRequest->originalFr;
        $params = $pdnFr->id. '?state=PUBLISHED_EDIT';

        // Aliases to shorten code.
        $oriFId = $oriFr->publishedFacility->id;
        $oriFIdArr = ['facilityId' => $oriFId];
        $oriC = $oriFr->publishedFacility->contacts;
        $oriD = $oriFr->publishedFacility->disciplines->pluck('id');
        $oriF = $oriFr->publishedFacility;
        $oriE = $oriFr->publishedFacility->equipment;
        $oriP = $oriFr->publishedFacility->primaryContact;
        $oriS = $oriFr->publishedFacility->sectors->pluck('id');

        // Assert that the original data is in the database.
        $this->seeInTable('contacts', $oriC, null, $oriFIdArr);
        $this->seeInBridgeTable('discipline_facility', $oriD, $oriFId);
        $this->seeInTable('facilities', $oriF, [
            'contacts',
            'disciplines',
            'equipment',
            'primaryContact',
            'sectors'
        ], ['id' => $oriFId]);
        $this->seeInTable('equipment', $oriE, null, $oriFIdArr);
        $this->seeInTable('primary_contacts', $oriP, null, $oriFIdArr);
        $this->seeInBridgeTable('facility_sector', $oriFId, $oriS);

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-repository/' . $params)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updFr = App\FacilityRepository::find(json_decode($resp)->id);

        // Assert that the original facility repository record has not been
        // removed from the database.
        $this->seeInTable('facility_repository', $oriFr, [
            'data',
            'publishedFacility'
        ]);

        // Assert that the 'PENDING_EDIT' facility repository record has been
        // updated to 'PUBLISHED_EDIT'.
        $this->seeInTable('facility_repository', $pdnFr, [
            'data',
            'originRequest',
        ], [
            'state' => 'PUBLISHED_EDIT',
        ]);

        // Assert that the facility update link token has been CLOSED.
        $this->seeInTable('facility_update_links', $updateRequest, [
            'originalFr'
        ], [
            'frIdBefore' => $oriFr->id,
            'frIdAfter' => $updFr->id,
            'status' => 'CLOSED',
            'dateClosed' => $updFr->originRequest->dateClosed
        ]);

        // Aliases to shorten code.
        $updC = $updFr->data['contacts'];
        $updD = $updFr->data['disciplines'];
        $updE = $updFr->data['equipment'];
        $updF = $updFr->data['facility'];
        $updP = $updFr->data['primaryContact'];
        $updS = $updFr->data['sectors'];

        // Assert that the database now contains the updated data while still
        // maintaining the same facility id.
        $this->seeInTable('contacts', $updC, null, $oriFIdArr);
        $this->seeInBridgeTable('discipline_facility', $updD, $oriFId);
        $this->seeInTable('facilities', $updF, null, ['id' => $oriFId]);
        $this->seeInTable('equipment', $updE, null, $oriFIdArr);
        $this->seeInTable('primary_contacts', $updP, null, $oriFIdArr);
        $this->seeInBridgeTable('facility_sector', $oriFId, $updS);

        // Assert that the database no longer contains the old discipline and
        // sector relationships (the tests always creates new discipline and 
        // sector IDs, so all the IDs are new).
        $this->notSeeInBridgeTable('discipline_facility', $oriD, $oriFId);
        $this->notSeeInBridgeTable('facility_sector', $oriFId, $oriS);
 
        // Format vars for tests below.
        $oriC = $oriC->map(function($v) {
            return ['id' => $v['id']];
        });
        $oriE = $oriE->map(function($v) {
            return ['id' => $v['id']];
        });
        $oriP = ['id' => $oriP->id];

        // Assert (only by IDs) that the old data is no longer in the database.
        $this->notSeeInTable('contacts', $oriC);
        $this->notSeeInTable('equipment', $oriE);
        $this->notSeeInTable('primary_contacts', $oriP);
    }

    public function testPutPublishedEditFacilityRepositoryWithSomeSameIds()
    {
        $pdnFr = $this->getPendingEditApprovalFr('model');
        $oriFr = $pdnFr->originRequest->originalFr;
        $params = $pdnFr->id. '?state=PUBLISHED_EDIT';

        // Aliases to shorten code.
        $oriFId = $oriFr->data['facility']['id'];
        $pData = $pdnFr->data;
        $oData = $oriFr->data;

        // Number of IDs we want to maintain from the original facility 
        // repository record.
        $lim = 3; 

        // Modify the `$data` property of the pending facility repository 
        // record to keep at most `$lim` identical IDs from the original
        // facility repository record before we publish it.
        foreach(array_slice($pData['contacts'], 0, $lim) as $i => $v) {
            $pData['contacts'][$i]['id'] = $oData['contacts'][$i]['id'];
        }
        foreach(array_slice($oData['disciplines'], 0, $lim) as $i => $oriDId) {
            $pData['disciplines'][$i] = $oriDId;
        }
        foreach(array_slice($pData['equipment'], 0, $lim) as $i => $v) {
            $pData['equipment'][$i]['id'] = $oData['equipment'][$i]['id'];
        }
        $pData['primaryContact']['id'] = $oData['primaryContact']['id'];
        foreach(array_slice($oData['sectors'], 0, $lim) as $i => $oriSId) {
            $pData['sectors'][$i] = $oriSId;
        }
        $pdnFr->data = $pData;
        $pdnFr->save();

        // Double check that we did not reduce the length of the arrays.
        $this->assertNotEquals(count($pdnFr->data['contacts']), $lim);
        $this->assertNotEquals(count($pdnFr->data['disciplines']), $lim);
        $this->assertNotEquals(count($pdnFr->data['equipment']), $lim);
        $this->assertNotEquals(count($pdnFr->data['sectors']), $lim);

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-repository/' . $params)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updFr = App\FacilityRepository::find(json_decode($resp)->id);
        
        // Alias to shorten code.
        $uData = $updFr->data;
        
        // Get array of IDs for the tests below.
        $oriD = array_diff($oData['disciplines'], $uData['disciplines']);
        $newD = array_diff($uData['disciplines'], $oData['disciplines']);
        $oriS = array_diff($oData['sectors'], $uData['sectors']);
        $newS = array_diff($uData['sectors'], $oData['sectors']);
        
        // Assert that the old discipline/sector relationships are no longer
        // in the database and the new ones are.
        $this->notSeeInBridgeTable('discipline_facility', $oriD, $oriFId);
        $this->seeInBridgeTable('discipline_facility', $newD, $oriFId);
        $this->notSeeInBridgeTable('facility_sector', $oriFId, $oriS);
        $this->seeInBridgeTable('facility_sector', $oriFId, $newS);

        // Assert that the first `$lim` IDs from the old facility repository 
        // record are still in the database (just the IDs) and the rest are not.
        foreach($uData['contacts'] as $i => $updC) {
            $oriCId = ['id' => $oData['contacts'][$i]['id']]; // Just ID. 
            $oriC = $oData['contacts'][$i]; // Entire row.

            if ($i < $lim) {
                $this->seeInTable('contacts', $updC, null, $oriCId);
                $this->notSeeInTable('contacts', $oriC);
            } else {
                $this->notSeeInTable('contacts', $updC, null, $oriCId);
                $this->seeInTable('contacts', $updC);
            }
        }
        foreach($uData['equipment'] as $i => $updE) {
            $oriEId = ['id' => $oData['equipment'][$i]['id']]; // Just ID.
            $oriE = $oData['equipment'][$i]; // Entire row.

            if ($i < $lim) {
                $this->seeInTable('equipment', $updE, null, $oriEId);
                $this->notSeeInTable('equipment', $oriE);
            } else {
                $this->notSeeInTable('equipment', $updE, null, $oriEId);
                $this->seeInTable('equipment', $updE);
            }
        }

        // Assert that only the primary contact ID from the original facility 
        // repository record is in the database.
        $this->seeInTable('primary_contacts', $updFr->data['primaryContact'],
            null, ['id' => $oData['primaryContact']['id']]);     
    }

    public function testPutPublishedEditFacilityWithNullOrganizationIdAndWithOrganizationNameAttr()
    {
        $pdnFr = $this->getPendingEditApprovalFr('model');
        $params = $pdnFr->id. '?state=PUBLISHED_EDIT';

        $pData = $pdnFr->data;
        $pData['facility']['organizationId'] = null;
        $pData['organization'] = ['name' => 'some new organization'];
        $pdnFr->data = $pData;
        $pdnFr->save();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-repository/' . $params)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updFr = App\FacilityRepository::find(json_decode($resp)->id);

        $this->seeInTable('organizations', $pData['organization'], null, [
            'id' => $updFr->publishedFacility->organizationId
        ]);
    }

    public function testPutPublishedEditFacilityWithNullOrganizationIdAndWithOrganizationNameAttrThatAlreadyExists()
    {
        $pdnFr = $this->getPendingEditApprovalFr('model');
        $params = $pdnFr->id. '?state=PUBLISHED_EDIT';

        $o = factory(App\Organization::class)->create([
            'name' => 'some new organization'
        ]);
        $pData = $pdnFr->data;
        $pData['facility']['organizationId'] = null;
        $pData['organization'] = ['name' => 'some new organization'];
        $pdnFr->data = $pData;
        $pdnFr->save();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/facility-repository/' . $params)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updFr = App\FacilityRepository::find(json_decode($resp)->id);

        $this->assertEquals($updFr->publishedFacility->organizationId, $o->id); 
    }
}
