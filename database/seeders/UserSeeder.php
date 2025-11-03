<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::firstOrCreate(
            ['email' => 'admin@dikitalaundry.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'employee_id' => null,
            ]
        );

        // Create sample users for different roles
        $roles = [
            ['name' => 'Admin', 'email' => 'admin@laundry.com', 'role' => 'admin'],
            ['name' => 'Manager', 'email' => 'manager@laundry.com', 'role' => 'manager'],
            ['name' => 'Cashier', 'email' => 'cashier@laundry.com', 'role' => 'cashier'],
            ['name' => 'Operator', 'email' => 'operator@laundry.com', 'role' => 'operator'],
        ];

        foreach ($roles as $roleData) {
            User::firstOrCreate(
                ['email' => $roleData['email']],
                [
                    'name' => $roleData['name'],
                    'password' => Hash::make('password123'),
                    'role' => $roleData['role'],
                    'employee_id' => null,
                ]
            );
        }
    }
}

