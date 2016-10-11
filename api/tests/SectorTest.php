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
        $s = factory(App\Sector::class)->create();
        $this->get('/sectors/' . $s->id)->seeJson([
            'id' => $s->id
        ]);
    }

    public function testPostSector()
    {
        $this->actingAs($this->getAdmin())
             ->post('/sectors', [
                 'name' => 'test'
             ])
             ->assertResponseOk();   
    }

    public function testPostSectorNameAttrMissing()
    {
        $this->actingAs($this->getAdmin())
             ->post('/sectors')
             ->assertResponseStatus('302');   
    }

    public function testPostSectorWithoutAuth()
    {
        $this->post('/sectors')->assertResponseStatus('403');
    }

    public function testPostSectorInvalidPath()
    {
        $this->post('/sectors/1')->assertResponseStatus('405');
    }

    public function testPutSector()
    {
        $s = factory(App\Sector::class)->create();
        $this->actingAs($this->getAdmin())
             ->put('/sectors/' . $s->id, [
                 'name' => 'new_test'
             ])
             ->assertResponseOk();
    }

     public function testPutSectorDoesNotExist()
    {
        $this->actingAs($this->getAdmin())
             ->put('/sectors/0', [
                 'name' => 'new_test'
             ])
             ->assertResponseStatus(404);
    }   

    public function testPutSectorWithoutAuth()
    {
        $this->put('/sectors/1')->assertResponseStatus('403');
    }

    public function testPutSectorInvalidPath()
    {
        $this->put('/sectors')->assertResponseStatus('405');
    }

    public function testDeleteSector()
    {
        $s = factory(App\Sector::class)->create();
        $this->actingAs($this->getAdmin())
             ->delete('/sectors/' . $s->id)
             ->assertResponseOk();
    }

    public function testDeleteSectorWithForeignKeyRestraint()
    {
        $f = App\Facility::with('sectors')->first();
        $this->actingAs($this->getAdmin())
             ->delete('/sectors/' . $f->sectors[0]->id)
             ->assertResponseStatus('400');
    }

    public function testDeleteSectorsWithoutAuth()
    {
        $this->delete('/sectors/1')->assertResponseStatus('403');   
    }

    public function testDeleteSectorsInvalidPath()
    {
        $this->delete('/sectors')->assertResponseStatus('405');   
    }
}
