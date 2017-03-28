<?php

use App\Facility;
use App\Province;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProvinceModelTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testDatesAreInstanceOfCarbon()
    {
        $p = factory(Province::class, 'withDates')->create();
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$p->dateCreated, $p->dateUpdated]
        );
    }

    public function testFacilitiesMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $p = Province::find($f->provinceId);

        $this->assertEquals($f->toArray(), $p->facilities[0]->toArray());
    }

    public function testScopeNotHiddenMethod()
    {
        foreach(factory(Province::class, 20)->create() as $p) {
            $p->isHidden = rand(0, 1);
            $p->update();
        }
        
        foreach(Province::notHidden()->get() as $p) {
            $this->assertEquals($p->isHidden, 0);
        }
    }

    public function testScopeHiddenMethod()
    {
        foreach(factory(Province::class, 20)->create() as $p) {
            $p->isHidden = rand(0, 1);
            $p->update();
        }
        
        foreach(Province::hidden()->get() as $p) {
            $this->assertEquals($p->isHidden, 1);
        }
    }
}
