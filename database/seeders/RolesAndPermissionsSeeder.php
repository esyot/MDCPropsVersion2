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
            'can approve reservations',
            'can view dashboard',
            'can view reservations',
            'can view properties',
            'can view categories',
            'can add reservations',
            'can manage categories',
            'can manage properties',
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
            'can approve reservations',
            'can view dashboard',
            'can view reservations',
            'can view properties',
            'can view categories',
            'can add reservations',
            'can manage categories',
            'can manage properties'
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'can manage categories',
            'can approve reservations',
            'can add reservations',
            'can view dashboard',
            'can view reservations',
            'can view properties',
            'can view categories'
        ]);

        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->givePermissionTo([
            'can manage properties',
            'can view dashboard',
            'can add reservations',
            'can view reservations',
            'can view categories',
            'can view properties',
        ]);

        $cashierRole = Role::firstOrCreate(['name' => 'cashier']);
        $cashierRole->givePermissionTo([
            'can manage payments',

        ]);
    }
}
