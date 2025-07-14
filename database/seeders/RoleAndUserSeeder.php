<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run()
    {
        $managerRole = Role::create(['name' => 'manager']);
        $userRole = Role::create(['name' => 'user']);

        $manager = User::create([
            'name'     => 'Manager One',
            'email'    => 'manager@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole($managerRole);

        $user = User::create([
            'name'     => 'Zeyad',
            'email'    => 'zeyad@gmail.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($userRole);
    }
}

