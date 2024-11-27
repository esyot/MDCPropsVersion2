<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id' => 1,
            'name' => 'Admin',
            'img' => 'user.png',
            'email' => 'admin@gmail.com',
            'isPasswordChanged' => true,
            'password' => Hash::make('P@ssw0rd'),
        ]);
        $user1 = User::create([
            'id' => 2,
            'name' => 'User',
            'img' => 'user.png',
            'email' => 'user@gmail.com',
            'isPasswordChanged' => true,
            'password' => Hash::make('P@ssw0rd'),
        ]);

        $user2 = User::create([
            'id' => 3,
            'name' => 'Cashier',
            'img' => 'user.png',
            'email' => 'cashier@gmail.com',
            'isPasswordChanged' => true,
            'password' => Hash::make('P@ssw0rd'),
        ]);

        $user3 = User::create([
            'id' => 4,
            'name' => 'Super Admin',
            'img' => 'user.png',
            'email' => 'superadmin@gmail.com',
            'isPasswordChanged' => true,
            'password' => Hash::make('P@ssw0rd'),
        ]);


        $user->assignRole('admin');
        $user1->assignRole('staff');
        $user2->assignRole('cashier');
        $user3->assignRole('superadmin');
    }
}
