<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DisciplineTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetDisciplines()
    {
        $this->get('/disciplines')->assertResponseOk();
    }

    public function testGetDisciplinesWithPagination()
    {
        $this->get('/disciplines')->seeJson([
            'prevPageUrl' => null
        ]);
    }

    public function testGetDisciplinesWithoutPagination()
    {
        $this->get('/disciplines?paginate=0')->dontSeeJson([
            'prevPageUrl' => null
        ]);        
    }

    public function testGetDiscipline()
    {
        $d = factory(App\Discipline::class, 'withDates')->create();

        $this->get('/disciplines/' . $d->id)->seeJson([
            'id' => $d->id
        ]);
    }

    public function testPostDiscipline()
    {
        $payload = factory(App\Discipline::class)->make()->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->post('/disciplines', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $d = json_decode($resp);

        $this->seeInTable('disciplines', $payload, [
            'id' => $d->id
        ]);
    }

    public function testPostDisciplineNameAttrMissing()
    {
        $this->actingAs($this->getAdmin())
             ->post('/disciplines')
             ->assertResponseStatus(302);   
    }

    public function testPostDisciplineWithIdenticalName()
    {
        $d = factory(App\Discipline::class, 'withDates')->create();
        $payload = factory(App\Discipline::class)->make([
            'name' => $d->name
        ])->toArray();

        $this->actingAs($this->getAdmin())
             ->post('/disciplines', $payload)
             ->assertResponseStatus(302);
    }

    public function testPostDisciplineWithoutAuth()
    {
        $payload = factory(App\Discipline::class)->make()->toArray();

        $this->post('/disciplines', $payload)
             ->assertResponseStatus(403);
    }

    public function testPutDiscipline()
    {
        $d = factory(App\Discipline::class, 'withDates')->create();
        $payload = factory(App\Discipline::class)->make([
            'id' => $d->id
        ])->toArray();

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/disciplines/' . $d->id, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updD = json_decode($resp);

        $this->seeInTable('disciplines', $payload, [
            'id' => $d->id
        ]);
    }

     public function testPutDisciplineDoesNotExist()
    {
        $payload = factory(App\Discipline::class)->make()->toArray();

        $this->actingAs($this->getAdmin())
             ->put('/disciplines/0', $payload)
             ->assertResponseStatus(404);
    }   

    public function testPutDisciplineWithoutAuth()
    {
        $d = factory(App\Discipline::class, 'withDates')->create();
        $payload = factory(App\Discipline::class)->make([
            'id' => $d->id
        ])->toArray();

        $this->put('/disciplines/' . $d->id, $payload)
             ->assertResponseStatus(403);
    }

    public function testDeleteDiscipline()
    {
        $d = factory(App\Discipline::class, 'withDates')->create();

        $this->actingAs($this->getAdmin())
             ->delete('/disciplines/' . $d->id)
             ->assertResponseStatus(200);

        $this->notSeeInTable('disciplines', ['id' => $d->id]);
    }

    public function testDeleteDisciplineWithForeignKeyRestraint()
    {
        $fr = $this->getPublishedFr('model');
        $f = $fr->publishedFacility;

        $this->actingAs($this->getAdmin())
             ->delete('/disciplines/' . $f->disciplines[0]->id)
             ->assertResponseStatus(403);

        $this->seeInTable('disciplines', ['id' => $f->disciplines[0]->id]);
    }

    public function testDeleteDisciplinesWithoutAuth()
    {
        $d = factory(App\Discipline::class, 'withDates')->create();

        $this->delete('/disciplines/' . $d->id)
             ->assertResponseStatus(403);

        $this->seeInTable('disciplines', ['id' => $d->id]);
    }
}
