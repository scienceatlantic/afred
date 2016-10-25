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
        $d = factory(App\Discipline::class)->create();
        $this->get('/disciplines/' . $d->id)->seeJson([
            'id' => $d->id
        ]);
    }

    public function testPostDiscipline()
    {
        $this->actingAs($this->getAdmin())
             ->post('/disciplines', [
                 'name' => 'test'
             ])
             ->assertResponseOk();   
    }

    public function testPostDisciplineNameAttrMissing()
    {
        $this->actingAs($this->getAdmin())
             ->post('/disciplines')
             ->assertResponseStatus('302');   
    }

    public function testPostDisciplineWithoutAuth()
    {
        $this->post('/disciplines')->assertResponseStatus('403');
    }

    public function testPostDisciplineInvalidPath()
    {
        $this->post('/disciplines/1')->assertResponseStatus('405');
    }

    public function testPutDiscipline()
    {
        $d = factory(App\Discipline::class)->create();
        $this->actingAs($this->getAdmin())
             ->put('/disciplines/' . $d->id, [
                 'name' => 'new_test'
             ])
             ->assertResponseOk();
    }

     public function testPutDisciplineDoesNotExist()
    {
        $this->actingAs($this->getAdmin())
             ->put('/disciplines/0', [
                 'name' => 'new_test'
             ])
             ->assertResponseStatus(404);
    }   

    public function testPutDisciplineWithoutAuth()
    {
        $this->put('/disciplines/1')->assertResponseStatus('403');
    }

    public function testPutDisciplineInvalidPath()
    {
        $this->put('/disciplines')->assertResponseStatus('405');
    }

    public function testDeleteDiscipline()
    {
        $d = factory(App\Discipline::class)->create();
        $this->actingAs($this->getAdmin())
             ->delete('/disciplines/' . $d->id)
             ->assertResponseOk();
    }

    public function testDeleteDisciplineWithForeignKeyRestraint()
    {
        $f = App\Facility::with('disciplines')->first();
        $this->actingAs($this->getAdmin())
             ->delete('/disciplines/' . $f->disciplines[0]->id)
             ->assertResponseStatus('400');
    }

    public function testDeleteDisciplinesWithoutAuth()
    {
        $this->delete('/disciplines/1')->assertResponseStatus('403');   
    }

    public function testDeleteDisciplinesInvalidPath()
    {
        $this->delete('/disciplines')->assertResponseStatus('405');   
    }
}
