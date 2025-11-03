@extends('layouts.app')

@section('title', 'Detail Pesanan - '.$order->order_number)
@section('page-title', 'Detail Pesanan')

@section('content')
<div class="mb-4">
    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline"><i class="fas fa-arrow-left mr-1"></i> Kembali ke daftar</a>
    <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('orders.bill', $order) }}" target="_blank" class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800">
            <i class="fas fa-file-pdf mr-2"></i> Lihat PDF Tagihan
        </a>
        <a href="{{ route('orders.edit', $order) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            <i class="fas fa-edit mr-2"></i> Edit Pesanan
        </a>
        <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan {{ $order->order_number }}?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                <i class="fas fa-trash mr-2"></i> Hapus
            </button>
        </form>
        
    </div>
    
</div>

    

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">{{ $order->order_number }}</h3>
      <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $order->status->name }}</span>
    </div>
    <div class="text-sm text-gray-600 mb-4">
      Tanggal: {{ $order->created_at->format('d M Y H:i') }}
      @if($order->delivery_date)
        · Estimasi Selesai: {{ $order->delivery_date->format('d M Y') }}
      @endif
    </div>

    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
          <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
          <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
          <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @foreach($order->items as $item)
        <tr>
          <td class="px-4 py-2 text-sm text-gray-900">{{ $item->item_name }}</td>
          <td class="px-4 py-2 text-sm text-gray-900 text-right">{{ rtrim(rtrim(number_format((float)$item->quantity, 2, ',', '.'), '0'), ',') }}</td>
          <td class="px-4 py-2 text-sm text-gray-900 text-right">Rp {{ number_format((float)$item->unit_price, 0, ',', '.') }}</td>
          <td class="px-4 py-2 text-sm text-gray-900 text-right">Rp {{ number_format((float)$item->total_price, 0, ',', '.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <div class="mt-4 flex justify-end">
      <div class="w-full md:w-1/2">
        <div class="flex justify-between text-sm py-1">
          <span class="text-gray-600">Subtotal</span>
          <span>Rp {{ number_format((float)$order->subtotal, 0, ',', '.') }}</span>
        </div>
        @if((float)$order->discount > 0)
        <div class="flex justify-between text-sm py-1">
          <span class="text-gray-600">Diskon</span>
          <span>Rp {{ number_format((float)$order->discount, 0, ',', '.') }}</span>
        </div>
        @endif
        @if((float)$order->tax > 0)
        <div class="flex justify-between text-sm py-1">
          <span class="text-gray-600">Pajak</span>
          <span>Rp {{ number_format((float)$order->tax, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="flex justify-between text-sm py-2 border-t mt-1">
          <span class="font-semibold">Total</span>
          <span class="font-semibold">Rp {{ number_format((float)$order->total, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm py-1">
          <span class="text-gray-600">Dibayar</span>
          <span>Rp {{ number_format((float)$order->paid_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm py-1">
          <span class="text-gray-600">Status Pembayaran</span>
          <span class="uppercase">{{ $order->payment_status }}</span>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-white border border-gray-200 rounded-lg p-6">
    <h4 class="text-md font-semibold mb-2">Pelanggan</h4>
    <div class="text-sm text-gray-900">{{ $order->customer->name }}</div>
    <div class="text-sm text-gray-600">{{ $order->customer->phone }}</div>
    @if($order->notes)
    <div class="mt-4">
      <h4 class="text-md font-semibold mb-1">Catatan</h4>
      <div class="text-sm text-gray-700 whitespace-pre-line">{{ $order->notes }}</div>
    </div>
    @endif
  </div>
</div>
@endsection

@section('scripts')
@endsection


