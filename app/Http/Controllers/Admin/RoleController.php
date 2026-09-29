<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // ------------------------------------------------------------------
    // List all roles
    // ------------------------------------------------------------------
    public function index()
    {
        $roles = Role::withCount('permissions', 'users')->orderBy('id')->get();
        return view('admin.roles.index', compact('roles'));
    }

    // ------------------------------------------------------------------
    // Show create form
    // ------------------------------------------------------------------
    public function create()
    {
    
        $permissions = Permission::orderBy('module')->orderBy('action')->get()->groupBy('module');
        return view('admin.roles.form', compact('permissions'));
    }

    // ------------------------------------------------------------------
    // Store new role
    // ------------------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:roles,name',
            'slug'        => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name'           => $request->name,
            'slug'           => $request->slug,
            'description'    => $request->description,
            'is_super_admin' => false, // only set via seeder
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    // ------------------------------------------------------------------
    // Show edit form
    // ------------------------------------------------------------------
    public function edit(int $id)
    {
        $role        = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('module')->orderBy('action')->get()->groupBy('module');

        return view('admin.roles.form', compact('role', 'permissions'));
    }

    // ------------------------------------------------------------------
    // Update role
    // ------------------------------------------------------------------
    public function update(Request $request, int $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name,' . $id,
            'slug'          => 'required|string|max:255|unique:roles,slug,' . $id,
            'description'   => 'nullable|string|max:500',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update([
            'name'        => $request->name,
            'slug'        => $request->slug,
            'description' => $request->description,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    // ------------------------------------------------------------------
    // Delete role
    // ------------------------------------------------------------------
    public function destroy(int $id)
    {
        $role = Role::findOrFail($id);

        // Prevent deleting super admin role
        if ($role->is_super_admin) {
            return back()->with('error', 'Super Admin role cannot be deleted.');
        }

        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }

    // ------------------------------------------------------------------
    // Toggle single permission on a role (AJAX)
    // ------------------------------------------------------------------
    public function togglePermission(Request $request, int $id)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $role = Role::findOrFail($id);

        // Super admin always has all permissions — no toggle needed
        if ($role->is_super_admin) {
            return response()->json(['message' => 'Super admin role cannot be modified.'], 403);
        }

        $permissionId = $request->permission_id;
        $hasIt        = $role->permissions()->where('permissions.id', $permissionId)->exists();

        if ($hasIt) {
            $role->permissions()->detach($permissionId);
        } else {
            $role->permissions()->attach($permissionId);
        }

        return response()->json([
            'active' => !$hasIt,
        ]);
    }
}