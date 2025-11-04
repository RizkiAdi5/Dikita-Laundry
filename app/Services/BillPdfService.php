<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class BillPdfService
{
    /**
     * Render order bill as PDF bytes. Requires barryvdh/laravel-dompdf.
     */
    public function render(Order $order): string
    {
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            throw new \RuntimeException('PDF generator not installed. Please run: composer require barryvdh/laravel-dompdf');
        }

        $html = View::make('orders.bill', [
            'order' => $order->load(['customer','items.service','status']),
            'company' => [
                'name' => 'DIKITA LAUNDRY',
                'address' => 'Jl. Mawar No.11, Sekip Lama, Kec. Singkawang Tengah, Kota Singkawang, Kalimantan Barat 79113',
                'phone' => '0852-4573-6325',
                'email' => 'info@dikita-laundry.com',
            ],
        ])->render();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4');
        return $pdf->output();
    }

    /**
     * Generate and save PDF to storage, returns relative storage path.
     */
    public function saveToStorage(Order $order): string
    {
        $bytes = $this->render($order);
        $filename = 'bills/'.$order->order_number.'-bill.pdf';
        Storage::disk('public')->put($filename, $bytes);
        return Storage::disk('public')->path($filename);
    }
}


