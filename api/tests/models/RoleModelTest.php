<?php

use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RoleModelTest extends TestCase
{
    use DatabaseTransactions;

    public function testDatesAreInstanceOfCarbon()
    {
        $r = factory(Role::class, 'withDates')->create();
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$r->dateCreated, $r->dateUpdated]
        );
    }

    public function testUsersMethod()
    {
        $r = factory(Role::class, 'withDates')->create();
        $uIds = [];
        for ($i = 0; $i < 10; $i++) {
            $u = factory(User::class, 'withPasswordAndDates')->create();
            DB::table('role_user')->insert([
                'roleId' => $r->id,
                'userId' => $u->id
            ]);
            array_push($uIds, $u->id);
        }
        $u = User::whereIn('id', $uIds)->get();
        $rUsers = $r->users->toArray();
        foreach($rUsers as &$ru) {
            unset($ru['pivot']);
        }

        $this->assertEquals($u->toArray(), $rUsers);
    }

    public function testScopeLookupMethod()
    {
        $permission = rand(1, 10);
        $r = factory(Role::class, 'withDates')->create([
            'permission' => $permission
        ]);

        $this->assertEquals(Role::lookup($r->name), $permission);
    }

    public function testScopeLookupMethodWithNonExistentRole()
    {
        $name = str_random(15);

        try {
            Role::lookup($name);
        } catch (HttpException $e) {

        }

        $this->assertEquals($e->getStatusCode(), 500);
    }

    public function testScopeMaxPermissionMethod()
    {
        $min = 90;
        $max = 100;
        Role::where('permission', '>=', $min)->update(['permission' => $min]);
        $ids = [];
        for ($i = $min; $i <= $max; $i++) {
            $r = factory(Role::class, 'withDates')->create([
                'permission' => $i
            ]);
            array_push($ids, $r->id);
        }
        $invalidIds = range(Role::max('id') + 1, Role::max('id') + 51);

        $this->assertEquals(Role::maxPermission(), $max);
        $this->assertEquals(Role::maxPermission($ids), $max);
        $this->assertEquals(Role::maxPermission(
            array_merge($ids, $invalidIds)), $max);
        $this->assertEquals(-1, Role::maxPermission($invalidIds));
    }
}
