<?php

use App\Facility;
use App\Ilo;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrganizationModelTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testDatesAreInstanceOfCarbon()
    {
        $o = factory(Organization::class, 'withDates')->create();
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$o->dateCreated, $o->dateUpdated]
        );
    }

    public function testIloMethod()
    {
        $o = factory(Organization::class, 'withDates')->create();
        $ilo = factory(Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);
        
        $this->assertEquals($ilo->toArray(), $o->ilo->toArray());
    }

    public function testFacilitiesMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $o = Organization::find($f->organizationId);

        $this->assertEquals($f->toArray(), $o->facilities[0]->toArray());
    }

    public function testScopeNotHiddenMethod()
    {
        foreach(factory(Organization::class, 20)->create() as $o) {
            $o->isHidden = rand(0, 1);
            $o->update();
        }
        
        foreach(Organization::notHidden()->get() as $o) {
            $this->assertEquals($o->isHidden, 0);
        }
    }

    public function testScopeHiddenMethod()
    {
        foreach(factory(Organization::class, 20)->create() as $o) {
            $o->isHidden = rand(0, 1);
            $o->update();
        }
        
        foreach(Organization::hidden()->get() as $o) {
            $this->assertEquals($o->isHidden, 1);
        }
    }
}
