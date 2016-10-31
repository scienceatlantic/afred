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
        $o = factory(App\Organization::class, 'withDates')->create();
        $i = factory(App\Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);

        $this->get('/ilos/' . $i->id)->seeJson([
            'id' => $i->id
        ]);
    }

    public function testPostIlo()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $payload = factory(App\Ilo::class)->make([
            'organizationId' => $o->id
        ])->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->post('/ilos', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $i = json_decode($resp);

        $this->seeInTable('ilos', $payload, null, [
            'id' => $i->id
        ]);
    }
    
    public function testPostIloWithInvalidOrganizationIdAttr()
    {
        $payload = factory(App\Ilo::class)->make([
            'organizationId' => 0
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/ilos', $payload)
             ->assertResponseStatus(302); 
    }

    public function testPostIloWithIdenticalOrganizationIdAttr()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $i = factory(App\Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);
        $payload = factory(App\Ilo::class)->make([
            'organizationId' => $o->id
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/ilos', $payload)
             ->assertResponseStatus(403); 
    }

    public function testPostIloWithoutAuth()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $payload = factory(App\Ilo::class)->make([
            'organizationId' => $o->id
        ])->toArray();

        $this->post('/ilos', $payload)
             ->assertResponseStatus(403);
    }

    public function testPutIlo()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $i = factory(App\Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);
        $payload = factory(App\Ilo::class)->make([
            'id' => $i->id,
            'organizationId' => $i->organizationId
        ])->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/ilos/' . $i->id, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updI = json_decode($resp);

        $this->seeInTable('ilos', $payload, null, [
            'id' => $updI->id
        ]);
    }

    public function testPutIloWithInvalidOrganizationIdAttr()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $i = factory(App\Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);
        $payload = factory(App\Ilo::class)->make([
            'id' => $i->id,
            'organizationId' => 0
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->put('/ilos/' . $i->id, $payload)
             ->assertResponseStatus(302);
    }  

    public function testPutIloWithoutAuth()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $i = factory(App\Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);
        $payload = factory(App\Ilo::class)->make([
            'id' => $i->id,
            'organizationId' => $i->organizationId
        ])->toArray();

        $this->put('/ilos/' . $i->id, $payload)
             ->assertResponseStatus(403);
    }

    public function testDeleteIlo()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $i = factory(App\Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);

        $this->actingAs($this->getAdmin())
             ->delete('/ilos/' . $i->id)
             ->assertResponseOk();

        $this->notSeeInTable('ilos', ['id' => $i->id]);
    }

    public function testDeleteIloWithoutAuth()
    {
        $o = factory(App\Organization::class, 'withDates')->create();
        $i = factory(App\Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);

        $this->delete('/ilos/' . $i->id)
             ->assertResponseStatus(403);

        $this->seeInTable('ilos', ['id' => $i->id]); 
    }
}
