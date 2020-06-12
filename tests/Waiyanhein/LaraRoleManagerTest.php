<?php

namespace Tests\Unit\Waiyanhein;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Waiyanhein\LaraRoleManager\Models\Role;

//@todo: run the tests, copy the entire tests/Waiyanhein folder into the tests/Unit folder of your Laravel project and run the tests together with the other your Laravel project's tests
//@todo: also make sure that your user model class has RoleManager trait.
class LaraRoleManagerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_attach_role_to_a_user()
    {
        $role = factory(Role::class)->create();
        $user = factory(config('auth.providers.users.model'))->create();

        $user->attachRole($role->code);
        $user->refresh();

        $this->assertEquals(1, $user->roles()->count());
        $this->assertEquals($role->id, $user->roles()->first()->id);
    }

    /** @test */
    public function attaching_a_role_to_user_throws_logic_exception_when_code_is_invalid()
    {
        $this->expectException(\LogicException::class);
        $user = factory(config('auth.providers.users.model'))->create();

        $user->attachRole(111);
    }

    /** @test */
    public function it_can_attach_roles_to_a_user()
    {
        $roleAdmin = factory(Role::class)->create([
            'code' => 11
        ]);
        $roleStaff = factory(Role::class)->create([
            'code' => 12
        ]);

        $user = factory(config('auth.providers.users.model'))->create();
        $user->attachRoles([ $roleAdmin->code, $roleStaff->code ]);
        $user->refresh();
        $roles = $user->roles()->get();

        $this->assertEquals(2, $user->roles()->count());
        $this->assertEquals($roleAdmin->id, $roles[0]->id);
        $this->assertEquals($roleStaff->id, $roles[1]->id);
    }

    /** @test */
    public function attaching_roles_to_user_ignore_role_if_it_is_already_attached()
    {
        $roleAdmin = factory(Role::class)->create([
            'code' => 11
        ]);
        $roleStaff = factory(Role::class)->create([
            'code' => 12
        ]);

        $user = factory(config('auth.providers.users.model'))->create();
        $user->attachRoles([ $roleAdmin->code, $roleStaff->code, $roleStaff->code ]);
        $user->refresh();

        $this->assertEquals(2, $user->roles()->count());
    }

    /** @test */
    public function it_can_find_role()
    {
        $role = factory(Role::class)->create([ 'code' => 123 ]);

        $this->assertEquals($role->id, Role::findRole(123)->id);
    }

    /** @test */
    public function has_role_returns_true_when_user_has_role()
    {
        $role = factory(Role::class)->create();
        $user = factory(config('auth.providers.users.model'))->create();

        $user->attachRole($role->code);
        $user->refresh();

        $this->assertTrue($user->hasRole($role->code));
    }

    /** @test */
    public function has_role_returns_false_when_user_does_not_have_role()
    {
        $user = factory(config('auth.providers.users.model'))->create();

        $this->assertFalse($user->hasRole(123));
    }

    /** @test */
    public function has_roles_returns_true_when_user_has_roles()
    {
        $roleAdmin = factory(Role::class)->create([
            'code' => 11
        ]);
        $roleStaff = factory(Role::class)->create([
            'code' => 12
        ]);

        $user = factory(config('auth.providers.users.model'))->create();
        $user->attachRoles([ $roleAdmin->code, $roleStaff->code ]);
        $user->refresh();

        $this->assertTrue($user->hasRoles([ 11, 12 ]));
    }

    /** @test */
    public function has_roles_returns_false_when_user_does_not_have_one_of_the_roles()
    {
        $roleAdmin = factory(Role::class)->create([
            'code' => 11
        ]);

        $user = factory(config('auth.providers.users.model'))->create();
        $user->attachRoles([ $roleAdmin->code ]);
        $user->refresh();

        $this->assertFalse($user->hasRoles([ 11, 12 ]));
    }

    /** @test */
    public function get_roles_returns_roles_in_right_data_format()
    {
        $roleAdmin = factory(Role::class)->create([
            'code' => 11
        ]);
        $roleStaff = factory(Role::class)->create([
            'code' => 12
        ]);

        $roles = Role::roles();
        $this->assertEquals($roleAdmin->display_title, $roles[$roleAdmin->id]);
        $this->assertEquals($roleStaff->display_title, $roles[$roleStaff->id]);
    }
}
