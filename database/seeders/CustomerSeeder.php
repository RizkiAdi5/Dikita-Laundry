<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Ahmad Rizki',
                'phone' => '081234567890',
                'email' => 'ahmad.rizki@email.com',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'gender' => 'male',
                'birth_date' => '1995-03-15',
                'membership_type' => 'gold',
                'points' => 1250,
                'is_active' => true,
                'notes' => 'Pelanggan setia, sering order cuci express',
                'created_at' => Carbon::now()->subMonths(6),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'name' => 'Siti Nurhaliza',
                'phone' => '081234567891',
                'email' => 'siti.nurhaliza@email.com',
                'address' => 'Jl. Thamrin No. 45, Jakarta Pusat',
                'gender' => 'female',
                'birth_date' => '1992-08-22',
                'membership_type' => 'platinum',
                'points' => 2450,
                'is_active' => true,
                'notes' => 'Member VIP, selalu order dry clean',
                'created_at' => Carbon::now()->subMonths(8),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567892',
                'email' => 'budi.santoso@email.com',
                'address' => 'Jl. Gatot Subroto No. 67, Jakarta Selatan',
                'gender' => 'male',
                'birth_date' => '1989-12-10',
                'membership_type' => 'regular',
                'points' => 350,
                'is_active' => true,
                'notes' => 'Pelanggan baru, masih eksplorasi layanan',
                'created_at' => Carbon::now()->subMonths(2),
                'updated_at' => Carbon::now()->subDays(5)
            ],
            [
                'name' => 'Dewi Wati',
                'phone' => '081234567893',
                'email' => 'dewi.wati@email.com',
                'address' => 'Jl. Kebayoran Lama No. 89, Jakarta Selatan',
                'gender' => 'female',
                'birth_date' => '1994-05-18',
                'membership_type' => 'silver',
                'points' => 780,
                'is_active' => false,
                'notes' => 'Sementara tidak aktif, pindah kota',
                'created_at' => Carbon::now()->subMonths(4),
                'updated_at' => Carbon::now()->subDays(10)
            ],
            [
                'name' => 'Rudi Hermawan',
                'phone' => '081234567894',
                'email' => 'rudi.hermawan@email.com',
                'address' => 'Jl. Senayan No. 12, Jakarta Pusat',
                'gender' => 'male',
                'birth_date' => '1991-07-25',
                'membership_type' => 'gold',
                'points' => 1890,
                'is_active' => true,
                'notes' => 'Suka order cuci setrika untuk pakaian kerja',
                'created_at' => Carbon::now()->subMonths(10),
                'updated_at' => Carbon::now()->subDays(3)
            ],
            [
                'name' => 'Maya Sari',
                'phone' => '081234567895',
                'email' => 'maya.sari@email.com',
                'address' => 'Jl. Kuningan No. 34, Jakarta Selatan',
                'gender' => 'female',
                'birth_date' => '1993-11-08',
                'membership_type' => 'platinum',
                'points' => 3200,
                'is_active' => true,
                'notes' => 'Member VIP, sering order untuk keluarga besar',
                'created_at' => Carbon::now()->subMonths(12),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'name' => 'Agus Setiawan',
                'phone' => '081234567896',
                'email' => 'agus.setiawan@email.com',
                'address' => 'Jl. Mangga Dua No. 56, Jakarta Utara',
                'gender' => 'male',
                'birth_date' => '1988-04-30',
                'membership_type' => 'regular',
                'points' => 120,
                'is_active' => true,
                'notes' => 'Pelanggan baru, order pertama kemarin',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7)
            ],
            [
                'name' => 'Nina Kartika',
                'phone' => '081234567897',
                'email' => 'nina.kartika@email.com',
                'address' => 'Jl. Kelapa Gading No. 78, Jakarta Utara',
                'gender' => 'female',
                'birth_date' => '1996-09-14',
                'membership_type' => 'silver',
                'points' => 650,
                'is_active' => true,
                'notes' => 'Suka order cuci reguler untuk pakaian sehari-hari',
                'created_at' => Carbon::now()->subMonths(3),
                'updated_at' => Carbon::now()->subDays(4)
            ],
            [
                'name' => 'Doni Prasetyo',
                'phone' => '081234567898',
                'email' => 'doni.prasetyo@email.com',
                'address' => 'Jl. Ciputat Raya No. 90, Jakarta Selatan',
                'gender' => 'male',
                'birth_date' => '1990-01-20',
                'membership_type' => 'gold',
                'points' => 1560,
                'is_active' => true,
                'notes' => 'Pelanggan setia, sering order untuk kantor',
                'created_at' => Carbon::now()->subMonths(7),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'name' => 'Rina Marlina',
                'phone' => '081234567899',
                'email' => 'rina.marlina@email.com',
                'address' => 'Jl. Pondok Indah No. 23, Jakarta Selatan',
                'gender' => 'female',
                'birth_date' => '1997-06-12',
                'membership_type' => 'regular',
                'points' => 280,
                'is_active' => true,
                'notes' => 'Pelanggan baru, masih belajar layanan yang tersedia',
                'created_at' => Carbon::now()->subMonths(1),
                'updated_at' => Carbon::now()->subDays(6)
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
} 