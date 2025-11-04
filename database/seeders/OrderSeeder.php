<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use App\Models\OrderStatus;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::take(5)->get();
        $employee  = Employee::first();
        $services  = Service::active()->get();

        if ($customers->isEmpty() || $services->isEmpty()) {
            return; // Pastikan CustomerSeeder dan ServiceSeeder sudah berjalan
        }

        $statusMap = [
            'new' => OrderStatus::where('name', 'Menunggu')->first(),
            'processing' => OrderStatus::where('name', 'Dalam Proses')->first(),
            'ready' => OrderStatus::where('name', 'Siap')->first(),
            'done' => OrderStatus::where('name', 'Selesai')->first(),
            'cancel' => OrderStatus::where('name', 'Batal')->first(),
        ];

        $now = Carbon::now();

        $ordersData = [
            [
                'status' => 'new',
                'pickup_offset' => 0,
                'delivery_offset' => 2,
                'items' => [
                    ['service' => 'Cuci Reguler', 'item_name' => 'Cuci Reguler - Pakaian', 'qty' => 5, 'price' => 8000],
                ],
                'payment' => ['type' => 'unpaid']
            ],
            [
                'status' => 'processing',
                'pickup_offset' => -1,
                'delivery_offset' => 1,
                'items' => [
                    ['service' => 'Cuci Setrika', 'item_name' => 'Cuci + Setrika - Campur', 'qty' => 3, 'price' => 12000],
                    ['service' => 'Setrika Saja', 'item_name' => 'Setrika - Kemeja', 'qty' => 4, 'price' => 5000],
                ],
                'payment' => ['type' => 'partial', 'paid' => 20000]
            ],
            [
                'status' => 'ready',
                'pickup_offset' => -2,
                'delivery_offset' => 0,
                'items' => [
                    ['service' => 'Dry Clean', 'item_name' => 'Dry Clean - Jas', 'qty' => 2, 'price' => 25000],
                ],
                'payment' => ['type' => 'unpaid']
            ],
            [
                'status' => 'done',
                'pickup_offset' => -3,
                'delivery_offset' => -1,
                'items' => [
                    ['service' => 'Cuci Reguler', 'item_name' => 'Cuci Reguler - Pakaian Anak', 'qty' => 4, 'price' => 8000],
                    ['service' => 'Cuci Selimut', 'item_name' => 'Cuci Selimut - Bed Cover', 'qty' => 1, 'price' => 35000],
                ],
                'payment' => ['type' => 'paid']
            ],
            [
                'status' => 'cancel',
                'pickup_offset' => 0,
                'delivery_offset' => null,
                'items' => [
                    ['service' => 'Cuci Dingin', 'item_name' => 'Cuci Dingin - Baju Batik', 'qty' => 2, 'price' => 10000],
                ],
                'payment' => ['type' => 'unpaid']
            ],
        ];

        foreach ($ordersData as $index => $data) {
            $customer = $customers[$index % $customers->count()];
            $status   = $statusMap[$data['status']];

            if (!$status) continue;

            $pickupDate = $data['pickup_offset'] !== null ? $now->copy()->addDays($data['pickup_offset'])->toDateString() : null;
            $deliveryDate = $data['delivery_offset'] !== null ? $now->copy()->addDays($data['delivery_offset'])->toDateString() : null;

            // Hitung subtotal dari item
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $subtotal += $item['qty'] * $item['price'];
            }

            $discount = 0;
            $tax = 0; // Bisa diubah jika perlu
            $total = $subtotal - $discount + $tax;

            $paidAmount = 0;
            $paymentStatus = 'unpaid';
            if ($data['payment']['type'] === 'paid') {
                $paidAmount = $total;
                $paymentStatus = 'paid';
            } elseif ($data['payment']['type'] === 'partial') {
                $paidAmount = $data['payment']['paid'] ?? 0;
                $paymentStatus = $paidAmount > 0 ? 'partial' : 'unpaid';
            }

            $order = Order::create([
                'order_number' => 'ORD-' . str_pad((string)($index + 1), 4, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'employee_id' => $employee?->id,
                'order_status_id' => $status->id,
                'pickup_date' => $pickupDate,
                'delivery_date' => $deliveryDate,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'payment_status' => $paymentStatus,
                'notes' => $data['status'] === 'cancel' ? 'Pesanan dibatalkan oleh pelanggan' : null,
                'special_instructions' => null,
            ]);

            foreach ($data['items'] as $item) {
                $service = $services->firstWhere('name', $item['service']);
                if (!$service) continue;

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_id' => $service->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['qty'] * $item['price'],
                    'notes' => null,
                ]);
            }
        }
    }
}