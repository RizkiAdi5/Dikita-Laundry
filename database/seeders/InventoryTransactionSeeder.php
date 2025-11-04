<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Employee;
use Carbon\Carbon;

class InventoryTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::first();

        $detergent = Inventory::where('sku', 'DET-001')->first();
        $softener  = Inventory::where('sku', 'FAB-001')->first();
        $plastic   = Inventory::where('sku', 'PLS-001')->first();

        // Pastikan item tersedia
        if (!$detergent || !$softener || !$plastic) {
            return; // InventorySeeder harus dijalankan terlebih dahulu
        }

        $now = Carbon::now();

        $transactions = [
            // Stok masuk detergen
            [
                'inventory_id' => $detergent->id,
                'employee_id' => $employee?->id,
                'type' => 'in',
                'quantity' => 20,
                'unit_price' => 14000,
                'total_amount' => 20 * 14000,
                'reference_number' => 'PO-DET-20250101',
                'reference_type' => 'purchase',
                'notes' => 'Restock detergen bubuk',
                'transaction_date' => $now->copy()->subDays(15)->toDateString(),
            ],
            // Pemakaian detergen
            [
                'inventory_id' => $detergent->id,
                'employee_id' => $employee?->id,
                'type' => 'out',
                'quantity' => 8,
                'unit_price' => 0,
                'total_amount' => 0,
                'reference_number' => 'USE-DET-20250110',
                'reference_type' => 'usage',
                'notes' => 'Pemakaian untuk pesanan minggu lalu',
                'transaction_date' => $now->copy()->subDays(10)->toDateString(),
            ],
            // Stok masuk pelembut
            [
                'inventory_id' => $softener->id,
                'employee_id' => $employee?->id,
                'type' => 'in',
                'quantity' => 10,
                'unit_price' => 11000,
                'total_amount' => 10 * 11000,
                'reference_number' => 'PO-FAB-20250105',
                'reference_type' => 'purchase',
                'notes' => 'Restock pelembut pakaian',
                'transaction_date' => $now->copy()->subDays(12)->toDateString(),
            ],
            // Pemakaian pelembut
            [
                'inventory_id' => $softener->id,
                'employee_id' => $employee?->id,
                'type' => 'out',
                'quantity' => 4,
                'unit_price' => 0,
                'total_amount' => 0,
                'reference_number' => 'USE-FAB-20250112',
                'reference_type' => 'usage',
                'notes' => 'Pemakaian untuk setrika dan finishing',
                'transaction_date' => $now->copy()->subDays(8)->toDateString(),
            ],
            // Stok masuk plastik laundry
            [
                'inventory_id' => $plastic->id,
                'employee_id' => $employee?->id,
                'type' => 'in',
                'quantity' => 300,
                'unit_price' => 280,
                'total_amount' => 300 * 280,
                'reference_number' => 'PO-PLS-20250108',
                'reference_type' => 'purchase',
                'notes' => 'Restock plastik laundry ukuran standar',
                'transaction_date' => $now->copy()->subDays(9)->toDateString(),
            ],
            // Pemakaian plastik laundry
            [
                'inventory_id' => $plastic->id,
                'employee_id' => $employee?->id,
                'type' => 'out',
                'quantity' => 120,
                'unit_price' => 0,
                'total_amount' => 0,
                'reference_number' => 'USE-PLS-20250114',
                'reference_type' => 'usage',
                'notes' => 'Pemakaian untuk pesanan selesai',
                'transaction_date' => $now->copy()->subDays(5)->toDateString(),
            ],
        ];

        foreach ($transactions as $tx) {
            InventoryTransaction::create($tx);
        }
    }
}