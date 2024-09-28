<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        Permission::create(['name' => 'can manage users']);
        Permission::create(['name' => 'can assign roles']);
        Permission::create(['name' => 'can approve transactions']);
        Permission::create(['name' => 'can view transactions']);
        Permission::create(['name' => 'can view items']);
        Permission::create(['name' => 'can add transactions']);
        Permission::create(['name' => 'can manage categories']);
        Permission::create(['name' => 'can manage items']);

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'can assign roles',
            'can manage users',
            'can manage items',
            'can manage categories',
            'can approve transactions',
            'can add transactions',
            'can view transactions',
            'can view items',
        ]);

        $editorRole = Role::create(['name' => 'moderator']);
        $editorRole->givePermissionTo([
            'can manage categories',
            'can approve transactions',
            'can add transactions',
            'can view transactions',
            'can view items',
        ]);

        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo([
            'can manage items',
            'can manage categories',
            'can add transactions',
            'can view transactions',
            'can view items',
        ]);

        $editorRole = Role::create(['name' => 'viewer']);
        $editorRole->givePermissionTo([
            'can view transactions',
            'can view items',
        ]);
    }
}
