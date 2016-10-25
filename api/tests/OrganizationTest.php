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
        $this->actingAs($this->getAdmin())
             ->post('/organizations', [
                 'name' => 'test',
                 'isHidden' => 1
             ])
             ->assertResponseOk();   
    }

    public function testPostOrganizationNameAttrMissing()
    {
        $this->actingAs($this->getAdmin())
             ->post('/organizations', [
                 'isHidden' => 1
             ])
             ->assertResponseStatus('302');   
    }

    public function testPostOrganizationIsHiddenAttrMissing()
    {
        $this->actingAs($this->getAdmin())
             ->post('/organizations', [
                 'name' => 'test'
             ])
             ->assertResponseStatus('302');   
    }

    public function testPostOrganizationIsHiddenAttrInvalid()
    {
        $this->actingAs($this->getAdmin())
             ->post('/organizations', [
                 'name' => 'test',
                 'isHidden' => 3 // Should be either 0 or 1.
             ])
             ->assertResponseStatus('302');   
    }

    public function testPostOrganizationWithoutAuth()
    {
        $this->post('/organizations')->assertResponseStatus('403');
    }

    public function testPostOrganizationInvalidPath()
    {
        $this->post('/organizations/1')->assertResponseStatus('405');
    }

    public function testPutOrganization()
    {
        $o = factory(App\Organization::class)->create();
        $this->actingAs($this->getAdmin())
             ->put('/organizations/' . $o->id, [
                 'name' => 'new_test',
                 'isHidden' => 0
             ])
             ->assertResponseOk();
    }

     public function testPutOrganizationDoesNotExist()
    {
        $this->actingAs($this->getAdmin())
             ->put('/organizations/0', [
                 'name' => 'new_test',
                 'isHidden' => 0
             ])
             ->assertResponseStatus(404);
    }   

    public function testPutOrganizationWithoutAuth()
    {
        $this->put('/organizations/1')->assertResponseStatus('403');
    }

    public function testPutOrganizationInvalidPath()
    {
        $this->put('/organizations')->assertResponseStatus('405');
    }

    public function testDeleteOrganization()
    {
        $o = factory(App\Organization::class)->create();
        $this->actingAs($this->getAdmin())
             ->delete('/organizations/' . $o->id)
             ->assertResponseOk();
    }

    public function testDeleteOrganizationWithForeignKeyRestraint()
    {
        $f = App\Facility::first();
        $this->actingAs($this->getAdmin())
             ->delete('/organizations/' . $f->organizationId)
             ->assertResponseStatus('400');
    }

    public function testDeleteOrganizationsWithoutAuth()
    {
        $this->delete('/organizations/1')->assertResponseStatus('403');   
    }

    public function testDeleteOrganizationsInvalidPath()
    {
        $this->delete('/organizations')->assertResponseStatus('405');   
    }
}
