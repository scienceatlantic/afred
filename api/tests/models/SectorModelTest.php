<?php

use App\Facility;
use App\Sector;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SectorModelTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testDatesAreInstanceOfCarbon()
    {
        $s = factory(Sector::class, 'withDates')->create();
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$s->dateCreated, $s->dateUpdated]
        );
    }

    public function testFacilitiesMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $s = Sector::find(DB::table('facility_sector')
            ->where('facilityId', $f->id)
            ->select('sectorId')
            ->first()->sectorId);
        $f2 = $s->facilities[0]->toArray();
        unset($f2['pivot']);

        $this->assertEquals($f->toArray(), $f2);
    }
}
