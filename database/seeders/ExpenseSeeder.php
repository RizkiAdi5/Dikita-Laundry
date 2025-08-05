<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\Employee;
use Carbon\Carbon;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        
        if ($employees->isEmpty()) {
            // Create a default employee if none exists
            $employee = Employee::create([
                'name' => 'Admin Default',
                'email' => 'admin@laundrydikita.com',
                'phone' => '081234567890',
                'position' => 'Manager',
                'role' => 'admin',
                'hire_date' => now(),
                'salary' => 5000000,
                'is_active' => true
            ]);
        } else {
            $employee = $employees->first();
        }

        $expenses = [
            [
                'title' => 'Pembelian Detergen',
                'description' => 'Pembelian detergen untuk kebutuhan laundry',
                'category' => 'inventory',
                'amount' => 250000,
                'payment_method' => 'cash',
                'status' => 'paid',
                'frequency' => 'one_time',
                'employee_id' => $employee->id,
                'supplier' => 'PT Sukses Makmur',
                'expense_date' => now()->subDays(5),
                'paid_date' => now()->subDays(5),
                'notes' => 'Pembelian rutin bulanan'
            ],
            [
                'title' => 'Bayar Listrik',
                'description' => 'Pembayaran tagihan listrik bulanan',
                'category' => 'utilities',
                'amount' => 850000,
                'payment_method' => 'bank_transfer',
                'status' => 'paid',
                'frequency' => 'monthly',
                'employee_id' => $employee->id,
                'supplier' => 'PLN',
                'expense_date' => now()->subDays(10),
                'paid_date' => now()->subDays(10),
                'notes' => 'Tagihan listrik bulan Januari 2025'
            ],
            [
                'title' => 'Gaji Karyawan',
                'description' => 'Pembayaran gaji karyawan bulanan',
                'category' => 'salary',
                'amount' => 3500000,
                'payment_method' => 'bank_transfer',
                'status' => 'paid',
                'frequency' => 'monthly',
                'employee_id' => $employee->id,
                'expense_date' => now()->subDays(15),
                'paid_date' => now()->subDays(15),
                'notes' => 'Gaji karyawan bulan Januari 2025'
            ],
            [
                'title' => 'Sewa Tempat',
                'description' => 'Pembayaran sewa tempat usaha',
                'category' => 'rent',
                'amount' => 2000000,
                'payment_method' => 'bank_transfer',
                'status' => 'pending',
                'frequency' => 'monthly',
                'employee_id' => $employee->id,
                'supplier' => 'PT Properti Sejahtera',
                'expense_date' => now()->addDays(5),
                'due_date' => now()->addDays(5),
                'notes' => 'Sewa tempat untuk bulan Februari 2025'
            ],
            [
                'title' => 'Maintenance Mesin Cuci',
                'description' => 'Perbaikan dan maintenance mesin cuci',
                'category' => 'maintenance',
                'amount' => 500000,
                'payment_method' => 'cash',
                'status' => 'approved',
                'frequency' => 'one_time',
                'employee_id' => $employee->id,
                'supplier' => 'Bengkel Teknik Jaya',
                'expense_date' => now()->subDays(2),
                'notes' => 'Maintenance rutin mesin cuci'
            ],
            [
                'title' => 'Pembelian Plastik',
                'description' => 'Pembelian plastik untuk packaging',
                'category' => 'inventory',
                'amount' => 150000,
                'payment_method' => 'cash',
                'status' => 'paid',
                'frequency' => 'one_time',
                'employee_id' => $employee->id,
                'supplier' => 'Toko Plastik Makmur',
                'expense_date' => now()->subDays(1),
                'paid_date' => now()->subDays(1),
                'notes' => 'Stok plastik packaging'
            ],
            [
                'title' => 'Bayar Air',
                'description' => 'Pembayaran tagihan air bulanan',
                'category' => 'utilities',
                'amount' => 120000,
                'payment_method' => 'bank_transfer',
                'status' => 'pending',
                'frequency' => 'monthly',
                'employee_id' => $employee->id,
                'supplier' => 'PDAM',
                'expense_date' => now()->addDays(3),
                'due_date' => now()->addDays(3),
                'notes' => 'Tagihan air bulan Januari 2025'
            ],
            [
                'title' => 'Pemasaran Digital',
                'description' => 'Biaya iklan digital dan sosial media',
                'category' => 'marketing',
                'amount' => 300000,
                'payment_method' => 'card',
                'status' => 'paid',
                'frequency' => 'monthly',
                'employee_id' => $employee->id,
                'supplier' => 'Facebook Ads',
                'expense_date' => now()->subDays(7),
                'paid_date' => now()->subDays(7),
                'notes' => 'Iklan Facebook dan Instagram'
            ],
            [
                'title' => 'Asuransi Usaha',
                'description' => 'Pembayaran premi asuransi usaha',
                'category' => 'insurance',
                'amount' => 750000,
                'payment_method' => 'bank_transfer',
                'status' => 'approved',
                'frequency' => 'yearly',
                'employee_id' => $employee->id,
                'supplier' => 'PT Asuransi Sejahtera',
                'expense_date' => now()->addDays(10),
                'due_date' => now()->addDays(10),
                'notes' => 'Asuransi kebakaran dan kehilangan'
            ],
            [
                'title' => 'Pajak Usaha',
                'description' => 'Pembayaran pajak usaha bulanan',
                'category' => 'tax',
                'amount' => 450000,
                'payment_method' => 'bank_transfer',
                'status' => 'pending',
                'frequency' => 'monthly',
                'employee_id' => $employee->id,
                'supplier' => 'Kantor Pajak',
                'expense_date' => now()->addDays(7),
                'due_date' => now()->addDays(7),
                'notes' => 'Pajak PPh Pasal 25'
            ]
        ];

        foreach ($expenses as $expenseData) {
            Expense::create($expenseData);
        }
    }
} 