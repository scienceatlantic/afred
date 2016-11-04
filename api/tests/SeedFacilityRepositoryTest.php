<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SeedFacilityRepositoryTest extends TestCase
{
    public function testSeedFacilityRepositoryTable()
    {
        $num = env('TEST_SEED_FACILITY_REPOSITORY_TABLE', 0);

        for ($i = 0; $i < $num; $i++) {
            switch (rand(0, 7)) {
                case 0:
                    $this->getPendingApprovalFr();
                    break;
                case 1:
                    $this->getPublishedFr();
                    break;
                case 2:
                    $this->getRejectedFr();
                    break;
                case 3:
                    $this->getPendingEditApprovalFr();
                    break;
                case 4:
                    $this->getPublishedEditFr();
                    break;
                case 5:
                    $this->getRejectedEditFr();
                    break;
                case 6:
                    $this->getDeletedFr();
                    break;
                case 7:
                    $this->getDeletedFrWithEdit();
                    break;
            }
        }
    }
}
