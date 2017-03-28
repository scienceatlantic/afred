<?php

use App\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserModelTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testDatesAreInstanceOfCarbon()
    {
        $u = factory(User::class, 'withPasswordAndDates')->create();
        
        $this->assertContainsOnlyInstancesOf(
            Carbon::class,
            [$u->dateCreated, $u->dateUpdated]
        );
    }

    public function testRolesMethod()
    {
        $u = factory(User::class, 'withPasswordAndDates')->create();
        $r1 = factory(Role::class, 'withDates')->create();
        $r2 = factory(Role::class, 'withDates')->create();
        DB::table('role_user')->insert([
            ['roleId' => $r1->id,'userId' => $u->id],
            ['roleId' => $r2->id,'userId' => $u->id]
        ]);
        $uRoles = $u->roles->toArray();
        foreach($uRoles as &$ur) {
            unset($ur['pivot']);
        }

        $this->assertEquals([$r1->toArray(), $r2->toArray()], $uRoles);
    }

    public function testFrsMethod()
    {
        $fr = $this->getPublishedFr('model');
        $u = User::find($fr->reviewerId);

        $this->assertEquals([$fr->toArray()], $u->frs->toArray());
    }

    public function testSettingsMethod()
    {
        // TODO:
    }

    public function testSettingMethod()
    {
        // TODO:
    }

    public function testLookupMethod()
    {
        // TODO:
    }

    public function testGetFullNameMethod()
    {
        $u = factory(User::class, 'withPasswordAndDates')->create();
        $fullName = $u->firstName . ' ' . $u->lastName;

        $this->assertEquals($fullName, $u->getFullName());
    }

    public function testScopeActiveAndNotActiveMethods()
    {
        $users = factory(User::class, 'withPasswordAndDates', 20)->create();
        foreach($users as $u) {
            $u->isActive = rand(0, 1);
            $u->update();
        }
        $activeU = User::where('isActive', 1)->get()->toArray();
        $activeUByMthd = User::active()->get()->toArray();
        $inactiveU = User::where('isActive', 0)->get()->toArray();
        $inactiveUByMthd = User::notActive()->get()->toArray();

        $this->assertEquals($activeU, $activeUByMthd);
        $this->assertEquals($inactiveU, $inactiveUByMthd);
    }

    public function testScopeSuperAdminsMethod()
    {
        $saRoleId = Role::where('name', 'SUPER_ADMIN')->first()->id;
        $adminRoleId = Role::where('name', 'ADMIN')->first()->id;
        DB::table('role_user')->where('roleId', $saRoleId)
            ->update(['roleId' => $adminRoleId]);
        $users = factory(User::class, 'withPasswordAndDates', 20)->create();
        foreach($users as $u) {
            $u->roles()->attach([$saRoleId]);
        }
        $saUserIds = DB::table('role_user')->where('roleId', $saRoleId)->get()
            ->pluck('userId');
        $superAdminUsers = User::whereIn('id', $saUserIds)->get()->toArray();
        $superAdminUsersByMthd = User::superAdmins()->get()->toArray();

        $this->assertEquals($superAdminUsers, $superAdminUsersByMthd);
    }

    public function testScopeAdminsMethod()
    {
        $saRoleId = Role::where('name', 'SUPER_ADMIN')->first()->id;
        $adminRoleId = Role::where('name', 'ADMIN')->first()->id;
        DB::table('role_user')->where('roleId', $saRoleId)
            ->update(['roleId' => $adminRoleId]);
        $users = factory(User::class, 'withPasswordAndDates', 20)->create();
        foreach($users as $u) {
            $u->roles()->attach([$adminRoleId]);
        }
        $adminUsers = User::all()->toArray();
        $adminUsersByMthd = User::admins()->get()->toArray();

        $this->assertEquals($adminUsers, $adminUsersByMthd);        
    }

    public function testScopeAdminsMethodWithStrictFlagSet()
    {
        $saRoleId = Role::where('name', 'SUPER_ADMIN')->first()->id;
        $adminRoleId = Role::where('name', 'ADMIN')->first()->id;
        DB::table('role_user')->where('roleId', $saRoleId)
            ->update(['roleId' => $adminRoleId]);
        $users = factory(User::class, 'withPasswordAndDates', 20)->create();
        foreach($users as $u) {
            $u->roles()->attach([rand(0, 1) ? $adminRoleId : $saRoleId]);
        }
        $saUserIds = DB::table('role_user')->where('roleId', $saRoleId)->get()
            ->pluck('userId');
        $adminUsers = User::whereNotIn('id', $saUserIds)->get()->toArray();
        $adminUsersByMthdWithBool = User::admins(true)->get()->toArray();
        $adminUsersByMthdWithInt = User::admins(1)->get()->toArray();

        $this->assertEquals($adminUsers, $adminUsersByMthdWithBool);
        $this->assertEquals($adminUsers, $adminUsersByMthdWithInt);
    }

    public function testIsSuperAdminAndIsAdminMethods()
    {
        $saAndAU = factory(User::class, 'withPasswordAndDates')->create();
        $saU = factory(User::class, 'withPasswordAndDates')->create();
        $aU = factory(User::class, 'withPasswordAndDates')->create();
        $u = factory(User::class, 'withPasswordAndDates')->create();
        $saRoleId = Role::where('name', 'SUPER_ADMIN')->first()->id;
        $aRoleId = Role::where('name', 'ADMIN')->first()->id;
        $saAndAU->roles()->attach([$saRoleId, $aRoleId]);
        $saU->roles()->attach($saRoleId);
        $aU->roles()->attach($aRoleId);

        $this->assertTrue($saAndAU->isSuperAdmin());
        $this->assertTrue($saAndAU->isSuperAdmin(true));
        $this->assertTrue($saU->isSuperAdmin());
        $this->assertTrue($saU->isSuperAdmin(true));
        $this->assertFalse($aU->isSuperAdmin());
        $this->assertFalse($aU->isSuperAdmin(true));
        $this->assertFalse($u->isSuperAdmin());
        $this->assertFalse($u->isSuperAdmin(true));

        $this->assertTrue($saAndAU->isAdmin());
        $this->assertTrue($saAndAU->isAdmin(true));
        $this->assertTrue($saU->isAdmin());
        $this->assertFalse($saU->isAdmin(true));
        $this->assertTrue($aU->isAdmin(true));
        $this->assertTrue($aU->isAdmin(true));
        $this->assertFalse($u->isAdmin());
        $this->assertFalse($u->isAdmin(true));
    }

    public function testGetMaxPermissionMethod()
    {
        $saAndAU = factory(User::class, 'withPasswordAndDates')->create();
        $saU = factory(User::class, 'withPasswordAndDates')->create();
        $aU = factory(User::class, 'withPasswordAndDates')->create();
        $u = factory(User::class, 'withPasswordAndDates')->create();
        $saR = Role::where('name', 'SUPER_ADMIN')->first();
        $aR = Role::where('name', 'ADMIN')->first();
        $saAndAU->roles()->attach([$saR->id, $aR->id]);
        $saU->roles()->attach($saR->id);
        $aU->roles()->attach($aR->id);

        $this->assertEquals($saR->permission, $saAndAU->getMaxPermission());
        $this->assertEquals($saR->permission, $saU->getMaxPermission());
        $this->assertEquals($aR->permission, $aU->getMaxPermission());
        $this->assertEquals(-1, $u->getMaxPermission());
    }
}
