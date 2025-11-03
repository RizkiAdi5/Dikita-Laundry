@extends('layouts.app')

@section('title', 'Detail Inventori - LaundryDikita')
@section('page-title', 'Detail Inventori')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">{{ $inventory->name }}</h3>
                <p class="text-sm text-gray-500">SKU: {{ $inventory->sku }}</p>
            </div>
            <a href="{{ route('inventory.edit', $inventory) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Edit</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Kategori</p>
                <p class="text-gray-900 font-medium mt-1">{{ str_replace('_',' ', ucfirst($inventory->category)) }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Stok</p>
                <p class="text-gray-900 font-medium mt-1">{{ number_format($inventory->quantity, 2) }} {{ $inventory->unit }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Min. Stok</p>
                <p class="text-gray-900 font-medium mt-1">{{ number_format($inventory->min_quantity, 2) }} {{ $inventory->unit }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Harga Modal</p>
                <p class="text-gray-900 font-medium mt-1">{{ $inventory->formatted_cost_price }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Harga Jual</p>
                <p class="text-gray-900 font-medium mt-1">{{ $inventory->formatted_selling_price }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600">Status</p>
                <p class="text-gray-900 font-medium mt-1">
                    @php $status = $inventory->stock_status; @endphp
                    @if($status === 'in_stock')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Stok Aman</span>
                    @elseif($status === 'low_stock')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Stok Menipis</span>
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Stok Habis</span>
                    @endif
                </p>
            </div>
        </div>
        @if($inventory->description)
        <div class="mt-6">
            <p class="text-sm text-gray-600">Deskripsi</p>
            <p class="mt-1 text-gray-800">{{ $inventory->description }}</p>
        </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Transaksi</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga/Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referensi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($inventory->transactions as $trx)
                    <tr>
                        <td class="px-6 py-3 text-sm">{{ $trx->transaction_date->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-sm">
                            @if($trx->type==='in')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Masuk</span>
                            @elseif($trx->type==='out')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Keluar</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Penyesuaian</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm">{{ number_format($trx->quantity, 2) }} {{ $inventory->unit }}</td>
                        <td class="px-6 py-3 text-sm">Rp {{ number_format($trx->unit_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm">{{ $trx->reference_number ?? '-' }} ({{ $trx->reference_type }})</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

