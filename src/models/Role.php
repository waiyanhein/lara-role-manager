<?php

namespace Waiyanhein\LaraRoleManager\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public static function findRole($code)
    {
        return static::where('code', $code)->first();
    }

    public function users()
    {
        $this->belongsToMany(config('auth.providers.users.model'), 'user_role', 'role_id', 'user_id')->withTimestamps();
    }

    public static function roles()
    {
        $roles = [ ];
        $roleModels = static::all();
        foreach ($roleModels as $roleModel) {
            $roles[$roleModel->id] = $roleModel->display_title;
        }

        return $roles;
    }
}
