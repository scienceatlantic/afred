<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProvinceTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetProvinces()
    {
        $this->get('/provinces')->assertResponseOk();
    }

    public function testGetProvincesWithPagination()
    {
        $this->get('/provinces')->seeJson([
            'prevPageUrl' => null
        ]);
    }

    public function testGetProvincesWithoutPagination()
    {
        $this->get('/provinces?paginate=0')->dontSeeJson([
            'prevPageUrl' => null
        ]);        
    }

    public function testGetProvince()
    {
        $p = factory(App\Province::class, 'withDates')->create();

        $this->get('/provinces/' . $p->id)->seeJson([
            'id' => $p->id
        ]);
    }

    public function testPostProvince()
    {
        $payload = factory(App\Province::class)->make()->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->post('/provinces', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $p = json_decode($resp);

        $this->seeInTable('provinces', $payload, null, [
            'id' => $p->id
        ]);   
    }

    public function testPostProvinceNameAttrMissing()
    {
        $payload = factory(App\Province::class)->make([
            'name' => null
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/provinces', $payload)
             ->assertResponseStatus(302);   
    }

    public function testPostProvinceIsHiddenAttrMissing()
    {
        $payload = factory(App\Province::class)->make([
            'isHidden' => null
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/provinces', $payload)
             ->assertResponseStatus(302);    
    }

    public function testPostProvinceIsHiddenAttrInvalid()
    {
        $payload = factory(App\Province::class)->make([
            'isHidden' => 3 // Should be either 1 or 0.
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/provinces', $payload)
             ->assertResponseStatus(302); 
    }

    public function testPostProvinceWithIdenticalNameAttr()
    {
        $p = factory(App\Province::class, 'withDates')->create();
        $payload = factory(App\Province::class)->make([
            'name' => $p->name
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/provinces', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostProvinceWithoutAuth()
    {
        $this->post('/provinces')->assertResponseStatus(403);
    }

    public function testPutProvince()
    {
        $p = factory(App\Province::class, 'withDates')->create();
        $payload = factory(App\Province::class)->make()->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/provinces/' . $p->id, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updP = json_decode($resp);                     

        $this->seeInTable('provinces', $payload, null, [
            'id' => $updP->id
        ]);
    }

    public function testPutProvinceWithoutUpdatingNameAttr()
    {
        $p = factory(App\Province::class, 'withDates')->create();
        $payload = factory(App\Province::class)->make([
            'name' => $p->name    
        ])->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/provinces/' . $p->id, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updP = json_decode($resp);                     

        $this->seeInTable('provinces', $payload, null, [
            'id' => $updP->id
        ]);
    }

    public function testPutProvinceWithIdenticalNameAttr()
    {
        $p = factory(App\Province::class, 'withDates')->create();
        $p2 = factory(App\Province::class, 'withDates')->create();
        $payload = factory(App\Province::class)->make([
            'name' => $p2->name
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->put('/provinces/' . $p->id, $payload)
             ->assertResponseStatus(302);
    }    

     public function testPutProvinceDoesNotExist()
    {
        $payload = factory(App\Province::class)->make()->toArray();

        $this->actingAs($this->getAdmin())
             ->put('/provinces/0', $payload)
             ->assertResponseStatus(404);
    }   

    public function testPutProvinceWithoutAuth()
    {
        $p = factory(App\Province::class, 'withDates')->create();
        $payload = factory(App\Province::class)->make()->toArray();

        $this->put('/provinces/' . $p->id)
             ->assertResponseStatus(403);
    }

    public function testDeleteProvince()
    {
        $p = factory(App\Province::class, 'withDates')->create();

        $this->actingAs($this->getAdmin())
             ->delete('/provinces/' . $p->id)
             ->assertResponseStatus(200);

        $this->notSeeInTable('provinces', ['id' => $p->id]);
    }

    public function testDeleteProvinceWithForeignKeyRestraint()
    {
        $fr = $this->getPublishedFr('model');
        $f = $fr->publishedFacility;

        $this->actingAs($this->getAdmin())
             ->delete('/provinces/' . $f->provinceId)
             ->assertResponseStatus(403);

        $this->seeInTable('provinces', ['id' => $f->provinceId]);             
    }

    public function testDeleteProvincesWithoutAuth()
    {
        $p = factory(App\Province::class, 'withDates')->create();

        $this->delete('/provinces/' . $p->id)
             ->assertResponseStatus(403);   

        $this->seeInTable('provinces', ['id' => $p->id]);
    }
}
