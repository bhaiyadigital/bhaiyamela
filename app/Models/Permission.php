<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['module', 'action', 'key', 'label'];

    // Roles that have this permission
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
}