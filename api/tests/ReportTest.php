<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReportTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetReport()
    {
        $resp = $this->actingAs($this->getAdmin())
                     ->get('/reports')
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $resp = json_decode($resp);

        $file = config('excel.export.store.path') . '/' . $resp->filename;
        $this->assertFileExists($file);
    }

    public function testGetReportWithoutAuth()
    {
        $this->get('/reports')
             ->assertResponseStatus(403);
    }
}
