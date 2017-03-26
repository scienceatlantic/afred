<?php

use App\Facility;
use App\Ilo;
use App\Organization;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IloModelTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testDatesAreInstanceOfCarbon()
    {
        $o = factory(Organization::class, 'withDates')->create();
        $ilo = factory(Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$ilo->dateCreated, $ilo->dateUpdated]
        );
    }

    public function testOrganizationMethod()
    {
        $o = factory(Organization::class, 'withDates')->create();
        $ilo = factory(Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);

        $this->assertEquals($o->toArray(), $ilo->organization->toArray());
    }

    public function testGetFullNameMethod()
    {
        $o = factory(Organization::class, 'withDates')->create();
        $ilo = factory(Ilo::class, 'withDates')->create([
            'organizationId' => $o->id
        ]);
        $fullName = $ilo->firstName . ' ' . $ilo->lastName;
        
        $this->assertEquals($ilo->getFullName(), $fullName);
    }
}
