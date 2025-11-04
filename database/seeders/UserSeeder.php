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
                'password' => Hash::make('Rakim$%**'),
                'role' => 'super_admin',
                'employee_id' => null,
            ]
        );

        // Create sample users for different roles
        $roles = [
            [
                'name' => 'Admin User',
                'email' => 'admin.user@dikitalaundry.com',
                'password' => 'admin123',
                'role' => 'admin'
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@dikitalaundry.com',
                'password' => 'manager123',
                'role' => 'manager'
            ],
            [
                'name' => 'Cashier User',
                'email' => 'cashier@dikitalaundry.com',
                'password' => 'cashier123',
                'role' => 'cashier'
            ],
            [
                'name' => 'Operator User',
                'email' => 'operator@dikitalaundry.com',
                'password' => 'operator123',
                'role' => 'operator'
            ],
            [
                'name' => 'Staff User',
                'email' => 'staff@dikitalaundry.com',
                'password' => 'staff123',
                'role' => 'staff'
            ],
        ];

        foreach ($roles as $roleData) {
            User::firstOrCreate(
                ['email' => $roleData['email']],
                [
                    'name' => $roleData['name'],
                    'password' => Hash::make($roleData['password']), // ✅ Gunakan password dari array
                    'role' => $roleData['role'],
                    'employee_id' => null,
                ]
            );
        }
    }
}