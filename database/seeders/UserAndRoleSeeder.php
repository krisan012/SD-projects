<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAndRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Administrator',
        ]);

        $userRole = Role::firstOrCreate(['name' => 'user'], [
            'display_name' => 'Regular User',
        ]);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Normal User',
                'password' => Hash::make('password'),
                'role_id' => $userRole->id,
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'normal1@example.com'],
            [
                'name' => 'first User',
                'password' => Hash::make('password'),
                'role_id' => $userRole->id,
            ]
        );

        $this->command->info('✅ Roles and Users seeded successfully.');
    }
}
