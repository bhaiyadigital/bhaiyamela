<?php

namespace App\Traits;

use App\Models\Role;

trait HasRoles
{
    // Static cache — keyed by user ID to avoid repeated DB queries per request
    private static array $permissionCache = [];

    // ------------------------------------------------------------------
    // All roles assigned to this user
    // ------------------------------------------------------------------
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    // ------------------------------------------------------------------
    // Check if user has a specific role by slug e.g. "super-admin"
    // ------------------------------------------------------------------
    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    // ------------------------------------------------------------------
    // Check if user is super admin
    // ------------------------------------------------------------------
    public function isSuperAdmin(): bool
    {
        return $this->roles()->where('is_super_admin', true)->exists();
    }

    // ------------------------------------------------------------------
    // Check if user has a specific permission key e.g. "blogs.create"
    // ------------------------------------------------------------------
    public function hasPermission(string $key): bool
    {
        $userId = $this->id;

        // Build cache for this user if not already built
        if (!isset(static::$permissionCache[$userId])) {
            static::$permissionCache[$userId] = [];

            $this->loadMissing('roles.permissions');

            foreach ($this->roles as $role) {
                // Super admin — cache wildcard and return immediately
                if ($role->is_super_admin) {
                    static::$permissionCache[$userId]['*'] = true;
                    return true;
                }

                foreach ($role->permissions as $permission) {
                    static::$permissionCache[$userId][$permission->key] = true;
                }
            }
        }

        // Wildcard means super admin
        if (isset(static::$permissionCache[$userId]['*'])) {
            return true;
        }

        return isset(static::$permissionCache[$userId][$key]);
    }

    // ------------------------------------------------------------------
    // Clear permission cache for this user
    // Call this after roles are updated
    // ------------------------------------------------------------------
    public function clearPermissionCache(): void
    {
        unset(static::$permissionCache[$this->id]);
    }

    // ------------------------------------------------------------------
    // Assign a role by slug
    // ------------------------------------------------------------------
    public function assignRole(string $slug): void
    {
        $role = Role::where('slug', $slug)->firstOrFail();
        $this->roles()->syncWithoutDetaching([$role->id]);
        $this->clearPermissionCache();
    }

    // ------------------------------------------------------------------
    // Remove a role by slug
    // ------------------------------------------------------------------
    public function removeRole(string $slug): void
    {
        $role = Role::where('slug', $slug)->first();
        if ($role) {
            $this->roles()->detach($role->id);
            $this->clearPermissionCache();
        }
    }
}