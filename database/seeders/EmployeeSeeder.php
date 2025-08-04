<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad@laundrydikita.com',
                'phone' => '081234567890',
                'position' => 'General Manager',
                'role' => 'manager',
                'hire_date' => '2023-01-15',
                'salary' => 4500000,
                'address' => 'Jl. Sudirman No. 123, Jakarta',
                'gender' => 'male',
                'birth_date' => '1995-03-15',
                'is_active' => true,
                'notes' => 'Karyawan berprestasi dengan pengalaman 5 tahun di industri laundry'
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@laundrydikita.com',
                'phone' => '081234567891',
                'position' => 'Cashier',
                'role' => 'cashier',
                'hire_date' => '2023-03-10',
                'salary' => 3200000,
                'address' => 'Jl. Thamrin No. 45, Jakarta',
                'gender' => 'female',
                'birth_date' => '1998-07-22',
                'is_active' => true,
                'notes' => 'Ramah dan teliti dalam melayani pelanggan'
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@laundrydikita.com',
                'phone' => '081234567892',
                'position' => 'Laundry Operator',
                'role' => 'operator',
                'hire_date' => '2023-04-25',
                'salary' => 2800000,
                'address' => 'Jl. Gatot Subroto No. 67, Jakarta',
                'gender' => 'male',
                'birth_date' => '1993-11-08',
                'is_active' => true,
                'notes' => 'Ahli dalam mengoperasikan mesin laundry'
            ],
            [
                'name' => 'Dewi Wati',
                'email' => 'dewi@laundrydikita.com',
                'phone' => '081234567893',
                'position' => 'Delivery Driver',
                'role' => 'delivery',
                'hire_date' => '2023-05-05',
                'salary' => 3000000,
                'address' => 'Jl. Kebayoran Lama No. 89, Jakarta',
                'gender' => 'female',
                'birth_date' => '1996-12-14',
                'is_active' => false,
                'notes' => 'Sedang cuti karena sakit'
            ],
            [
                'name' => 'Rudi Kurniawan',
                'email' => 'rudi@laundrydikita.com',
                'phone' => '081234567894',
                'position' => 'Branch Manager',
                'role' => 'manager',
                'hire_date' => '2023-06-12',
                'salary' => 4200000,
                'address' => 'Jl. Senayan No. 12, Jakarta',
                'gender' => 'male',
                'birth_date' => '1991-05-30',
                'is_active' => true,
                'notes' => 'Bertanggung jawab atas operasional cabang'
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya@laundrydikita.com',
                'phone' => '081234567895',
                'position' => 'Admin',
                'role' => 'admin',
                'hire_date' => '2023-07-20',
                'salary' => 3500000,
                'address' => 'Jl. Kuningan No. 34, Jakarta',
                'gender' => 'female',
                'birth_date' => '1997-09-18',
                'is_active' => true,
                'notes' => 'Mengelola administrasi dan keuangan'
            ],
            [
                'name' => 'Joko Widodo',
                'email' => 'joko@laundrydikita.com',
                'phone' => '081234567896',
                'position' => 'Laundry Operator',
                'role' => 'operator',
                'hire_date' => '2023-08-15',
                'salary' => 2800000,
                'address' => 'Jl. Sudirman No. 56, Jakarta',
                'gender' => 'male',
                'birth_date' => '1994-02-25',
                'is_active' => true,
                'notes' => 'Spesialis cuci kering dan setrika'
            ],
            [
                'name' => 'Sri Wahyuni',
                'email' => 'sri@laundrydikita.com',
                'phone' => '081234567897',
                'position' => 'Cashier',
                'role' => 'cashier',
                'hire_date' => '2023-09-10',
                'salary' => 3200000,
                'address' => 'Jl. Thamrin No. 78, Jakarta',
                'gender' => 'female',
                'birth_date' => '1999-04-12',
                'is_active' => true,
                'notes' => 'Pelayanan pelanggan yang sangat baik'
            ],
            [
                'name' => 'Agus Setiawan',
                'email' => 'agus@laundrydikita.com',
                'phone' => '081234567898',
                'position' => 'Delivery Driver',
                'role' => 'delivery',
                'hire_date' => '2023-10-05',
                'salary' => 3000000,
                'address' => 'Jl. Gatot Subroto No. 90, Jakarta',
                'gender' => 'male',
                'birth_date' => '1992-08-07',
                'is_active' => true,
                'notes' => 'Pengalaman 3 tahun sebagai kurir'
            ],
            [
                'name' => 'Nina Kartika',
                'email' => 'nina@laundrydikita.com',
                'phone' => '081234567899',
                'position' => 'Quality Control',
                'role' => 'operator',
                'hire_date' => '2023-11-20',
                'salary' => 3200000,
                'address' => 'Jl. Kebayoran Baru No. 23, Jakarta',
                'gender' => 'female',
                'birth_date' => '1996-01-30',
                'is_active' => true,
                'notes' => 'Memastikan kualitas layanan laundry'
            ]
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
} 