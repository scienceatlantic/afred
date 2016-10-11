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
        $p = factory(App\Province::class)->create();
        $this->get('/provinces/' . $p->id)->seeJson([
            'id' => $p->id
        ]);
    }

    public function testPostProvince()
    {
        $this->actingAs($this->getAdmin())
             ->post('/provinces', [
                 'name' => 'test',
                 'isHidden' => 1
             ])
             ->assertResponseOk();   
    }

    public function testPostProvinceNameAttrMissing()
    {
        $this->actingAs($this->getAdmin())
             ->post('/provinces', [
                 'isHidden' => 1
             ])
             ->assertResponseStatus('302');   
    }

    public function testPostProvinceIsHiddenAttrMissing()
    {
        $this->actingAs($this->getAdmin())
             ->post('/provinces', [
                 'name' => 'test'
             ])
             ->assertResponseStatus('302');   
    }

    public function testPostProvinceIsHiddenAttrInvalid()
    {
        $this->actingAs($this->getAdmin())
             ->post('/provinces', [
                 'name' => 'test',
                 'isHidden' => 3 // Should be either 0 or 1.
             ])
             ->assertResponseStatus('302');   
    }

    public function testPostProvinceWithoutAuth()
    {
        $this->post('/provinces')->assertResponseStatus('403');
    }

    public function testPostProvinceInvalidPath()
    {
        $this->post('/provinces/1')->assertResponseStatus('405');
    }

    public function testPutProvince()
    {
        $p = factory(App\Province::class)->create();
        $this->actingAs($this->getAdmin())
             ->put('/provinces/' . $p->id, [
                 'name' => 'new_test',
                 'isHidden' => 0
             ])
             ->assertResponseOk();
    }

     public function testPutProvinceDoesNotExist()
    {
        $this->actingAs($this->getAdmin())
             ->put('/provinces/0', [
                 'name' => 'new_test',
                 'isHidden' => 0
             ])
             ->assertResponseStatus(404);
    }   

    public function testPutProvinceWithoutAuth()
    {
        $this->put('/provinces/1')->assertResponseStatus('403');
    }

    public function testPutProvinceInvalidPath()
    {
        $this->put('/provinces')->assertResponseStatus('405');
    }

    public function testDeleteProvince()
    {
        $p = factory(App\Province::class)->create();
        $this->actingAs($this->getAdmin())
             ->delete('/provinces/' . $p->id)
             ->assertResponseOk();
    }

    public function testDeleteProvinceWithForeignKeyRestraint()
    {
        $f = App\Facility::first();
        $this->actingAs($this->getAdmin())
             ->delete('/provinces/' . $f->provinceId)
             ->assertResponseStatus('400');
    }

    public function testDeleteProvincesWithoutAuth()
    {
        $this->delete('/provinces/1')->assertResponseStatus('403');   
    }

    public function testDeleteProvincesInvalidPath()
    {
        $this->delete('/provinces')->assertResponseStatus('405');   
    }
}
