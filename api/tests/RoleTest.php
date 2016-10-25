<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RoleTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetRoles()
    {
        $this->actingAs($this->getAdmin())
             ->get('/roles')
             ->assertResponseOk();
    }

    public function testGetRolesWithPagination()
    {
        $this->actingAs($this->getAdmin())
             ->get('/roles')->seeJson([
                 'prevPageUrl' => null
             ]);
    }

    public function testGetRolesWithoutPagination()
    {
        $this->actingAs($this->getAdmin())
             ->get('/roles?paginate=0')->dontSeeJson([
                 'prevPageUrl' => null
             ]);        
    }

    public function testGetRolesWithoutAuth()
    {
        $this->get('/roles')->assertResponseStatus('403');        
    }
}
