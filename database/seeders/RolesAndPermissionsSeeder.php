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
        Permission::create(['name' => 'publish articles']);

        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['can manage users', 'publish articles']);

        $editorRole = Role::create(['name' => 'staff']);
        $editorRole->givePermissionTo('publish articles');
    }
}
