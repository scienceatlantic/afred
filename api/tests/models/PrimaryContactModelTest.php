<?php

use App\PrimaryContact;
use App\Facility;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PrimaryContactModelTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testFacilityMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $pc = PrimaryContact::find($fr->data['primaryContact']['id']);

        $this->assertEquals($f->toArray(), $pc->facility->toArray());
    }

    public function testGetFullNameMethod()
    {
        $fr = $this->getPublishedFr();
        $pc = PrimaryContact::find($fr->data['primaryContact']['id']);
        $fullName = $pc->firstName . ' ' . $pc->lastName;

        $this->assertEquals($pc->getFullName(), $fullName);
    }
}
