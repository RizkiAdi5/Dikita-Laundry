<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Employee;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employee = Employee::first();

        $orders = Order::whereIn('payment_status', ['paid', 'partial'])->get();
        if ($orders->isEmpty()) {
            return; // Pastikan OrderSeeder sudah berjalan
        }

        $now = Carbon::now();
        $counter = 1;

        foreach ($orders as $order) {
            $amount = $order->payment_status === 'paid' ? $order->total : $order->paid_amount;
            if ($amount <= 0) continue;

            Payment::firstOrCreate(
                ['payment_number' => 'PAY-' . str_pad((string)$counter, 4, '0', STR_PAD_LEFT)],
                [
                    'order_id' => $order->id,
                    'employee_id' => $employee?->id,
                    'amount' => $amount,
                    'payment_method' => $order->payment_status === 'paid' ? 'qris' : 'cash',
                    'status' => 'completed',
                    'reference_number' => null,
                    'notes' => $order->payment_status === 'partial' ? 'Pembayaran uang muka/DP' : 'Pembayaran lunas',
                    'paid_at' => $now->copy()->subDays(1),
                ]
            );

            $counter++;
        }
    }
}