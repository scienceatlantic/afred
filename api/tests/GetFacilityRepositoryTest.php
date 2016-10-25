<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetFacilityRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetFacilityRepositories()
    {
        $this->actingAs($this->getAdmin())
             ->get('/facility-repository')
             ->assertResponseOk();
    }

    public function testGetFacilityRepositoriesWithPagination()
    {
        $this->actingAs($this->getAdmin())
             ->get('/facility-repository')
             ->seeJson([
                 'prevPageUrl' => null
             ]);
    }

    public function testGetFacilityRepositoriesWithoutPagination()
    {
        $this->actingAs($this->getAdmin())
             ->get('/facility-repository?paginate=0')->dontSeeJson([
                 'prevPageUrl' => null
             ]);        
    }

    public function testGetFacilityRepositoriesWithoutAuth()
    {
        $this->get('/facility-repository')->assertResponseStatus(403);
    }

    public function testGetFacilityRepository()
    {
        $fr = $this->getPendingApprovalFr();

        $this->actingAs($this->getAdmin())
             ->get('/facility-repository/' . $fr->id)->seeJson([
                 'id' => $fr->id
             ]);
    }
    
    public function testGetFacilityRepositoryWithoutAuth()
    {
        $fr = $this->getPendingApprovalFr();

        $this->get('/facility-repository/' . $fr->id)
             ->assertResponseStatus(403);
    }
}
