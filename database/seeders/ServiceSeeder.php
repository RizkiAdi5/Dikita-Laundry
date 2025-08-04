<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

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
                'description' => 'Layanan cuci standar dengan kualitas terbaik',
                'price' => 8000,
                'unit' => 'kg',
                'estimated_days' => 2,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Cuci Express',
                'description' => 'Layanan cuci cepat untuk kebutuhan mendesak',
                'price' => 15000,
                'unit' => 'kg',
                'estimated_days' => 0.25, // 6 jam
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Cuci Setrika',
                'description' => 'Layanan cuci lengkap dengan setrika',
                'price' => 12000,
                'unit' => 'kg',
                'estimated_days' => 1.5,
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Setrika Saja',
                'description' => 'Layanan setrika untuk pakaian yang sudah bersih',
                'price' => 5000,
                'unit' => 'kg',
                'estimated_days' => 0.5, // 12 jam
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Dry Clean',
                'description' => 'Layanan cuci kering untuk pakaian khusus',
                'price' => 25000,
                'unit' => 'piece',
                'estimated_days' => 3,
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Cuci Selimut',
                'description' => 'Layanan cuci khusus untuk selimut dan bed cover',
                'price' => 35000,
                'unit' => 'piece',
                'estimated_days' => 2,
                'is_active' => true,
                'sort_order' => 6
            ],
            [
                'name' => 'Cuci Karpet',
                'description' => 'Layanan cuci karpet dengan mesin khusus',
                'price' => 50000,
                'unit' => 'm2',
                'estimated_days' => 3,
                'is_active' => true,
                'sort_order' => 7
            ],
            [
                'name' => 'Cuci Dingin',
                'description' => 'Layanan cuci dengan suhu rendah untuk pakaian sensitif',
                'price' => 10000,
                'unit' => 'kg',
                'estimated_days' => 2,
                'is_active' => true,
                'sort_order' => 8
            ],
            [
                'name' => 'Cuci Panas',
                'description' => 'Layanan cuci dengan suhu tinggi untuk sanitasi maksimal',
                'price' => 12000,
                'unit' => 'kg',
                'estimated_days' => 2,
                'is_active' => true,
                'sort_order' => 9
            ],
            [
                'name' => 'Cuci Jas',
                'description' => 'Layanan cuci khusus untuk jas dan pakaian formal',
                'price' => 30000,
                'unit' => 'piece',
                'estimated_days' => 2,
                'is_active' => true,
                'sort_order' => 10
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
