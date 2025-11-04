<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tagihan {{ $order->order_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; margin: 0; padding: 24px; background: #fff; }
        .invoice { background: #fff; border-radius: 8px; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px dashed #000; padding-bottom: 12px; margin-bottom: 16px; }
        .company h1 { margin: 0 0 4px 0; font-size: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .muted { color: #555; }
        .right { text-align: right; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; background: #f3f4f6; }
        .section-title { font-weight: 700; font-size: 13px; margin: 8px 0; text-transform: uppercase; letter-spacing: 0.3px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .info { font-size: 12px; }
        .label { color: #6b7280; }
        .table { width: 100%; border-collapse: collapse; margin-top: 12px; border-top: 1px dashed #000; border-bottom: 1px dashed #000; }
        .table th, .table td { border: none; padding: 8px; }
        .table th { background: #fff; text-align: left; }
        .table td.right, .table th.right { text-align: right; }
        .totals { width: 100%; margin-top: 10px; }
        .totals td { padding: 4px 8px; }
        .totals .label { text-align: right; }
        .footer { margin-top: 18px; font-size: 11px; color: #4b5563; text-align: center; }
        .note { font-size: 12px; padding: 10px; background: #fff; border-radius: 6px; margin-top: 10px; }
    </style>
    </head>
<body>
<div class="invoice">
    <div class="header">
        <div class="company">
            <h1>{{ $company['name'] }}</h1>
            <div class="muted">{{ $company['address'] }}</div>
            <div class="muted">Tel: {{ $company['phone'] }} | {{ $company['email'] }}</div>
        </div>
        <div class="right">
            <div class="badge">TAGIHAN</div>
            <div>No. Pesanan: <strong>{{ $order->order_number }}</strong></div>
            <div>Tanggal: {{ $order->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}</div>
            <div>Kasir: {{ auth()->user()->name ?? 'System' }}</div>
        </div>
    </div>

    <div class="grid-2 info">
        <div>
            <div class="section-title">Pelanggan</div>
            <div><span class="label">Nama:</span> {{ $order->customer->name }}</div>
            <div><span class="label">Telepon:</span> {{ $order->customer->phone }}</div>
            @if($order->customer->address)
            <div><span class="label">Alamat:</span> {{ $order->customer->address }}</div>
            @endif
        </div>
        <div>
            <div class="section-title">Tgl Terima/Selesai</div>
            @if($order->pickup_date)
            <div><span class="label">Terima:</span> {{ \Carbon\Carbon::parse($order->pickup_date)->format('d/m/Y') }}</div>
            @endif
            @if($order->delivery_date)
            <div><span class="label">Selesai:</span> {{ \Carbon\Carbon::parse($order->delivery_date)->format('d/m/Y') }}</div>
            @endif
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 36px;">No</th>
                <th>Deskripsi</th>
                <th style="width: 100px;" class="right">Qty</th>
                <th style="width: 120px;" class="right">Harga</th>
                <th style="width: 120px;" class="right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td class="right">{{ $i+1 }}</td>
                <td>
                    <div style="font-weight: 600;">{{ $item->item_name }}</div>
                    @if($item->notes)
                    <div style="font-size: 11px; color:#6b7280;">Catatan: {{ $item->notes }}</div>
                    @endif
                </td>
                <td class="right">{{ number_format((float)$item->quantity, 0) }} {{ $item->service->unit ?? 'pcs' }}</td>
                <td class="right">Rp {{ number_format((float)$item->unit_price, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format((float)$item->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="label" style="width: 80%;">Subtotal:</td>
            <td class="right">Rp {{ number_format((float)$order->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if((float)$order->discount > 0)
        <tr>
            <td class="label">Diskon:</td>
            <td class="right">- Rp {{ number_format((float)$order->discount, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if((float)$order->tax > 0)
        <tr>
            <td class="label">Pajak:</td>
            <td class="right">Rp {{ number_format((float)$order->tax, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td class="label"><strong>Total:</strong></td>
            <td class="right"><strong>Rp {{ number_format((float)$order->total, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td class="label">Dibayar:</td>
            <td class="right">Rp {{ number_format((float)$order->paid_amount, 0, ',', '.') }}</td>
        </tr>
        @php $remaining = (float)$order->total - (float)$order->paid_amount; @endphp
        @if($remaining > 0)
        <tr>
            <td class="label">Sisa:</td>
            <td class="right">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Metode Pembayaran:</td>
            <td class="right">{{ ucfirst($order->payment_method ?? 'Cash') }}</td>
        </tr>
        <tr>
            <td class="label">Status Pembayaran:</td>
            <td class="right">
                @if($order->payment_status === 'paid')
                    LUNAS
                @elseif($order->payment_status === 'partial')
                    SEBAGIAN
                @else
                    BELUM BAYAR
                @endif
            </td>
        </tr>
    </table>

    <div style="margin-top: 10px; text-align: center;">
        <span class="badge">STATUS: {{ strtoupper($order->status->name ?? 'PENDING') }}</span>
    </div>

    @if($order->notes)
    <div class="note">
        <div class="section-title" style="margin:0 0 6px 0;">Catatan</div>
        {{ $order->notes }}
    </div>
    @endif

    <div class="footer">
        <div style="font-weight: 700; letter-spacing: 0.5px;">TERIMA KASIH</div>
        <div style="margin-top: 4px;">
            Barang yang sudah dicuci tidak dapat dikembalikan. Simpan dokumen ini sebagai bukti.
        </div>
        <div style="margin-top: 6px; font-size: 11px; color:#6b7280;">
            Dicetak: {{ now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}
        </div>
    </div>
</div>
</body>
</html>