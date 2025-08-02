<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Cuci Reguler',
                'description' => 'Cuci pakaian biasa dengan durasi 1-2 hari',
                'price' => 8000.00,
                'unit' => 'kg',
                'estimated_days' => 2,
                'sort_order' => 1,
            ],
            [
                'name' => 'Cuci Express',
                'description' => 'Cuci pakaian cepat dengan durasi 3-6 jam',
                'price' => 15000.00,
                'unit' => 'kg',
                'estimated_days' => 1,
                'sort_order' => 2,
            ],
            [
                'name' => 'Cuci Setrika',
                'description' => 'Cuci dan setrika pakaian',
                'price' => 12000.00,
                'unit' => 'kg',
                'estimated_days' => 2,
                'sort_order' => 3,
            ],
            [
                'name' => 'Setrika Saja',
                'description' => 'Setrika pakaian yang sudah dicuci',
                'price' => 5000.00,
                'unit' => 'kg',
                'estimated_days' => 1,
                'sort_order' => 4,
            ],
            [
                'name' => 'Dry Clean',
                'description' => 'Cuci kering untuk pakaian khusus',
                'price' => 25000.00,
                'unit' => 'piece',
                'estimated_days' => 3,
                'sort_order' => 5,
            ],
            [
                'name' => 'Cuci Selimut',
                'description' => 'Cuci selimut dan bed cover',
                'price' => 15000.00,
                'unit' => 'piece',
                'estimated_days' => 2,
                'sort_order' => 6,
            ],
            [
                'name' => 'Cuci Karpet',
                'description' => 'Cuci karpet dan keset',
                'price' => 20000.00,
                'unit' => 'piece',
                'estimated_days' => 3,
                'sort_order' => 7,
            ],
        ];

        foreach ($services as $service) {
            DB::table('services')->insert($service);
        }
    }
}
