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
        $this->get('/users')->assertResponseStatus('403');
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
        $u = factory(App\User::class)->create();
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
        $u = factory(App\User::class)->make()->toArray();
        $u['password'] = str_random(10);
        $u['roles'] = [$r->id];
        $this->actingAs($this->getAdmin())
             ->post('/users', $u)
             ->assertResponseOk();
    }

    public function testPostUserWithoutAuth()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class)->make()->toArray();
        $u['password'] = str_random(10);
        $u['roles'] = [$r->id];
        $this->post('/users', $u)
             ->assertResponseStatus('403');
    }

    public function testPostUserWithNoPasswordAttr()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class)->make()->toArray(); // `toArray()` will
                                                          // not return 
                                                          // password.
        $u['roles'] = [$r->id];
        $this->actingAs($this->getAdmin())
             ->post('/users', $u)
             ->assertResponseStatus('302');        
    }

    public function testPostUserWithNoRolesAttr()
    {
        $u = factory(App\User::class)->make()->toArray();
        $u['password'] = str_random(10);
        $this->actingAs($this->getAdmin())
             ->post('/users', $u)
             ->assertResponseStatus('403');       
    }

    public function testPostUserWithNonexistentRole()
    {
        $r = App\Role::orderBy('id', 'desc')->first();
        $u = factory(App\User::class)->make()->toArray();
        $u['password'] = str_random(10);
        $u['roles'] = [$r->id + 1]; // Id of a role that does not exist.
        $this->actingAs($this->getAdmin())
             ->post('/users', $u)
             ->assertResponseStatus('403');  
    }

    public function testPostUserWithHigherPermissionLevel()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $r2 = App\Role::where('name', 'SUPER_ADMIN')->first();
        $u = factory(App\User::class)->make()->toArray();
        $u['password'] = str_random(10);
        $u['roles'] = [$r->id, $r2->id];
        $this->actingAs($this->getAdmin())
             ->post('/users', $u)
             ->assertResponseStatus('403');         
    }

    public function testPostUserInvalidPath()
    {
        $this->post('/users/1')->assertResponseStatus('405');
    }
    
    public function testPutUser()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class)->create();
        $u->roles()->attach([$r->id]);
        $u2 = factory(App\User::class)->make()->toArray();
        $u2['roles'] = [$r->id];
        $this->actingAs($this->getAdmin())
             ->put('/users/' . $u->id, $u2)
             ->assertResponseOk();
    }

    public function testPutUserResetPassword()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class)->create();
        $u->roles()->attach([$r->id]);
        $oldHashedPwd = $u->password;
        $this->actingAs($this->getAdmin())
             ->put('/users/' . $u->id, ['password' => str_random(10)])
             ->assertResponseOk();
        $newHashedPwd = App\User::find($u->id)->password; 
        $this->assertNotEquals($oldHashedPwd, $newHashedPwd);
    }

    public function testPutUserWithoutAuth()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class)->create();
        $u->roles()->attach([$r->id]);
        $u2 = factory(App\User::class)->make()->toArray();
        $u2['roles'] = [$r->id];
        $this->put('/users/' . $u->id, $u2)
             ->assertResponseStatus(403);
    }

    public function testPutUserWithNoRolesAttr()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class)->create();
        $u->roles()->attach([$r->id]);
        $u2 = factory(App\User::class)->make()->toArray();
        $this->actingAs($this->getAdmin())
             ->put('/users/' . $u->id, $u2)
             ->assertResponseStatus('403');         
    }

    public function testPutUserWithNonexistentRole()
    {
        $r = App\Role::orderBy('id', 'desc')->first();
        $u = factory(App\User::class)->create();
        $u->roles()->attach([$r->id]);
        $u2 = factory(App\User::class)->make()->toArray();
        $u2['roles'] = [$r->id + 1]; // Id of a role that does not exist.
        $this->actingAs($this->getAdmin())
             ->put('/users/' . $u->id, $u2)
             ->assertResponseStatus('403');         
    }

    public function testPutUserWithHigherPermissionLevel()
    {
        $r = App\Role::where('name', 'SUPER_ADMIN')->first();
        $r2 = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class)->create();
        $u->roles()->attach([$r->id]);
        $u2 = factory(App\User::class)->make()->toArray();
        $u2['roles'] = [$r2->id, $r->id];
        $this->actingAs($this->getAdmin())
             ->put('/users/' . $u->id, $u2)
             ->assertResponseStatus('403');         
    }    
    
    public function testPutUserDoesNotExist()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class)->create();
        $u->roles()->attach([$r->id]);
        $u2 = factory(App\User::class)->make()->toArray();
        $u2['roles'] = [$r->id];
        $this->actingAs($this->getAdmin())
             ->put('/users/0', $u2)
             ->assertResponseStatus(404);
    }   

    public function testPutUserInvalidPath()
    {
        $this->put('/users')->assertResponseStatus('405');
    }

    public function testDeleteUser()
    {
        $r = App\Role::where('name', 'ADMIN')->first();
        $u = factory(App\User::class)->create();
        $u->roles()->attach([$r->id]);
        $this->actingAs($this->getAdmin())
             ->delete('/users/' . $u->id)
             ->assertResponseOk();
    }

    public function testDeleteUsersWithoutAuth()
    {
        $this->delete('/users/1')->assertResponseStatus('403');   
    }

    public function testDeleteUserWithHigherPermissionLevel()
    {
        $r = App\Role::where('name', 'SUPER_ADMIN')->first();
        $u = factory(App\User::class)->create();
        $u->roles()->attach([$r->id]);
        $this->actingAs($this->getAdmin())
             ->delete('/users/' . $u->id)
             ->assertResponseStatus('403');       
    }

    public function testDeleteUsersInvalidPath()
    {
        $this->delete('/users')->assertResponseStatus('405');   
    }
}
