@extends('layouts.app')

@section('title', 'Laporan Stok - LaundryDikita')

@section('page-title', 'Laporan Stok')

@section('content')
<!-- Navigation Links -->
<div class="mb-4">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Laporan
        </a>
        <a href="{{ route('reports.performance') }}" class="px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors text-sm">
            <i class="fas fa-chart-line mr-1"></i> Laporan Performa
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ route('reports.stock') }}" class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Filter</h3>
            <p class="text-sm text-gray-500">Pilih kategori untuk memfokuskan laporan</p>
        </div>
        <div class="flex gap-3">
            <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ ($category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <input type="text" name="supplier" value="{{ $supplier ?? '' }}" placeholder="Filter supplier" class="px-4 py-2 border border-gray-300 rounded-lg" />
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">Terapkan</button>
            <a href="{{ route('reports.stock.export', request()->only(['category','supplier'])) }}" class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">Export CSV</a>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    @php
        $totalItems = $allItems->count();
        $ready = $healthyItems->count();
        $low = $lowStockItems->count();
        $out = $outOfStockItems->count();
    @endphp
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4"><i class="fas fa-box"></i></div>
            <div>
                <div class="text-sm text-gray-600">Total Item</div>
                <div class="text-2xl font-semibold text-gray-900">{{ number_format($totalItems, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4"><i class="fas fa-check"></i></div>
            <div>
                <div class="text-sm text-gray-600">Ready</div>
                <div class="text-2xl font-semibold text-gray-900">{{ number_format($ready, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <div class="text-sm text-gray-600">Low</div>
                <div class="text-2xl font-semibold text-gray-900">{{ number_format($low, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4"><i class="fas fa-times"></i></div>
            <div>
                <div class="text-sm text-gray-600">Habis</div>
                <div class="text-2xl font-semibold text-gray-900">{{ number_format($out, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-md font-semibold text-gray-800 mb-2">Stok Rendah</h3>
        <div class="space-y-3">
            @forelse($lowStockItems as $item)
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                    <div class="text-xs text-gray-500">Min: {{ rtrim(rtrim(number_format((float)$item->min_quantity, 2, ',', '.'), '0'), ',') }} | Qty: {{ rtrim(rtrim(number_format((float)$item->quantity, 2, ',', '.'), '0'), ',') }} {{ $item->unit }}</div>
                </div>
                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Low</span>
            </div>
            @empty
            <div class="text-sm text-gray-500">Tidak ada item stok rendah.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-md font-semibold text-gray-800 mb-2">Stok Habis</h3>
        <div class="space-y-3">
            @forelse($outOfStockItems as $item)
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                    <div class="text-xs text-gray-500">Kategori: {{ $item->category ?? '-' }}</div>
                </div>
                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Habis</span>
            </div>
            @empty
            <div class="text-sm text-gray-500">Tidak ada item stok habis.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-md font-semibold text-gray-800 mb-2">Ringkasan per Kategori</h3>
        <div class="space-y-3">
            @forelse($byCategory as $row)
            <div>
                <div class="flex items-center justify-between">
                    <div class="text-sm font-medium text-gray-900">{{ $row['category'] ?: 'Tanpa Kategori' }}</div>
                    <div class="text-xs text-gray-500">{{ $row['total_items'] }} item</div>
                </div>
                <div class="flex gap-2 text-xs text-gray-600 mt-1">
                    <span class="inline-flex px-2 py-0.5 rounded-full bg-green-100 text-green-800">Ready: {{ $row['in_stock'] }}</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">Low: {{ $row['low_stock'] }}</span>
                    <span class="inline-flex px-2 py-0.5 rounded-full bg-red-100 text-red-800">Habis: {{ $row['out_of_stock'] }}</span>
                </div>
            </div>
            @empty
            <div class="text-sm text-gray-500">Tidak ada data kategori.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-md font-semibold text-gray-800">Transaksi Stok Terbaru (14 hari)</h3>
        <a href="{{ route('inventory.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Kelola Inventori →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentTransactions as $trx)
                <tr>
                    <td class="px-6 py-3 text-sm text-gray-900">{{ $trx->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-3 text-sm text-gray-900">{{ $trx->inventory->name ?? '-' }}</td>
                    <td class="px-6 py-3 text-sm">
                        @php $type = $trx->type ?? 'in'; @endphp
                        <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $type === 'in' ? 'Masuk' : 'Keluar' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-sm text-right text-gray-900">{{ rtrim(rtrim(number_format((float)$trx->quantity, 2, ',', '.'), '0'), ',') }}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">{{ $trx->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">Tidak ada transaksi stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


