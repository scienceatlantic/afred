<?php

use App\Contact;
use App\Facility;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ContactModelTest extends TestCase
{
    use DatabaseTransactions;

    public function testFacilityMethod()
    {
        $fr = $this->getPublishedFr();
        $f = Facility::find($fr->facilityId);
        $c = Contact::find($fr->data['contacts'][0]['id']);

        $this->assertEquals($f->toArray(), $c->facility->toArray());
    }

    public function testGetFullNameMethod()
    {
        $fr = $this->getPublishedFr();
        $c = Contact::find($fr->data['contacts'][0]['id']);
        $fullName = $c->firstName . ' ' . $c->lastName;
        
        $this->assertEquals($c->getFullName(), $fullName);
    }
}
