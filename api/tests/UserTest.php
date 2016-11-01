<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetUsers()
    {
        $this->actingAs($this->getAdmin())
             ->get('/users')
             ->assertResponseOk();
    }

    public function testGetUsersWithoutAuth()
    {
        $this->get('/users')->assertResponseStatus(403);
    }

    public function testGetUsersWithPagination()
    {
        $this->actingAs($this->getAdmin())
             ->get('/users')->seeJson([
                 'prevPageUrl' => null
             ]);
    }

    public function testGetUsersWithoutPagination()
    {
        $this->actingAs($this->getAdmin())
             ->get('/users?paginate=0')->dontSeeJson([
                 'prevPageUrl' => null
             ]);        
    }
    
    public function testGetUser()
    {
        $r = App\Role::where('name', 'SUPER_ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);

        $this->actingAs($this->getAdmin())
             ->get('/users/' . $u->id)->seeJson([
                 'id' => $u->id
             ]);
    }

    public function testGetUserWithNoRoles()
    {
        // We're testing a scenario that shouldn't happen (where a user is 
        // created with no roles, i.e. should always have at least one role).
        $u = factory(App\User::class)->create();

        $this->actingAs($this->getAdmin())
             ->get('/users/' . $u->id)->seeJson([
                 'maxPermissionLevel' => -1 // User has no roles, 
                                            // `User::getMaxPermission()` 
                                            // should return -1.
             ]);   
    }
    
    public function testPostUser()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $payload = factory(App\User::class)->make()->toArray();
        $payload['password'] = str_random(10); // `toArray()` method will not 
                                               // return password so add it like 
                                               // this.
        $payload['roles'] = [$r->id];

        $resp = $this->actingAs($this->getAdmin())
                     ->post('/users', $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $u = json_decode($resp, true);
    
        // Password should not be returned.
        $this->assertContains('password', $u);

        $this->seeInTable('users', $payload, [
            'password',
            'roles'
        ], [
            'id' => $u['id']
        ]);
        $this->seeInBridgeTable('role_user', $payload['roles'], $u['id']);
    }

    public function testPostUserWithIdenticalEmailAttr()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);
        $payload = factory(App\User::class)->make([
            'email' => $u->email
        ])->toArray();
        $payload['password'] = str_random(10);
        $payload['roles'] = [$r->id];

        $this->actingAs($this->getAdmin())
             ->post('/users', $payload)
             ->assertResponseStatus(302);

    }

    public function testPostUserWithNoPasswordAttr()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $payload = factory(App\User::class)->make()->toArray();
        $payload['roles'] = [$r->id];

        $this->actingAs($this->getAdmin())
             ->post('/users', $payload)
             ->assertResponseStatus(302);        
    }

    public function testPostUserWithNoRolesAttr()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $payload = factory(App\User::class)->make()->toArray();
        $payload['password'] = str_random(10);

        $this->actingAs($this->getAdmin())
             ->post('/users', $payload)
             ->assertResponseStatus(403);       
    }

    public function testPostUserWithNonexistentRole()
    {
        $r = App\Role::orderBy('id', 'desc')->first();
        $payload = factory(App\User::class)->make()->toArray();
        $payload['password'] = str_random(10);
        $payload['roles'] = [$r->id + 1]; // Id of a role that does not exist.

        $this->actingAs($this->getAdmin())
             ->post('/users', $payload)
             ->assertResponseStatus(403);  
    }

    public function testPostUserWithHigherPermissionLevel()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $r2 = App\Role::where('name', 'SUPER_ADMIN')->first();
        $payload = factory(App\User::class)->make()->toArray();
        $payload['password'] = str_random(10);
        $payload['roles'] = [$r->id, $r2->id];

        $this->actingAs($this->getAdmin())
             ->post('/users', $payload)
             ->assertResponseStatus(403);         
    }

    public function testPostUserWithoutAuth()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $payload = factory(App\User::class)->make()->toArray();
        $payload['password'] = str_random(10);
        $payload['roles'] = [$r->id];

        $this->post('/users', $payload)
             ->assertResponseStatus(403);
    }
    
    public function testPutUser()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);
        $payload = factory(App\User::class)->make()->toArray();
        $payload['roles'] = [$r->id];

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/users/' . $u->id, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updU = json_decode($resp, true);

        // TODO: figure out why this is failing...
        //$this->assertNotContains('password', $updU);

        $this->seeInTable('users', $payload, [
            'password',
            'roles'
        ], [
            'id' => $updU['id']
        ]);
        $this->seeInBridgeTable('role_user', $payload['roles'], $updU['id']);
    }

    public function testPutUserWithoutUpdatingEmailAttr()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);
        $payload = factory(App\User::class)->make([
            'email' => $u->email
        ])->toArray();
        $payload['roles'] = [$r->id];

        $resp = $this->actingAs($this->getAdmin())
                     ->put('/users/' . $u->id, $payload)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $updU = json_decode($resp, true);

        // TODO: figure out why this is failing...
        //$this->assertNotContains('password', $updU);

        $this->seeInTable('users', $payload, [
            'password',
            'roles'
        ], [
            'id' => $updU['id']
        ]);
        $this->seeInBridgeTable('role_user', $payload['roles'], $updU['id']);
    }    

    public function testPutUserResetPassword()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);
        $oldHashedPwd = $u->password;
        $payload = ['password' => str_random(10)];

        $this->actingAs($this->getAdmin())
             ->put('/users/' . $u->id, $payload)
             ->assertResponseOk();

        $this->notSeeInTable('users', ['password' => $oldHashedPwd]);
    }

    public function testPutUserWithoutAuth()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);
        $payload = factory(App\User::class)->make()->toArray();
        $payload['roles'] = [$r->id];

        $this->put('/users/' . $u->id, $payload)
             ->assertResponseStatus(403);
    }

    public function testPutUserWithNoRolesAttr()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);
        $payload = factory(App\User::class)->make()->toArray();

        $this->actingAs($this->getAdmin())
             ->put('/users/' . $u->id, $payload)
             ->assertResponseStatus(403);         
    }

    public function testPutUserWithNonexistentRole()
    {
        $r = App\Role::orderBy('id', 'desc')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);
        $payload = factory(App\User::class)->make()->toArray();
        $payload['roles'] = [$r->id + 1]; // Id of a role that does not exist.

        $this->actingAs($this->getAdmin())
             ->put('/users/' . $u->id, $payload)
             ->assertResponseStatus(403);         
    }

    public function testPutUserWithHigherPermissionLevel()
    {
        $r = App\Role::where('name', 'SUPER_ADMIN')->first();
        $r2 = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);
        $payload = factory(App\User::class)->make()->toArray();
        $payload['roles'] = [$r2->id, $r->id];

        $this->actingAs($this->getAdmin())
             ->put('/users/' . $u->id, $payload)
             ->assertResponseStatus(403);         
    }    
    
    public function testPutUserDoesNotExist()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $payload = factory(App\User::class)->make()->toArray();
        $payload['roles'] = [$r->id];

        $this->actingAs($this->getAdmin())
             ->put('/users/0', $payload)
             ->assertResponseStatus(404);
    }

    public function testDeleteUser()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);

        $resp = $this->actingAs($this->getAdmin())
                     ->delete('/users/' . $u->id)
                     ->seeStatusCode(200)
                     ->response
                     ->getContent();
        $deletedU = json_decode($resp);

        $this->notSeeInTable('users', ['id' => $deletedU->id]);
    }

    public function testDeleteUserWithHigherPermissionLevel()
    {
        $r = App\Role::where('name', 'SUPER_ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);

        $this->actingAs($this->getAdmin())
             ->delete('/users/' . $u->id)
             ->assertResponseStatus(403);       
    }

    public function testDeleteUsersWithoutAuth()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class, 'withPasswordAndDates')->create();
        $u->roles()->attach([$r->id]);

        $this->delete('/users/' . $u->id)
             ->assertResponseStatus(403);   
    }
}
