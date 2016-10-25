<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IloTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetIlos()
    {
        $this->get('/ilos')->assertResponseOk();
    }

    public function testGetIlosWithPagination()
    {
        $this->get('/ilos')->seeJson([
            'prevPageUrl' => null
        ]);
    }

    public function testGetIlosWithoutPagination()
    {
        $this->get('/ilos?paginate=0')->dontSeeJson([
            'prevPageUrl' => null
        ]);        
    }

    public function testGetIlo()
    {
        $o = factory(App\Organization::class)->create();
        $i = factory(App\Ilo::class)->create([
            'organizationId' => $o->id
        ]);
        $this->get('/ilos/' . $i->id)->seeJson([
            'id' => $i->id
        ]);
    }

    public function testPostIlo()
    {
        $o = factory(App\Organization::class)->create();
        $i = factory(App\Ilo::class)->make([
            'organizationId' => $o->id
        ]);
        $this->actingAs($this->getAdmin())
             ->post('/ilos', $i->toArray())
             ->assertResponseOk();   
    }
    
    public function testPostIloOrganizationIdAttrInvalid()
    {
        $i = factory(App\Ilo::class)->make([
            'organizationId' => 0
        ]);
        $this->actingAs($this->getAdmin())
             ->post('/ilos', $i->toArray())
             ->assertResponseStatus('302'); 
    }

    public function testPostIloWithoutAuth()
    {
        $this->post('/ilos')->assertResponseStatus('403');
    }
    
    public function testPostIloInvalidPath()
    {
        $this->post('/ilos/1')->assertResponseStatus('405');
    }

    public function testPutIlo()
    {
        $o = factory(App\Organization::class)->create();
        $i = factory(App\Ilo::class)->create([
            'organizationId' => $o->id
        ]);
        $i2 = factory(App\Ilo::class)->make([
            'id' => $i->id,
            'organizationId' => $i->organizationId
        ]);
        $this->actingAs($this->getAdmin())
             ->put('/ilos/' . $i->id, $i2->toArray())
             ->assertResponseOk();
    }

    public function testPutIloDoesNotExist()
    {
        $o = factory(App\Organization::class)->create();
        $i = factory(App\Ilo::class)->create([
            'organizationId' => $o->id
        ]);
        $i2 = factory(App\Ilo::class)->make([
            'id' => $i->id,
            'organizationId' => $i->organizationId
        ]);
        $this->actingAs($this->getAdmin())
             ->put('/ilos/0', $i2->toArray())
             ->assertResponseStatus(404);
    }   

    public function testPutIloWithoutAuth()
    {
        $this->put('/ilos/1')->assertResponseStatus('403');
    }

    public function testPutIloInvalidPath()
    {
        $this->put('/ilos')->assertResponseStatus('405');
    }

    public function testDeleteIlo()
    {
        $o = factory(App\Organization::class)->create();
        $i = factory(App\Ilo::class)->create([
            'organizationId' => $o->id
        ]);
        $this->actingAs($this->getAdmin())
             ->delete('/ilos/' . $i->id)
             ->assertResponseOk();
    }

    public function testDeleteIlosWithoutAuth()
    {
        $this->delete('/ilos/1')->assertResponseStatus('403');   
    }

    public function testDeleteIlosInvalidPath()
    {
        $this->delete('/ilos')->assertResponseStatus('405');   
    }
}
