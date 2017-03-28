<?php

use App\Facility;
use App\FacilityRepository;
use App\FacilityUpdateLink;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FacilityRepositoryModelTest extends TestCase
{
    use DatabaseTransactions;

    public function testDatesAreInstanceOfCarbon()
    {
        $fr = $this->getPublishedFr('model');
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$fr->dateSubmitted, $fr->dateReviewed]
        );
    }

    public function testAppendedAttributes()
    {
        // Pending Approval record.
        $fr = $this->getPendingApprovalFr('model');

        $this->assertEquals($fr->data['facility']['name'], $fr->facilityName);
        $this->assertEquals(-1, $fr->isBeingUpdated);
        $this->assertEquals(0, $fr->isDeleted);
        $this->assertEquals(-1, $fr->isPublishedRevision);
        $this->assertEquals(-1, $fr->isPreviousRevision);
        $this->assertEquals(-1, $fr->isPublic);
        $this->assertEquals(-1, $fr->publishedId);
        $this->assertEquals(null, $fr->unclosedUpdateRequest);


        // Published record.
        $fr = $this->getPublishedFr('model');
        $f = $fr->publishedFacility;

        $this->assertEquals($f->name, $fr->facilityName);
        $this->assertEquals(0, $fr->isBeingUpdated);
        $this->assertEquals(0, $fr->isDeleted);
        $this->assertEquals(1, $fr->isPublishedRevision);
        $this->assertEquals(0, $fr->isPreviousRevision);
        $this->assertEquals($f->isPublic, $fr->isPublic);
        $this->assertEquals($f->facilityRepositoryId, $fr->publishedId);
        $this->assertEquals(null, $fr->unclosedUpdateRequest);


        // Rejected record.
        $fr = $this->getRejectedFr('model');

        $this->assertEquals($fr->data['facility']['name'], $fr->facilityName);
        $this->assertEquals(-1, $fr->isBeingUpdated);
        $this->assertEquals(0, $fr->isDeleted);
        $this->assertEquals(-1, $fr->isPublishedRevision);
        $this->assertEquals(-1, $fr->isPreviousRevision);
        $this->assertEquals(-1, $fr->isPublic);
        $this->assertEquals(-1, $fr->publishedId);
        $this->assertEquals(null, $fr->unclosedUpdateRequest);

        
        // Pending edit approval record.
        $pdnFr = $this->getPendingEditApprovalFr('model');
        $pubFr = $pdnFr->originRequest->originalFr;
        $f = $pdnFr->facility;
        $ful = FacilityUpdateLink::where('frIdBefore', $pubFr->id)->first();

        $this->assertNotEquals($f->name, $pdnFr->facilityName);
        $this->assertEquals(-1, $pdnFr->isBeingUpdated);
        $this->assertEquals(0, $pdnFr->isDeleted);
        $this->assertEquals(0, $pdnFr->isPublishedRevision);
        $this->assertEquals(0, $pdnFr->isPreviousRevision);
        $this->assertEquals(-1, $pdnFr->isPublic);
        $this->assertEquals($f->facilityRepositoryId, $pdnFr->publishedId);
        $this->assertEquals(null, $pdnFr->unclosedUpdateRequest);

        $this->assertEquals($f->name, $pubFr->facilityName);
        $this->assertEquals(1, $pubFr->isBeingUpdated);
        $this->assertEquals(0, $pubFr->isDeleted);
        $this->assertEquals(1, $pubFr->isPublishedRevision);
        $this->assertEquals(0, $pubFr->isPreviousRevision);
        $this->assertEquals($f->isPublic, $pubFr->isPublic);
        $this->assertEquals($pubFr->id, $pubFr->publishedId);
        $this->assertEquals($ful->toArray(), 
            $pubFr->unclosedUpdateRequest->toArray());


        // Published edit record.
        $fr = $this->getPublishedEditFr('model');
        $preFr = $fr->originRequest->originalFr;
        $f = $fr->publishedFacility;

        $this->assertEquals($f->name, $fr->facilityName);
        $this->assertEquals(0, $fr->isBeingUpdated);
        $this->assertEquals(0, $fr->isDeleted);
        $this->assertEquals(1, $fr->isPublishedRevision);
        $this->assertEquals(0, $fr->isPreviousRevision);
        $this->assertEquals($f->isPublic, $fr->isPublic);
        $this->assertEquals($f->facilityRepositoryId, $fr->publishedId);
        $this->assertEquals(null, $fr->unclosedUpdateRequest);

        $this->assertNotEquals($f->name, $preFr->facilityName);
        $this->assertEquals(-1, $preFr->isBeingUpdated);
        $this->assertEquals(0, $preFr->isDeleted);
        $this->assertEquals(0, $preFr->isPublishedRevision);
        $this->assertEquals(1, $preFr->isPreviousRevision);
        $this->assertEquals(-1, $preFr->isPublic);
        $this->assertEquals($f->facilityRepositoryId, $preFr->publishedId);
        $this->assertEquals(null, $preFr->unclosedUpdateRequest);


        // Rejected edit record.
        $rejFr = $this->getRejectedEditFr('model');
        $pubFr = $rejFr->originRequest->originalFr;
        $f = $rejFr->facility;

        $this->assertNotEquals($f->name, $rejFr->facilityName);
        $this->assertEquals(-1, $rejFr->isBeingUpdated);
        $this->assertEquals(0, $rejFr->isDeleted);
        $this->assertEquals(0, $rejFr->isPublishedRevision);
        $this->assertEquals(0, $rejFr->isPreviousRevision);
        $this->assertEquals(-1, $rejFr->isPublic);
        $this->assertEquals($pubFr->id, $rejFr->publishedId);
        $this->assertEquals(null, $rejFr->unclosedUpdateRequest);

        $this->assertEquals($f->name, $pubFr->facilityName);
        $this->assertEquals(0, $pubFr->isBeingUpdated);
        $this->assertEquals(0, $pubFr->isDeleted);
        $this->assertEquals(1, $pubFr->isPublishedRevision);
        $this->assertEquals(0, $pubFr->isPreviousRevision);
        $this->assertEquals($f->isPublic, $pubFr->isPublic);
        $this->assertEquals($f->facilityRepositoryId, $pubFr->publishedId);
        $this->assertEquals(null, $pubFr->unclosedUpdateRequest);


        // Deleted record.
        $fr = $this->getDeletedFr('model');

        $this->assertEquals($fr->data['facility']['name'], $fr->facilityName);
        $this->assertEquals(-1, $fr->isBeingUpdated);
        $this->assertEquals(1, $fr->isDeleted);
        $this->assertEquals(-1, $fr->isPublishedRevision);
        $this->assertEquals(-1, $fr->isPreviousRevision);
        $this->assertEquals(-1, $fr->isPublic);
        $this->assertEquals(-1, $fr->publishedId);
        $this->assertEquals(null, $fr->unclosedUpdateRequest);
    }

    public function testPublishedFacilityMethod()
    {
        // Pending Approval record.
        $fr = $this->getPendingApprovalFr('model');

        $this->assertEquals(null, $fr->publishedFacility);


        // Published record.
        $fr = $this->getPublishedFr('model');

        $this->assertEquals(Facility::find($fr->facilityId)->toArray(), 
            $fr->publishedFacility->toArray());


        // Rejected record.
        $fr = $this->getRejectedFr('model');

        $this->assertEquals(null, $fr->publishedFacility);
        

        // Pending edit approval record.
        $fr = $this->getPendingEditApprovalFr('model');

        $this->assertEquals(null, $fr->publishedFacility);


        // Published edit record.
        $fr = $this->getPublishedEditFr('model');

        $this->assertEquals(Facility::find($fr->facilityId)->toArray(), 
            $fr->publishedFacility->toArray());


        // Rejected edit record.
        $fr = $this->getRejectedEditFr('model');

        $this->assertEquals(null, $fr->publishedFacility);


        // Deleted record.
        $fr = $this->getDeletedFr('model');

        $this->assertEquals(null, $fr->publishedFacility);
    }

    public function testFacilityMethod()
    {
        // Pending Approval record.
        $fr = $this->getPendingApprovalFr('model');

        $this->assertEquals(null, $fr->facility);


        // Published record.
        $fr = $this->getPublishedFr('model');

        $this->assertEquals(Facility::find($fr->facilityId)->toArray(), 
            $fr->facility->toArray());


        // Rejected record.
        $fr = $this->getRejectedFr('model');

        $this->assertEquals(null, $fr->facility);
        

        // Pending edit approval record.
        $fr = $this->getPendingEditApprovalFr('model');

        $this->assertEquals(Facility::find($fr->facilityId)->toArray(), 
            $fr->facility->toArray());


        // Published edit record.
        $fr = $this->getPublishedEditFr('model');

        $this->assertEquals(Facility::find($fr->facilityId)->toArray(), 
            $fr->facility->toArray());


        // Rejected edit record.
        $fr = $this->getRejectedEditFr('model');

        $this->assertEquals(Facility::find($fr->facilityId)->toArray(), 
            $fr->facility->toArray());


        // Deleted record.
        $fr = $this->getDeletedFr('model');

        $this->assertEquals(null, $fr->facility);
    }

    public function testReviewerMethod()
    {
        // Pending Approval record.
        $fr = $this->getPendingApprovalFr('model');

        $this->assertEquals(null, $fr->reviewer);

        // Published record.
        $fr = $this->getPublishedFr('model');

        $this->assertEquals(User::find($fr->reviewerId)->toArray(), 
            $fr->reviewer->toArray());


        // Rejected record.
        $fr = $this->getRejectedFr('model');

        $this->assertEquals(User::find($fr->reviewerId)->toArray(), 
            $fr->reviewer->toArray());
        

        // Pending edit approval record.
        $fr = $this->getPendingEditApprovalFr('model');

        $this->assertEquals(null, $fr->reviewer);


        // Published edit record.
        $fr = $this->getPublishedEditFr('model');

        $this->assertEquals(User::find($fr->reviewerId)->toArray(), 
            $fr->reviewer->toArray());


        // Rejected edit record.
        $fr = $this->getRejectedEditFr('model');

        $this->assertEquals(User::find($fr->reviewerId)->toArray(), 
            $fr->reviewer->toArray());


        // Deleted record.            
        $fr = $this->getDeletedFr('model');

        $this->assertEquals(User::find($fr->reviewerId)->toArray(), 
            $fr->reviewer->toArray());        
    }

    public function testUpdateRequestsMethod()
    {
        // Pending Approval record.
        $fr = $this->getPendingApprovalFr('model');

        $this->assertEquals(0, $fr->updateRequests()->count());


        // Published record.
        $fr = $this->getPublishedFr('model');

        $this->assertEquals(0, $fr->updateRequests()->count());


        // Rejected record.
        $fr = $this->getRejectedFr('model');

        $this->assertEquals(0, $fr->updateRequests()->count());
        

        // Pending edit approval record.
        $fr = $this->getPendingEditApprovalFr('model');
        $pubFr = $fr->facility->currentRevision;

        $this->assertEquals(0, $fr->updateRequests()->count());
        $this->assertEquals(1, $pubFr->updateRequests()->count());


        // Published edit record.
        $fr = $this->getPublishedEditFr('model');
        $preFr = $fr->originRequest->originalFr;

        $this->assertEquals(0, $fr->updateRequests()->count());
        $this->assertEquals(1, $preFr->updateRequests()->count());


        // Rejected edit record.
        $fr = $this->getRejectedEditFr('model');
        $pubFr = $fr->facility->currentRevision;

        $this->assertEquals(0, $fr->updateRequests()->count());
        $this->assertEquals(1, $pubFr->updateRequests()->count());


        // Deleted record.
        $fr = $this->getDeletedFr('model');

        $this->assertEquals(0, $fr->updateRequests()->count());        
    }

    public function testOriginFrMethod()
    {
        // Pending Approval record.
        $fr = $this->getPendingApprovalFr('model');

        $this->assertEquals(null, $fr->originRequest);


        // Published record.
        $fr = $this->getPublishedFr('model');

        $this->assertEquals(null, $fr->originRequest);


        // Rejected record.
        $fr = $this->getRejectedFr('model');

        $this->assertEquals(null, $fr->originRequest);
        

        // Pending edit approval record.
        $fr = $this->getPendingEditApprovalFr('model');
        $ful = FacilityUpdateLink::where('frIdAfter', $fr->id)->first();

        $this->assertEquals($ful->toArray(), $fr->originRequest->toArray());


        // Published edit record.
        $fr = $this->getPublishedEditFr('model');
        $ful = FacilityUpdateLink::where('frIdAfter', $fr->id)->first();

        $this->assertEquals($ful->toArray(), $fr->originRequest->toArray());


        // Rejected edit record.
        $fr = $this->getRejectedEditFr('model');
        $ful = FacilityUpdateLink::where('frIdAfter', $fr->id)->first();

        $this->assertEquals($ful->toArray(), $fr->originRequest->toArray());


        // Deleted record.
        $fr = $this->getDeletedFr('model');

        $this->assertEquals(null, $fr->originRequest);        
    }

    public function testScopePendingApprovalMethod()
    {
        $this->getPendingApprovalFr();
        $this->getPendingEditApprovalFr();

        foreach(FacilityRepository::pendingApproval()->get() as $fr) {
            $this->assertTrue($fr->state === 'PENDING_APPROVAL');
        }

        // With boolean param.
        $editsIncluded = false;
        foreach(FacilityRepository::pendingApproval(true)->get() as $fr) {
            $this->assertTrue($fr->state === 'PENDING_APPROVAL' 
                || $fr->state === 'PENDING_EDIT_APPROVAL');
            
            if ($fr->state === 'PENDING_EDIT_APPROVAL') {
                $editsIncluded = true;
            }
        }
        $this->assertTrue($editsIncluded);

        // With int param.
        $editsIncluded = false;
        foreach(FacilityRepository::pendingApproval(true)->get() as $fr) {
            $this->assertTrue($fr->state === 'PENDING_APPROVAL' 
                || $fr->state === 'PENDING_EDIT_APPROVAL');
            
            if ($fr->state === 'PENDING_EDIT_APPROVAL') {
                $editsIncluded = true;
            }
        }
        $this->assertTrue($editsIncluded);
    }

    public function testScopePendingEditApprovalMethod()
    {
        $this->getPendingApprovalFr();
        $this->getPendingEditApprovalFr();

        foreach(FacilityRepository::pendingEditApproval()->get() as $fr) {
            $this->assertTrue($fr->state === 'PENDING_EDIT_APPROVAL');
        }
    }

    public function testScopePublishedMethod()
    {
        $this->getPublishedFr();
        $f = $this->getPublishedEditFr('model')->publishedFacility;
        $f->isPublic = 0;
        $f->update();

        $editsIncluded = false;
        $publicIncluded = false;
        $privateIncluded = false;
        foreach(FacilityRepository::published()->get() as $fr) {
            $this->assertTrue($fr->state === 'PUBLISHED' 
                || $fr->state === 'PUBLISHED_EDIT');
            
            if ($fr->state === 'PUBLISHED_EDIT') {
                $editsIncluded = true;
            }
            if ($fr->publishedFacility->isPublic) {
                $publicIncluded = true;
            }
            if (!$fr->publishedFacility->isPublic) {
                $privateIncluded = true;
            }            
        }
        $this->assertTrue($editsIncluded);
        $this->assertTrue($publicIncluded);
        $this->assertTrue($privateIncluded);

        // With boolean param.
        $publicOnly = true;
        foreach(FacilityRepository::published(true)->get() as $fr) {
            if (!$fr->publishedFacility->isPublic) {
                $publicOnly = false;
            }
        }
        $this->assertTrue($publicOnly);

        // With int param.
        $publicOnly = true;
        foreach(FacilityRepository::published(1)->get() as $fr) {
            if (!$fr->publishedFacility->isPublic) {
                $publicOnly = false;
            }
        }
        $this->assertTrue($publicOnly);

        // With boolean param.
        $privateOnly = true;
        foreach(FacilityRepository::published(false)->get() as $fr) {
            if ($fr->publishedFacility->isPublic) {
                $privateOnly = false;
            }
        }
        $this->assertTrue($privateOnly);

        // With int param.
        $privateOnly = true;
        foreach(FacilityRepository::published(0)->get() as $fr) {
            if ($fr->publishedFacility->isPublic) {
                $privateOnly = false;
            }
        }
        $this->assertTrue($privateOnly);
    }

    public function testScopeRejectedMethod()
    {
        $this->getRejectedFr();
        $this->getRejectedEditFr();

        foreach(FacilityRepository::rejected()->get() as $fr) {
            $this->assertTrue($fr->state === 'REJECTED');
        }

        // With boolean param.
        $editsIncluded = false;
        foreach(FacilityRepository::rejected(true)->get() as $fr) {
            $this->assertTrue($fr->state === 'REJECTED' 
                || $fr->state === 'REJECTED_EDIT');
            
            if ($fr->state === 'REJECTED_EDIT') {
                $editsIncluded = true;
            }
        }
        $this->assertTrue($editsIncluded);

        // With int param.
        $editsIncluded = false;
        foreach(FacilityRepository::rejected(1)->get() as $fr) {
            $this->assertTrue($fr->state === 'REJECTED' 
                || $fr->state === 'REJECTED_EDIT');
            
            if ($fr->state === 'REJECTED_EDIT') {
                $editsIncluded = true;
            }
        }
        $this->assertTrue($editsIncluded);        
    }

    public function testScopeRejectedEditMethod()
    {
        $this->getRejectedFr();
        $this->getRejectedEditFr();

        foreach(FacilityRepository::rejectedEdit()->get() as $fr) {
            $this->assertTrue($fr->state === 'REJECTED_EDIT');
        }
    }

    public function testScopeRemovedMethod()
    {
        Facility::where('id', '>', 0)->delete();
        FacilityRepository::where('id', '>', 0)->delete();

        $this->getPendingApprovalFr();
        $this->getPublishedFr();
        $this->getRejectedFr();
        $this->getPendingEditApprovalFr();
        $this->getPublishedEditFr();
        $this->getRejectedEditFr();

        $this->getDeletedFr();
        $this->getDeletedFr();
        $this->getDeletedFrWithEdit();
        $this->getDeletedFrWithEdit();

        $this->assertEquals(4, FacilityRepository::removed()->count());
    }
}
