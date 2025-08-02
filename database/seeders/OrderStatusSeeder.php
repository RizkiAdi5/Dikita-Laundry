<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Pending',
                'color' => '#ffc107',
                'description' => 'Pesanan baru, menunggu konfirmasi',
                'sort_order' => 1,
            ],
            [
                'name' => 'Confirmed',
                'color' => '#17a2b8',
                'description' => 'Pesanan dikonfirmasi dan siap diproses',
                'sort_order' => 2,
            ],
            [
                'name' => 'In Progress',
                'color' => '#007bff',
                'description' => 'Pesanan sedang diproses',
                'sort_order' => 3,
            ],
            [
                'name' => 'Washing',
                'color' => '#28a745',
                'description' => 'Pesanan sedang dicuci',
                'sort_order' => 4,
            ],
            [
                'name' => 'Drying',
                'color' => '#fd7e14',
                'description' => 'Pesanan sedang dikeringkan',
                'sort_order' => 5,
            ],
            [
                'name' => 'Ironing',
                'color' => '#6f42c1',
                'description' => 'Pesanan sedang disetrika',
                'sort_order' => 6,
            ],
            [
                'name' => 'Ready',
                'color' => '#20c997',
                'description' => 'Pesanan siap diambil',
                'sort_order' => 7,
            ],
            [
                'name' => 'Delivered',
                'color' => '#198754',
                'description' => 'Pesanan telah dikirim/diambil',
                'sort_order' => 8,
            ],
            [
                'name' => 'Cancelled',
                'color' => '#dc3545',
                'description' => 'Pesanan dibatalkan',
                'sort_order' => 9,
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('order_statuses')->insert($status);
        }
    }
}
