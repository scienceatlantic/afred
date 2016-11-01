<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SectorTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetSectors()
    {
        $this->get('/sectors')->assertResponseOk();
    }

    public function testGetSectorsWithPagination()
    {
        $this->get('/sectors')->seeJson([
            'prevPageUrl' => null
        ]);
    }

    public function testGetSectorsWithoutPagination()
    {
        $this->get('/sectors?paginate=0')->dontSeeJson([
            'prevPageUrl' => null
        ]);        
    }

    public function testGetSector()
    {
        $s = factory(App\Sector::class, 'withDates')->create();

        $this->get('/sectors/' . $s->id)->seeJson([
            'id' => $s->id
        ]);
    }

    public function testPostSector()
    {
        $payload = factory(App\Sector::class)->make()->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->post('/sectors', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $p = json_decode($resp);

        $this->seeInTable('sectors', $payload, null, [
            'id' => $p->id
        ]);
    }

    public function testPostSectorNameAttrMissing()
    {
        $payload = factory(App\Sector::class)->make([
            'name' => null
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/sectors', $payload)
             ->assertResponseStatus(302);   
    }

    public function testPostSectorWithIdenticalNameAttr()
    {
        $s = factory(App\Sector::class, 'withDates')->create();
        $payload = factory(App\Sector::class)->make([
            'name' => $s->name
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/sectors', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostSectorWithoutAuth()
    {
        $payload = factory(App\Sector::class)->make()->toArray();

        $this->post('/sectors', $payload)
             ->assertResponseStatus(403);
    }

    public function testPutSector()
    {
        $s = factory(App\Sector::class, 'withDates')->create();
        $payload = factory(App\Sector::class)->make()->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/sectors/' . $s->id, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updS = json_decode($resp);

        $this->seeInTable('sectors', $payload, null, [
            'id' => $updS->id
        ]);
    }

    public function testPutSectorWithoutUpdatingNameAttr()
    {
        $s = factory(App\Sector::class, 'withDates')->create();
        $payload = factory(App\Sector::class)->make([
            'name' => $s->name
        ])->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/sectors/' . $s->id, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updS = json_decode($resp);                      

        $this->seeInTable('sectors', $payload, [
            'id' => $updS->id
        ]);
    }

    public function testPuttSectorWithIdenticalNameAttr()
    {
        $s = factory(App\Sector::class, 'withDates')->create();
        $s2 = factory(App\Sector::class, 'withDates')->create();
        $payload = factory(App\Sector::class)->make([
            'name' => $s2->name
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->put('/sectors/' . $s->id, $payload)
             ->assertResponseStatus(302);
    }

     public function testPutSectorDoesNotExist()
    {
        $payload = factory(App\Sector::class)->make()->toArray();

        $this->actingAs($this->getAdmin())
             ->put('/sectors/0', $payload)
             ->assertResponseStatus(404);
    }   

    public function testPutSectorWithoutAuth()
    {
        $s = factory(App\Sector::class, 'withDates')->create();
        $payload = factory(App\Sector::class)->make()->toArray();

        $this->put('/sectors/' . $s->id, $payload)
             ->assertResponseStatus(403);
    }

    public function testDeleteSector()
    {
        $s = factory(App\Sector::class, 'withDates')->create();

        $this->actingAs($this->getAdmin())
             ->delete('/sectors/' . $s->id)
             ->assertResponseStatus(200);
    
        $this->notSeeInTable('sectors', ['id' => $s->id]);
    }

    public function testDeleteSectorWithForeignKeyRestraint()
    {
        $fr = $this->getPublishedFr('model');
        $f = $fr->publishedFacility;

        $this->actingAs($this->getAdmin())
             ->delete('/sectors/' . $f->sectors[0]->id)
             ->assertResponseStatus(400);

        $this->seeInTable('sectors', ['id' => $f->sectors[0]->id]);
    }

    public function testDeleteSectorsWithoutAuth()
    {
        $s = factory(App\Sector::class, 'withDates')->create();

        $this->delete('/sectors/' . $s->id)
             ->assertResponseStatus(403);   
    }
}
