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
            'name' => 'Admin',
            'img' => 'user.png',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('P@ssw0rd'),
        ]);

        // Assigned admin to this user
        $user->assignRole('admin');
    }
}
