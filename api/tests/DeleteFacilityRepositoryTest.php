<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteFacilityRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    public function testDeleteFacilityRepository()
    {
        $fr = $this->getPublishedFr();

        $this->delete('/facility-repository/' . $fr->id)
             ->assertResponseStatus(405);
    }

    public function testDeleteFacilityRepositoryWithAuth()
    {
        $fr = $this->getPublishedFr();

        $this->actingAs($this->getAdmin())
             ->delete('/facility-repository/' . $fr->id)
             ->assertResponseStatus(405);
    }
}
