<?php

use App\Equipment;
use App\Facility;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EquipmentModelTest extends TestCase
{
    use DatabaseTransactions;

    function testSearchableAsMethod()
    {
        $e = $this->getPublishedFr('model')->publishedFacility->equipment[0];
        $prefix = env('SCOUT_PREFIX') . 'equipment';

        $this->assertEquals($prefix, $e->searchableAs());
    }

    function testToSearchableArrayMethod()
    {
        $fr = $this->getPublishedFr();
        $e = Equipment::with('facility.contacts', 
                             'facility.equipment',
                             'facility.disciplines',
                             'facility.organization',
                             'facility.organization.ilo',
                             'facility.primaryContact',
                             'facility.province',
                             'facility.sectors')
            ->find($fr->data['equipment'][0]['id']);
        $f = $e->facility()->first();
        $f->isPublic = 1;
        $f->save();
        $e->isPublic = 1;
        $e->save();

        $this->assertEquals(Controller::toCcArray($e->toArray()), 
            Controller::toCcArray($e->toSearchableArray()));
    }

    function testToSearchableArrayMethodWithHiddenFacility()
    {
        $fr = $this->getPublishedFr();
        $e = Equipment::with('facility.contacts', 
                             'facility.equipment',
                             'facility.disciplines',
                             'facility.organization',
                             'facility.organization.ilo',
                             'facility.primaryContact',
                             'facility.province',
                             'facility.sectors')
            ->find($fr->data['equipment'][0]['id']);
        $f = $e->facility()->first();
        $f->isPublic = 0;
        $f->save();
        $e->isPublic = 1;
        $e->save();

        $this->assertEquals($e->toSearchableArray(), []);
    }

    function testToSearchableArrayMethodWithHiddenEquipment()
    {
        $fr = $this->getPublishedFr();
        $e = Equipment::with('facility.contacts', 
                             'facility.equipment',
                             'facility.disciplines',
                             'facility.organization',
                             'facility.organization.ilo',
                             'facility.primaryContact',
                             'facility.province',
                             'facility.sectors')
            ->find($fr->data['equipment'][0]['id']);
        $f = $e->facility()->first();
        $f->isPublic = 1;
        $f->save();
        $e->isPublic = 0;
        $e->save();

        $this->assertEquals($e->toSearchableArray(), []);
    }

    function testFacilityMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $e = Equipment::find($fr->data['equipment'][0]['id']);

        $this->assertEquals($f->toArray(), $e->facility->toArray());
    }

    function testScopeHiddenMethod()
    {
        $data = self::createFrDataAttr(5, 5, 20);
        $fr = $this->getPublishedFr('stdClass', $data);
        $ids = collect($fr->data['equipment'])->pluck('id')->toArray();
        foreach(Equipment::whereIn('id', $ids)->get() as $e) {
            $e->isPublic = rand(0, 1);
            $e->update();
        }
        $numHidden = Equipment::whereIn('id', $ids)->where('isPublic', 0)
            ->count();
        
        $this->assertEquals($numHidden, 
            Equipment::whereIn('id', $ids)->hidden()->count());
    }

    function testScopeNotHiddenMethod()
    {
        $data = self::createFrDataAttr(5, 5, 20);
        $fr = $this->getPublishedFr('stdClass', $data);
        $ids = collect($fr->data['equipment'])->pluck('id')->toArray();
        foreach(Equipment::whereIn('id', $ids)->get() as $e) {
            $e->isPublic = rand(0, 1);
            $e->update();
        }
        $numNotHidden = Equipment::whereIn('id', $ids)->where('isPublic', 1)
            ->count();
        
        $this->assertEquals($numNotHidden,
            Equipment::whereIn('id', $ids)->notHidden()->count());
    }

    public function testScopeExcessCapacityMethod()
    {
        $data = self::createFrDataAttr(5, 5, 20);
        $fr = $this->getPublishedFr('stdClass', $data);
        $ids = collect($fr->data['equipment'])->pluck('id')->toArray();
        foreach(Equipment::whereIn('id', $ids)->get() as $e) {
            $e->hasExcessCapacity = rand(0, 1);
            $e->update();
        }
        $numWithExcessCapacity = Equipment::whereIn('id', $ids)
            ->where('hasExcessCapacity', 1)->count();
        $numWithoutExcessCapacity = Equipment::whereIn('id', $ids)
            ->where('hasExcessCapacity', 0)->count();

        $this->assertEquals($numWithExcessCapacity,
            Equipment::whereIn('id', $ids)->excessCapacity(true)->count());
        $this->assertEquals($numWithExcessCapacity,
            Equipment::whereIn('id', $ids)->excessCapacity(1)->count());
        $this->assertEquals($numWithoutExcessCapacity,
            Equipment::whereIn('id', $ids)->excessCapacity(false)->count());
        $this->assertEquals($numWithoutExcessCapacity,
            Equipment::whereIn('id', $ids)->excessCapacity(0)->count());
    }
}
