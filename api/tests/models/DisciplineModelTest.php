<?php

use App\Discipline;
use App\Facility;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DisciplineModelTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testDatesAreInstanceOfCarbon()
    {
        $d = factory(Discipline::class, 'withDates')->create();
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$d->dateCreated, $d->dateUpdated]
        );
    }

    public function testFacilitiesMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $d = Discipline::find(DB::table('discipline_facility')
            ->where('facilityId', $f->id)
            ->select('disciplineId')
            ->first()->disciplineId);
        $f2 = $d->facilities[0]->toArray();
        unset($f2['pivot']);

        $this->assertEquals($f->toArray(), $f2);
    }
}
