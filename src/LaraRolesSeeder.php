<?php

namespace Waiyanhein\LaraRoleManager;

use Waiyanhein\LaraRoleManager\Models\Role;

class LaraRolesSeeder
{
    public static function seed()
    {
        $roles = config('roles');

        foreach ($roles as $code => $displayTitle) {
            factory(Role::class)->create([
               'code' => $code,
               'display_title' => $displayTitle,
            ]);
        }
    }
}
