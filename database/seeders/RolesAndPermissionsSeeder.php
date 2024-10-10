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
            'can manage items',
            'can manage payments',
        ];

        // Create permissions if they do not exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superadminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $superadminRole->givePermissionTo([
            'can manage users',
            'can assign roles',
            'can approve transactions',
            'can view transactions',
            'can view items',
            'can view categories',
            'can add transactions',
            'can manage categories',
            'can manage items'
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'can manage categories',
            'can approve transactions',
            'can add transactions',
            'can view transactions',
            'can view items',
            'can view categories'
        ]);

        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'can manage items',
            'can add transactions',
            'can view transactions',
            'can view categories',
            'can view items',
        ]);

        $cashierRole = Role::firstOrCreate(['name' => 'cashier']);
        $cashierRole->givePermissionTo([
            'can manage payments',

        ]);
    }
}
