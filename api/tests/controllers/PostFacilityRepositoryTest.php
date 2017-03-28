<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostFacilityRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testPostPendingApprovalFacilityRepository()
    {
        $data = self::createFrDataAttr();
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => $data
        ])->toArray();

        $resp = $this->post('/facility-repository', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $fr = App\FacilityRepository::find(json_decode($resp)->id);

        // Assert facility repository record is saved with the right state.
        $this->seeInTable('facility_repository', $payload, ['data'], [
            'id' => $fr->id,
            'state' => 'PENDING_APPROVAL'
        ]);

        // Assert the contents of the `$data` attribute.
        $this->seeInArray($fr->data['contacts'], $data['contacts']);
        $this->assertEquals($fr->data['disciplines'], $data['disciplines']);
        $this->seeInArray($fr->data['facility'], $data['facility']);
        $this->seeInArray($fr->data['equipment'], $data['equipment']);
        $this->seeInArray($fr->data['primaryContact'], $data['primaryContact']);
        $this->assertEquals($fr->data['sectors'], $data['sectors']);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNoContactsAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(0)
        ])->toArray();

        $resp = $this->post('/facility-repository', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $fr = App\FacilityRepository::find(json_decode($resp)->id);
        
        $this->assertArrayNotHasKey('contacts', $fr->data);
    }

    public function testPostPendingApprovalFacilityRepositoryWithEmptyContactsAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(0)
        ])->toArray();
        $payload['data']['contacts'] = [];

        $resp = $this->post('/facility-repository', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $fr = App\FacilityRepository::find(json_decode($resp)->id);
        
        $this->assertArrayNotHasKey('contacts', $fr->data);
    }

    public function testPostPendingApprovalFacilityRepositoryWithMinContactsAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::MIN_NUM_CONTACTS_IN_FR_DATA_ATTR
            )
        ])->toArray();

        $resp = $this->post('/facility-repository', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $fr = App\FacilityRepository::find(json_decode($resp)->id);
        
        $this->assertEquals(count($fr->data['contacts']), 
            self::MIN_NUM_CONTACTS_IN_FR_DATA_ATTR);
    }

    public function testPostPendingApprovalFacilityRepositoryWithMaxNumContactsAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::MAX_NUM_CONTACTS_IN_FR_DATA_ATTR
            )
        ])->toArray();

        $resp = $this->post('/facility-repository', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $fr = App\FacilityRepository::find(json_decode($resp)->id);

        $this->assertEquals(count($fr->data['contacts']), 
            self::MAX_NUM_CONTACTS_IN_FR_DATA_ATTR);
    }

    public function testPostPendingApprovalFacilityRepositoryWithTooManyContactsAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::MAX_NUM_CONTACTS_IN_FR_DATA_ATTR + 1
            )
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNoDisciplinesAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                0
            )
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithEmptyDisciplinesAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                0
            )
        ])->toArray();
        $payload['data']['disciplines'] = [];

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithInvalidDisciplineIds()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['disciplines'] = [0, -1, -2];

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNoEquipmentAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                0
            )
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithEmptyEquipmentAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                0
            )
        ])->toArray();
        $payload['data']['equipment'] = [];

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithMinNumEquipmentAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                self::MIN_NUM_EQUIPMENT_IN_FR_DATA_ATTR
            )
        ])->toArray();

        $resp = $this->post('/facility-repository', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $fr = App\FacilityRepository::find(json_decode($resp)->id);

        $this->assertEquals(count($fr->data['equipment']), 
            self::MIN_NUM_EQUIPMENT_IN_FR_DATA_ATTR);
    }

    public function testPostPendingApprovalFacilityRepositoryWithMaxNumEquipmentAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                self::MAX_NUM_EQUIPMENT_IN_FR_DATA_ATTR
            )
        ])->toArray();

        $resp = $this->post('/facility-repository', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $fr = App\FacilityRepository::find(json_decode($resp)->id);

        $this->assertEquals(count($fr->data['equipment']), 
            self::MAX_NUM_EQUIPMENT_IN_FR_DATA_ATTR);
    }

    public function testPostPendingApprovalFacilityRepositoryWithTooManyEquipmentAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                self::MAX_NUM_EQUIPMENT_IN_FR_DATA_ATTR + 1
            )
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNoFacilityAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                self::MAX_NUM_EQUIPMENT_IN_FR_DATA_ATTR + 1,
                0
            )
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithEmptyFacilityAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                self::MAX_NUM_EQUIPMENT_IN_FR_DATA_ATTR + 1,
                0
            )
        ])->toArray();
        $payload['data']['facility'] = '';
        
        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithInvalidFacilityOrganizationIdAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['facility']['organizationId'] = 0;

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNullOrganizationIdAndWithOrganizationNameAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['facility']['organizationId'] = null;
        $payload['data']['organization'] = ['name' => 'something'];

        $resp = $this->post('/facility-repository', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $fr = App\FacilityRepository::find(json_decode($resp)->id);

        $this->assertEquals($fr->data['facility']['organizationId'], null);
        $this->assertArrayHasKey('organization', $fr->data);
        $this->seeInArray($fr->data['organization'], 
            $payload['data']['organization']);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNullOrganizationIdAndNoOrganizationNameAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['facility']['organizationId'] = null;

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNoOrganizationIdAndWithOrganizationNameAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr()
        ])->toArray();
        unset($payload['data']['facility']['organizationId']);
        $payload['data']['organization'] = ['name' => 'something'];

        $resp = $this->post('/facility-repository', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $fr = App\FacilityRepository::find(json_decode($resp)->id);

        $this->assertArrayNotHasKey('organizationId', $fr->data['facility']);
        $this->assertArrayHasKey('organization', $fr->data);
        $this->seeInArray($fr->data['organization'], 
            $payload['data']['organization']);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNoOrganizationIdAndNoOrganizationNameAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr()
        ])->toArray();
        unset($payload['data']['facility']['organizationId']);

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithInvalidFacilityProvinceIdAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr()
        ])->toArray();
        $payload['data']['facility']['provinceId'] = 0;

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNoPrimaryContactAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                self::DEF_NUM_EQUIPMENT_IN_FR_DATA_ATTR, 
                self::DEF_NUM_FACILITIES_IN_FR_DATA_ATTR, 
                0
            )
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithEmptyPrimaryContactAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                self::DEF_NUM_EQUIPMENT_IN_FR_DATA_ATTR, 
                self::DEF_NUM_FACILITIES_IN_FR_DATA_ATTR, 
                0
            )
        ])->toArray();
        $payload['data']['primaryContact'] = '';

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithTooManyPrimaryContactAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                self::DEF_NUM_EQUIPMENT_IN_FR_DATA_ATTR, 
                self::DEF_NUM_FACILITIES_IN_FR_DATA_ATTR, 
                self::DEF_NUM_PRIMARY_CONTACTS_IN_FR_DATA_ATTR + 1
            )
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNoSectorsAttr()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => self::createFrDataAttr(
                self::DEF_NUM_CONTACTS_IN_FR_DATA_ATTR, 
                self::DEF_NUM_DISCIPLINES_IN_FR_DATA_ATTR, 
                self::DEF_NUM_EQUIPMENT_IN_FR_DATA_ATTR, 
                self::DEF_NUM_FACILITIES_IN_FR_DATA_ATTR, 
                self::DEF_NUM_PRIMARY_CONTACTS_IN_FR_DATA_ATTR,
                0
            )
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithInvalidSectorIds()
    {
        $data = self::createFrDataAttr();
        $data['sectors'] = [0, -1, -2];
        $payload = factory(App\FacilityRepository::class)->make([
            'data' => $data
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostPendingApprovalFacilityRepositoryWithPublishedState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PUBLISHED',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithRejectedState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'REJECTED',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithPendingEditApprovalState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PENDING_EDIT_APPROVAL',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithPublishedEditState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PUBLISHED_EDIT',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithRejectedEditState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'REJECTED_EDIT',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithAuthAndPublishedState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PUBLISHED',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithAuthAndRejectedState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'REJECTED',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithAuthAndPendingEditApprovalState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PENDING_EDIT_APPROVAL',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithAuthAndPublishedEditState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'PUBLISHED_EDIT',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithAuthAndRejectedEditState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'REJECTED_EDIT',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithNonexistentState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'SOMETHING_RANDOM',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }

    public function testPostPendingApprovalFacilityRepositoryWithAuthAndNonexistentState()
    {
        $payload = factory(App\FacilityRepository::class)->make([
            'state' => 'SOMETHING_RANDOM',
            'data' => self::createFrDataAttr()
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/facility-repository', $payload)
             ->assertResponseStatus(403);
    }
}
