<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'is_super_admin'];

    protected $casts = ['is_super_admin' => 'boolean'];

    // Permissions assigned to this role
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    // Users assigned to this role
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    // Check if role has a specific permission key e.g. "blogs.create"
    public function hasPermission(string $key): bool
    {
        if ($this->is_super_admin) return true;

        return $this->permissions()->where('key', $key)->exists();
    }
}