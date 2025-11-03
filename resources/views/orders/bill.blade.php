<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tagihan {{ $order->order_number }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px; }
        .company h1 { margin: 0 0 4px 0; font-size: 20px; }
        .muted { color: #666; }
        .right { text-align: right; }
        .table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background: #f5f5f5; }
        .totals { width: 100%; margin-top: 10px; }
        .totals td { padding: 4px 8px; }
        .totals .label { text-align: right; }
        .footer { margin-top: 24px; font-size: 11px; color: #555; }
    </style>
    </head>
<body>
    <div class="header">
        <div class="company">
            <h1>{{ $company['name'] }}</h1>
            <div class="muted">{{ $company['address'] }}</div>
            <div class="muted">Tel: {{ $company['phone'] }} | {{ $company['email'] }}</div>
        </div>
        <div class="right">
            <div><strong>Tagihan</strong></div>
            <div>No: {{ $order->order_number }}</div>
            <div>Tanggal: {{ $order->created_at->format('d M Y') }}</div>
        </div>
    </div>

    <div>
        <strong>Kepada Yth:</strong><br>
        {{ $order->customer->name }}<br>
        {{ $order->customer->phone }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 36px;">No</th>
                <th>Deskripsi</th>
                <th style="width: 80px;" class="right">Qty</th>
                <th style="width: 120px;" class="right">Harga</th>
                <th style="width: 120px;" class="right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $i => $item)
            <tr>
                <td class="right">{{ $i+1 }}</td>
                <td>{{ $item->item_name }}</td>
                <td class="right">{{ rtrim(rtrim(number_format((float)$item->quantity, 2, ',', '.'), '0'), ',') }}</td>
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
            <td class="right">Rp {{ number_format((float)$order->discount, 0, ',', '.') }}</td>
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
        <tr>
            <td class="label">Status Pembayaran:</td>
            <td class="right">{{ strtoupper($order->payment_status) }}</td>
        </tr>
    </table>

    <div class="footer">
        Terima kasih atas kepercayaan Anda. Simpan tagihan ini sebagai bukti transaksi.
    </div>
</body>
</html>


