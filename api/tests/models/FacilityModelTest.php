<?php

use App\Contact;
use App\Discipline;
use App\Equipment;
use App\Facility;
use App\FacilityRepository;
use App\Http\Controllers\Controller;
use App\Organization;
use App\PrimaryContact;
use App\Province;
use App\Sector;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FacilityModelTest extends TestCase
{
    use DatabaseTransactions;

    public function testDatesAreInstanceOfCarbon()
    {
        $f = $this->getPublishedFr('model')->publishedFacility;
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$f->datePublished, $f->dateUpdated]
        );
    }

    public function testSearchableAsMethod()
    {
        $f = $this->getPublishedFr('model')->publishedFacility;
        $prefix = env('SCOUT_PREFIX') . 'facilities';

        $this->assertEquals($prefix, $f->searchableAs());        
    }

    public function testToSearchableArrayMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::with(['contacts',
                             'equipment' => function($query) {
                                 $query->notHidden();
                             },
                            'disciplines',
                            'organization',
                            'organization.ilo',
                            'primaryContact',
                            'province',
                            'sectors'])
            ->find($fr->facilityId);
        $f->isPublic = 1;
        $f->save();

        $this->assertEquals(Controller::toCcArray($f->toArray()), 
            Controller::toCcArray($f->toSearchableArray()));
    }

    public function testToSearchableArrayMethodWithHiddenFacility()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::with(['contacts',
                             'equipment' => function($query) {
                                 $query->notHidden();
                             },
                            'disciplines',
                            'organization',
                            'organization.ilo',
                            'primaryContact',
                            'province',
                            'sectors'])
            ->find($fr->facilityId);
        $f->isPublic = 0;
        $f->save();

        $this->assertEquals([], Controller::toCcArray($f->toSearchableArray()));
    }

    public function testToSearchableArrayMethodWithSomeHiddenEquipment()
    {
        $data = self::createFrDataAttr(5, 5, 20);
        $fr = $this->getPublishedFr('stdClass', $data);
        $eIds = collect($fr->data['equipment'])->pluck('id')->toArray();
        foreach(Equipment::whereIn('id', $eIds)->get() as $e) {
            $e->isPublic = rand(0, 1);
            $e->update();
        }
        $f = Facility::with(['contacts',
                             'equipment' => function($query) {
                                 $query->notHidden();
                             },
                            'disciplines',
                            'organization',
                            'organization.ilo',
                            'primaryContact',
                            'province',
                            'sectors'])
            ->find($fr->facilityId);
        $f->isPublic = 1;
        $f->save();

        $this->assertEquals(Controller::toCcArray($f->toArray()), 
            Controller::toCcArray($f->toSearchableArray()));
    }

    public function testCurrentRevisionMethod()
    {
        $fr = $this->getPublishedFr('model');
        $f = Facility::find($fr->facilityId);

        $this->assertEquals($fr->toArray(), $f->currentRevision->toArray());
    }

    public function testRevisionsMethod()
    {
        $fr = $this->getPublishedEditFr();
        $f = Facility::find($fr->facilityId);
        $fr = FacilityRepository::where('facilityId', $f->id)->get();

        $this->assertEquals($fr->toArray(), $f->revisions->toArray());
    }

    public function testOrganizationMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $o = Organization::find($f->organizationId);

        $this->assertEquals($o->toArray(), $f->organization->toArray());
    }

    public function testProvinceMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $p = Province::find($f->provinceId);

        $this->assertEquals($p->toArray(), $f->province->toArray());
    }

    public function testDisciplinesMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $dIds = collect(DB::table('discipline_facility')
            ->where('facilityId', $fr->facilityId)
            ->select('disciplineId')
            ->get()
            ->toArray())
            ->pluck('disciplineId');
        $d = Discipline::whereIn('id', $dIds)->get();
        $fDisciplines = $f->disciplines->toArray();
        foreach($fDisciplines as &$fd) {
            unset($fd['pivot']);
        }

        $this->assertEquals($d->toArray(), $fDisciplines);
    }

    public function testSectorsMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $sIds = collect(DB::table('facility_sector')
            ->where('facilityId', $fr->facilityId)
            ->select('sectorId')
            ->get()
            ->toArray())
            ->pluck('sectorId');
        $s = Sector::whereIn('id', $sIds)->get();
        $fSectors = $f->sectors->toArray();
        foreach($fSectors as &$fs) {
            unset($fs['pivot']);
        }

        $this->assertEquals($s->toArray(), $fSectors);
    }

    public function testPrimaryContactMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $pc = PrimaryContact::where('facilityId', $f->id)->first();

        $this->assertEquals($pc->toArray(), $f->primaryContact->toArray());
    }

    public function testContactsMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $c = Contact::where('facilityId', $f->id)->get();

        $this->assertEquals($c->toArray(), $f->contacts->toArray());
    }

    public function testEquipmentMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $e = Equipment::where('facilityId', $f->id)->get();

        $this->assertEquals($e->toArray(), $f->equipment->toArray());
    }

    public function testScopeHiddenAndScopeNotHiddenMethods()
    {
        $ids = [];
        for ($i = 0; $i < 4; $i++) {
            $f = Facility::find($this->getPublishedFr()->facilityId);
            $f->isPublic = ($i % 2 === 0);
            $f->update();
            array_push($ids, $f->id);
        }
        $hiddenFByInt = Facility::whereIn('id', $ids)->where('isPublic', 0)
            ->get()->toArray();
        $publicFByInt = Facility::whereIn('id', $ids)->where('isPublic', 1)
            ->get()->toArray();
        $hiddenFByBool = Facility::whereIn('id', $ids)->where('isPublic', false)
            ->get()->toArray();
        $publicFByBool = Facility::whereIn('id', $ids)->where('isPublic', true)
            ->get()->toArray();
        $hiddenFByMthd = Facility::whereIn('id', $ids)->hidden()->get()
            ->toArray();
        $publicFByMthd = Facility::whereIn('id', $ids)->notHidden()->get()
            ->toArray();

        $this->assertEquals($hiddenFByInt, $hiddenFByMthd);
        $this->assertEquals($hiddenFByBool, $hiddenFByMthd);
        $this->assertEquals($publicFByInt, $publicFByMthd);
        $this->assertEquals($publicFByBool, $publicFByMthd);
    }
}
