<?php

namespace Waiyanhein\LaraRoleManager\Traits;

use Waiyanhein\LaraRoleManager\Models\Role;

trait RoleManager
{
    public function roles()
    {
        return $this->belongsToMany(\Waiyanhein\LaraRoleManager\Models\Role::class, 'user_role', 'user_id', 'role_id')->withTimestamps();
    }

    public function attachRole($code)
    {
        $role = Role::where('code', $code)->first();

        if (! $role) {
            throw new \LogicException("Invalid code passed to attachRole function. Code: $code");
        }

        $this->roles()->attach([ $role->id ]);
    }

    public function attachRoles($codes)
    {
        $validCodes = [ ];
        foreach ($codes as $code) {
            if (! $this->hasRole($code)) {
                $validCodes[] = $code;
            }
        }
        $roleIds = Role::whereIn('code', $validCodes)->get()->pluck('id')->all();

        if ($roleIds) {
            $this->roles()->attach($roleIds);
        }
    }

    public function hasRole($code)
    {
        $role = $this->roles()->where('code', $code)->first();

        return ($role)? true: false;
    }

    public function hasRoles($codes)
    {
        return $this->roles()->whereIn('code', $codes)->count() == count($codes);
    }
}
