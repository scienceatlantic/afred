<?php

use App\FacilityRepository;
use App\FacilityUpdateLink;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FacilityUpdateLinkModelTest extends TestCase
{
    use DatabaseTransactions;

    public function testDatesAreInstanceOfCarbon()
    {
        $fr = $this->getPublishedEditFr('model');
        $ful = $fr->originRequest;
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$ful->dateOpened, $ful->datePending, $ful->dateClosed]
        );
    }

    public function testUpdatedFrMethod()
    {
        $fr = $this->getPublishedEditFr('model');
        $ful = $fr->originRequest()->first();

        $this->assertEquals($fr->toArray(), $ful->updatedFr->toArray());
    }

    public function testOriginalFrMethod()
    {
        $fr = $this->getPublishedEditFr('model');
        $ful = $fr->originRequest()->first();
        $oFr = FacilityRepository::where('id', '!=', $fr->id)
            ->where('facilityId', $fr->facilityId)->first();

        $this->assertEquals($oFr->toArray(), $ful->originalFr->toArray());
    }

    public function testScopeOpenMethod()
    {
        $this->getOpenFul();
        $status = FacilityUpdateLink::open()->first()->status;

        $this->assertEquals('OPEN', $status);
    }

    public function testScopePendingMethod()
    {
        $this->getPendingEditApprovalFr();
        $status = FacilityUpdateLink::pending()->first()->status;

        $this->assertEquals('PENDING', $status);
    }

    public function testScopeClosedMethod()
    {
        $this->getPublishedEditFr();
        $status = FacilityUpdateLink::closed()->first()->status;

        $this->assertEquals('CLOSED', $status);
    }

    public function testScopeNotClosedMethod()
    {
        
    }

    public function testGetFullNameMethod()
    {
        $ful = $this->getPublishedEditFr('model')->originRequest;
        $fullName = $ful->editorFirstName . ' ' . $ful->editorLastName;

        $this->assertEquals($fullName, $ful->getFullName());
    }

    public function testVerifyTokenMethod()
    {
        $ful = $this->getOpenFul('model');
        $token = $ful->token;
        $upperCaseToken = strtoupper($token);
        $lowerCaseToken = strtolower($token);
        $invalidToken = $token . str_random(5);

        $this->assertTrue(FacilityUpdateLink::verifyToken($ful->frIdBefore,
            $token));
        $this->assertTrue(FacilityUpdateLink::verifyToken($ful->frIdBefore,
            $upperCaseToken));
        $this->assertTrue(FacilityUpdateLink::verifyToken($ful->frIdBefore,
            $lowerCaseToken));
        $this->assertFalse(FacilityUpdateLink::verifyToken($ful->frIdBefore,
            $invalidToken));
    }

    public function testVerifyTokenMethodWithPendingFul()
    {
        $fr = $this->getPendingEditApprovalFr('model');
        $ful = $fr->originRequest;
        $token = $ful->token;

        $this->assertFalse(FacilityUpdateLink::verifyToken($ful->frIdBefore,
            $token));
    }
}
