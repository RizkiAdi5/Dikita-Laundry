<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi - {{ $order->order_number }}</title>
    <style>
        @media print {
            @page {
                margin: 0;
                size: 58mm auto;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm;
            margin: 0 auto;
            padding: 2mm;
            font-size: 9pt;
            line-height: 1.3;
            background: white;
        }

        .receipt {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 3mm;
            border-bottom: 1px dashed #000;
            padding-bottom: 3mm;
        }

        .store-name {
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 1mm;
            text-transform: uppercase;
        }

        .store-info {
            font-size: 8pt;
            line-height: 1.4;
        }

        .section {
            margin: 3mm 0;
            font-size: 8pt;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 1mm;
            font-size: 9pt;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin: 1mm 0;
            font-size: 8pt;
        }

        .row-label {
            flex: 0 0 35%;
        }

        .row-value {
            flex: 0 0 65%;
            text-align: right;
        }

        .items-table {
            width: 100%;
            margin: 2mm 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 2mm 0;
        }

        .item-row {
            margin: 1.5mm 0;
            font-size: 8pt;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 0.5mm;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
        }

        .totals {
            margin-top: 2mm;
            padding-top: 2mm;
            border-top: 1px dashed #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 1mm 0;
            font-size: 9pt;
        }

        .total-row.grand {
            font-weight: bold;
            font-size: 11pt;
            margin-top: 2mm;
            padding-top: 2mm;
            border-top: 1px solid #000;
        }

        .footer {
            text-align: center;
            margin-top: 3mm;
            padding-top: 3mm;
            border-top: 1px dashed #000;
            font-size: 8pt;
        }

        .status-badge {
            display: inline-block;
            padding: 1mm 2mm;
            border: 1px solid #000;
            border-radius: 2mm;
            font-weight: bold;
            margin: 2mm 0;
            font-size: 9pt;
        }

        .thank-you {
            margin-top: 2mm;
            font-weight: bold;
            font-size: 9pt;
        }

        .barcode {
            text-align: center;
            margin: 2mm 0;
            font-family: 'Libre Barcode 128', cursive;
            font-size: 24pt;
            letter-spacing: 0;
        }

        /* Print button - hidden when printing */
        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-family: Arial, sans-serif;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .print-button:hover {
            background: #1d4ed8;
        }

        .print-button:active {
            transform: scale(0.98);
        }

        .dotted-line {
            border-bottom: 1px dashed #000;
            margin: 2mm 0;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Print Resi
    </button>

    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="store-name">DIKITA LAUNDRY</div>
            <div class="store-info">
                Jl. Mawar No.11, Sekip Lama, <br>
                Kec. Singkawang Tengah, Kota Singkawang 
                , Kalimantan Barat 79113
            </div>
            <div class="store-info">
                0852-4573-6325
            </div>
        </div>

        <!-- Order Info -->
        <div class="section">
            <div class="row">
                <span class="row-label">No. Pesanan:</span>
                <span class="row-value"><strong>{{ $order->order_number }}</strong></span>
            </div>
            <div class="row">
                <span class="row-label">Tanggal:</span>
                <span class="row-value">{{ $order->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}</span>
            </div>
            <div class="row">
                <span class="row-label">Kasir:</span>
                <span class="row-value">{{ auth()->user()->name ?? 'System' }}</span>
            </div>
        </div>

        <div class="dotted-line"></div>

        <!-- Customer Info -->
        <div class="section">
            <div class="section-title">PELANGGAN</div>
            <div class="row">
                <span class="row-label">Nama:</span>
                <span class="row-value">{{ $order->customer->name }}</span>
            </div>
            <div class="row">
                <span class="row-label">Telepon:</span>
                <span class="row-value">{{ $order->customer->phone }}</span>
            </div>
            @if($order->customer->address)
            <div class="row">
                <span class="row-label">Alamat:</span>
                <span class="row-value">{{ Str::limit($order->customer->address, 30) }}</span>
            </div>
            @endif
        </div>

        <div class="dotted-line"></div>

        <!-- Delivery Dates -->
        @if($order->pickup_date || $order->delivery_date)
        <div class="section">
            @if($order->pickup_date)
            <div class="row">
                <span class="row-label">Tgl Terima:</span>
                <span class="row-value">{{ \Carbon\Carbon::parse($order->pickup_date)->format('d/m/Y') }}</span>
            </div>
            @endif
            @if($order->delivery_date)
            <div class="row">
                <span class="row-label">Tgl Selesai:</span>
                <span class="row-value">{{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</span>
            </div>
            @endif
        </div>
        <div class="dotted-line"></div>
        @endif

        <!-- Items -->
        <div class="items-table">
            <div class="section-title">DETAIL PESANAN</div>
            @foreach($order->items as $item)
            <div class="item-row">
                <div class="item-name">{{ $item->item_name }}</div>
                <div class="item-details">
                    <span>{{ number_format($item->quantity, 0) }} {{ $item->service->unit ?? 'pcs' }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                    <span><strong>Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong></span>
                </div>
                @if($item->notes)
                <div style="font-size: 7pt; font-style: italic; margin-top: 0.5mm;">
                    Catatan: {{ $item->notes }}
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($order->discount > 0)
            <div class="total-row">
                <span>Diskon:</span>
                <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($order->tax > 0)
            <div class="total-row">
                <span>Pajak:</span>
                <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row grand">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="dotted-line"></div>

        <!-- Payment Info -->
        <div class="section">
            <div class="section-title">PEMBAYARAN</div>
            <div class="row">
                <span class="row-label">Metode:</span>
                <span class="row-value">{{ ucfirst($order->payment_method ?? 'Cash') }}</span>
            </div>
            <div class="row">
                <span class="row-label">Dibayar:</span>
                <span class="row-value">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</span>
            </div>
            @php
                $remaining = $order->total - $order->paid_amount;
            @endphp
            @if($remaining > 0)
            <div class="row" style="font-weight: bold;">
                <span class="row-label">Sisa:</span>
                <span class="row-value">Rp {{ number_format($remaining, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="row">
                <span class="row-label">Status:</span>
                <span class="row-value">
                    @if($order->payment_status === 'paid')
                        <strong>LUNAS</strong>
                    @elseif($order->payment_status === 'partial')
                        <strong>SEBAGIAN</strong>
                    @else
                        <strong>BELUM BAYAR</strong>
                    @endif
                </span>
            </div>
        </div>

        <div class="dotted-line"></div>

        <!-- Status -->
        <div class="section" style="text-align: center;">
            <div class="status-badge">
                STATUS: {{ strtoupper($order->status->name ?? 'PENDING') }}
            </div>
        </div>

        @if($order->notes)
        <div class="dotted-line"></div>
        <div class="section">
            <div class="section-title">CATATAN</div>
            <div style="font-size: 8pt; font-style: italic;">
                {{ $order->notes }}
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">TERIMA KASIH</div>
            <div style="margin-top: 2mm; font-size: 7pt;">
                Barang yang sudah dicuci<br>
                tidak dapat dikembalikan<br>
                Simpan struk ini sebagai bukti
            </div>
            <div style="margin-top: 2mm; font-size: 7pt;">
                {{ now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}
            </div>
            <div class="thank-you">GRATIS ANTAR JEMPUT UNTUK AREA KOTA SINGKAWANG</div>
            <div class="thank-you">0852-4573-6325</div>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            // Small delay to ensure everything is loaded
            setTimeout(function() {
                // Uncomment the line below if you want auto-print
                // window.print();
            }, 500);
        };

        // Close window after printing (optional)
        window.onafterprint = function() {
            // Uncomment to auto-close after printing
            // window.close();
        };
    </script>
</body>
</html>