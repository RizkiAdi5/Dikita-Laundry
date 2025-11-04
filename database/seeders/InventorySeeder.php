<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Detergen Bubuk',
                'sku' => 'DET-001',
                'description' => 'Detergen bubuk untuk cuci reguler',
                'category' => 'detergen',
                'unit' => 'kg',
                'quantity' => 50,
                'min_quantity' => 10,
                'cost_price' => 15000,
                'selling_price' => 20000,
                'supplier' => 'PT Sukses Makmur',
                'location' => 'Rak A1',
                'is_active' => true,
            ],
            [
                'name' => 'Pelembut Pakaian',
                'sku' => 'FAB-001',
                'description' => 'Pelembut pakaian cair',
                'category' => 'pelembut',
                'unit' => 'liter',
                'quantity' => 30,
                'min_quantity' => 8,
                'cost_price' => 12000,
                'selling_price' => 17000,
                'supplier' => 'PT Wangi Jaya',
                'location' => 'Rak A2',
                'is_active' => true,
            ],
            [
                'name' => 'Pemutih',
                'sku' => 'BLE-001',
                'description' => 'Pemutih pakaian untuk noda membandel',
                'category' => 'pemutih',
                'unit' => 'liter',
                'quantity' => 20,
                'min_quantity' => 5,
                'cost_price' => 10000,
                'selling_price' => 15000,
                'supplier' => 'PT Bersih Sejahtera',
                'location' => 'Rak B1',
                'is_active' => true,
            ],
            [
                'name' => 'Plastik Laundry',
                'sku' => 'PLS-001',
                'description' => 'Plastik pembungkus laundry ukuran standar',
                'category' => 'plastik',
                'unit' => 'pcs',
                'quantity' => 500,
                'min_quantity' => 100,
                'cost_price' => 300,
                'selling_price' => 500,
                'supplier' => 'PT Kemasan Nusantara',
                'location' => 'Rak C1',
                'is_active' => true,
            ],
            [
                'name' => 'Hanger',
                'sku' => 'HNG-001',
                'description' => 'Hanger plastik untuk pakaian',
                'category' => 'gantungan',
                'unit' => 'pcs',
                'quantity' => 200,
                'min_quantity' => 50,
                'cost_price' => 1500,
                'selling_price' => 2500,
                'supplier' => 'PT Perkakas Rumah',
                'location' => 'Rak C2',
                'is_active' => true,
            ],
            [
                'name' => 'Suku Cadang Mesin Cuci',
                'sku' => 'EQP-001',
                'description' => 'Suku cadang untuk perawatan mesin cuci',
                'category' => 'peralatan',
                'unit' => 'pcs',
                'quantity' => 15,
                'min_quantity' => 3,
                'cost_price' => 50000,
                'selling_price' => 75000,
                'supplier' => 'PT Mesin Bersih',
                'location' => 'Gudang',
                'is_active' => true,
            ],
            [
                'name' => 'Parfum Laundry',
                'sku' => 'PRF-001',
                'description' => 'Parfum konsentrat untuk pewangi pakaian',
                'category' => 'parfum',
                'unit' => 'liter',
                'quantity' => 25,
                'min_quantity' => 5,
                'cost_price' => 25000,
                'selling_price' => 35000,
                'supplier' => 'PT Wangi Sejahtera',
                'location' => 'Rak B2',
                'is_active' => true,
            ],
            [
                'name' => 'Pembersih Noda',
                'sku' => 'STN-001',
                'description' => 'Cairan penghilang noda untuk pretreatment',
                'category' => 'pembersih_noda',
                'unit' => 'liter',
                'quantity' => 12,
                'min_quantity' => 3,
                'cost_price' => 30000,
                'selling_price' => 45000,
                'supplier' => 'PT Bersih Prima',
                'location' => 'Rak B3',
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            Inventory::firstOrCreate(
                ['sku' => $item['sku']],
                $item
            );
        }
    }
}