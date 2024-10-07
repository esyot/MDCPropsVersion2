<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Define permissions
        $permissions = [
            'can manage users',
            'can assign roles',
            'can approve transactions',
            'can view transactions',
            'can view items',
            'can view categories',
            'can add transactions',
            'can manage categories',
            'can manage items'
        ];

        // Create permissions if they do not exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'can assign roles',
            'can manage users',
            'can manage items',
            'can manage categories',
            'can approve transactions',
            'can add transactions',
            'can view transactions',
            'can view items',
            'can view categories'
        ]);

        $moderatorRole = Role::firstOrCreate(['name' => 'moderator']);
        $moderatorRole->givePermissionTo([
            'can manage categories',
            'can approve transactions',
            'can add transactions',
            'can view transactions',
            'can view items',
            'can view categories'
        ]);

        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $editorRole->givePermissionTo([
            'can manage items',
            'can manage categories',
            'can add transactions',
            'can view transactions',
            'can view categories',
            'can view items',
        ]);

        $viewerRole = Role::firstOrCreate(['name' => 'viewer']);
        // Optionally assign permissions for the viewer role if needed
        $viewerRole->givePermissionTo([
            // Add viewer permissions here
        ]);
    }
}
