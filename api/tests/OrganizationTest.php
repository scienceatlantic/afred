<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrganizationTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetOrganizations()
    {
        $this->get('/organizations')->assertResponseOk();
    }

    public function testGetOrganizationsWithPagination()
    {
        $this->get('/organizations')->seeJson([
            'prevPageUrl' => null
        ]);
    }

    public function testGetOrganizationsWithoutPagination()
    {
        $this->get('/organizations?paginate=0')->dontSeeJson([
            'prevPageUrl' => null
        ]);        
    }

    public function testGetOrganization()
    {
        $o = factory(App\Organization::class)->create();
        $this->get('/organizations/' . $o->id)->seeJson([
            'id' => $o->id
        ]);
    }

    public function testPostOrganization()
    {
        $payload = factory(App\Organization::class)->make()->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->post('/organizations', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $o = json_decode($resp);

        $this->seeInTable('organizations', $payload, null, [
            'id' => $o->id,
        ]); 
    }

    public function testPostOrganizationNameAttrMissing()
    {
        $payload = factory(App\Organization::class)->make([
            'name' => null
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/organizations', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostOrganizationIsHiddenAttrMissing()
    {
        $payload = factory(App\Organization::class)->make([
            'isHidden' => null
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/organizations', $payload)
             ->assertResponseStatus(302);   
    }

    public function testPostOrganizationIsHiddenAttrInvalid()
    {
        $payload = factory(App\Organization::class)->make([
            'isHidden' => 3
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/organizations', $payload)
             ->assertResponseStatus(302);    
    }

    public function testPostOrganizationWithIdenticalName()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $payload = factory(App\Organization::class)->make([
            'name' => $o->name
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/organizations', $payload)
             ->assertResponseStatus(403);        
    }

    public function testPostOrganizationWithoutAuth()
    {
        $this->post('/organizations')->assertResponseStatus(403);
    }

    public function testPutOrganization()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $payload = factory(App\Organization::class)->make([
            'id' => $o->id
        ])->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/organizations/' . $o->id, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updO = json_decode($resp);

        $this->seeInTable('organizations', $payload, null, [
            'id' => $updO->id
        ]);
    }

     public function testPutOrganizationDoesNotExist()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $payload = factory(App\Organization::class)->make([
            'id' => $o->id
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->put('/organizations/0', $payload)
             ->assertResponseStatus(404);
    }   

    public function testPutOrganizationWithoutAuth()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $payload = factory(App\Organization::class)->make([
            'id' => $o->id
        ])->toArray();

        $this->put('/organizations/' . $o->id, $payload)
             ->assertResponseStatus(403);
    }

    public function testDeleteOrganization()
    {
        $o = factory(App\Organization::class, 'withDates')->create();

        $this->actingAs($this->getAdmin())
             ->delete('/organizations/' . $o->id)
             ->assertResponseOk();

        $this->notSeeInTable('organizations', ['id' => $o->id]);
    }

    public function testDeleteOrganizationWithForeignKeyRestraint()
    {
        $fr = $this->getPublishedFr('model');
        $f = $fr->publishedFacility;

        $this->actingAs($this->getAdmin())
             ->delete('/organizations/' . $f->organizationId)
             ->assertResponseStatus(403);

        $this->seeInTable('organizations', ['id' => $f->organizationId]);
    }

    public function testDeleteOrganizationsWithoutAuth()
    {
        $o = factory(App\Organization::class, 'withDates')->create();

        $this->delete('/organizations/' . $o->id)
             ->assertResponseStatus(403);

        $this->seeInTable('organizations', ['id' => $o->id]);   
    }
}
