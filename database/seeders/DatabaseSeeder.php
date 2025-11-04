<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            OrderStatusSeeder::class,
            ServiceSeeder::class,
            CustomerSeeder::class,
            EmployeeSeeder::class,
            ExpenseSeeder::class,
            InventorySeeder::class,
            InventoryTransactionSeeder::class,
            OrderSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}
