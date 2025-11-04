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
                'name' => 'Menunggu',
                'color' => '#fbbf24', // Kuning (match dengan chart: rgb(251, 191, 36))
                'description' => 'Pesanan baru, menunggu konfirmasi',
                'sort_order' => 1,
            ],
            [
                'name' => 'Dikonfirmasi',
                'color' => '#22c55e', // Hijau (match dengan chart: rgb(34, 197, 94))
                'description' => 'Pesanan dikonfirmasi dan siap diproses',
                'sort_order' => 2,
            ],
            [
                'name' => 'Dalam Proses',
                'color' => '#3b82f6', // Biru (match dengan chart: rgb(59, 130, 246))
                'description' => 'Pesanan sedang diproses',
                'sort_order' => 3,
            ],
            [
                'name' => 'Siap',
                'color' => '#3b82f6', // Biru (sama dengan Dalam Proses)
                'description' => 'Pesanan siap diambil',
                'sort_order' => 7,
            ],
            [
                'name' => 'Selesai',
                'color' => '#22c55e', // Hijau (sama dengan Dikonfirmasi)
                'description' => 'Pesanan telah dikirim/diambil',
                'sort_order' => 8,
            ],
            [
                'name' => 'Batal',
                'color' => '#ef4444', // Merah (match dengan chart: rgb(239, 68, 68))
                'description' => 'Pesanan dibatalkan',
                'sort_order' => 9,
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('order_statuses')->insert($status);
        }
    }
}