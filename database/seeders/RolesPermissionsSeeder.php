<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // ── All modules (must match AppServiceProvider keys) ──────────────
        $modules = [
            'hero',
            'destination',
            'category',
            'project',
            'blog',
            'social',
            'setting',
            'user_equirements',
            'about',
            'about_value',
            'meta_info',
            'stat',
            'message',
            'support-ticket',
        ];

        // ── Actions for every module ──────────────────────────────────────
        $actions = ['view', 'create', 'edit', 'delete'];

        // ── Create all permissions ────────────────────────────────────────
        $allPermissionIds = [];
        $messageViewOnly = []; // Message view permission only

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $key   = "{$module}.{$action}";
                $label = ucfirst($module) . ' - ' . ucfirst($action);

                $permission = Permission::firstOrCreate(
                    ['key' => $key],
                    [
                        'module' => $module,
                        'action' => $action,
                        'label'  => $label,
                    ]
                );

                if ($module === 'message' && $action === 'view') {
                    $messageViewOnly[] = $permission->id;
                }

                $allPermissionIds[] = $permission->id;
            }
        }

        // ── Create roles ──────────────────────────────────────────────────

        // Super Admin — is_super_admin flag bypasses all checks
        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            [
                'name'           => 'Super Admin',
                'description'    => 'Full access to everything.',
                'is_super_admin' => true,
            ]
        );

        // Assign all permissions to super admin explicitly as well
        $superAdmin->permissions()->sync($allPermissionIds);

        // ✅ Message View Only Role
        $messageViewer = Role::firstOrCreate(
            ['slug' => 'message-viewer'],
            [
                'name'           => 'Message Viewer',
                'description'    => 'Can only view messages.',
                'is_super_admin' => true,
            ]
        );
        $messageViewer->permissions()->sync($messageViewOnly);

        // ── Create default super admin user ───────────────────────────────
        $user = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name'     => 'Super Admin',
                'user_type'     => 'admin',
                'password' => Hash::make('12345678'),
                'status' => 1,
            ]
        );

        // Assign super admin role to user
        $user->roles()->syncWithoutDetaching([$superAdmin->id]);

        $this->command->info('Roles, permissions and super admin user created successfully.');
        $this->command->table(
            ['Role', 'Permissions Count', 'Super Admin'],
            Role::withCount('permissions')->get()->map(fn($r) => [
                $r->name,
                $r->permissions_count,
                $r->is_super_admin ? 'Yes' : 'No',
            ])->toArray()
        );
    }
}
