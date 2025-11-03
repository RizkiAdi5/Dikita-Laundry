@extends('layouts.app')

@section('title', 'Laporan Performa - LaundryDikita')

@section('page-title', 'Laporan Performa')

@section('content')
<!-- Navigation Links -->
<div class="mb-4">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('reports.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Laporan
        </a>
        <a href="{{ route('reports.stock') }}" class="px-4 py-2 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors text-sm">
            <i class="fas fa-box-open mr-1"></i> Laporan Stok
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <form method="GET" action="{{ route('reports.performance') }}" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Filter Periode</h3>
            <p class="text-sm text-gray-500">Pilih periode dan tanggal acuan</p>
            <p class="text-xs text-gray-400 mt-1">
                Periode: {{ \Carbon\Carbon::parse($startDate ?? now())->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate ?? now())->format('d M Y') }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
            <select name="period" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="daily" {{ ($period ?? 'monthly') == 'daily' ? 'selected' : '' }}>Harian</option>
                <option value="weekly" {{ ($period ?? 'monthly') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                <option value="monthly" {{ ($period ?? 'monthly') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="yearly" {{ ($period ?? 'monthly') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
            </select>
            <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Semua Role</option>
                @foreach(($roles ?? []) as $r)
                <option value="{{ $r }}" {{ ($role ?? '') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ $date ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg">
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Terapkan</button>
            <a href="{{ route('reports.performance.export', request()->only(['period','date','role'])) }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">Export CSV</a>
        </div>
    </form>
</div>

<!-- Summary Cards -->
@php
    $totalEmployees = ($employeePerformance ?? collect())->count();
    $totalOrders = ($employeePerformance ?? collect())->sum('orders_count');
    $avgPerEmployee = $totalEmployees > 0 ? round($totalOrders / $totalEmployees, 2) : 0;
@endphp
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4"><i class="fas fa-user-tie"></i></div>
            <div>
                <div class="text-sm text-gray-600">Karyawan Aktif</div>
                <div class="text-2xl font-semibold text-gray-900">{{ number_format($totalEmployees, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4"><i class="fas fa-tasks"></i></div>
            <div>
                <div class="text-sm text-gray-600">Total Pesanan (Periode)</div>
                <div class="text-2xl font-semibold text-gray-900">{{ number_format($totalOrders, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4"><i class="fas fa-chart-line"></i></div>
            <div>
                <div class="text-sm text-gray-600">Rata-rata per Karyawan</div>
                <div class="text-2xl font-semibold text-gray-900">{{ number_format($avgPerEmployee, 2, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-md font-semibold text-gray-800 mb-4">Performa Karyawan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan Diproses</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($employeePerformance as $emp)
                    <tr>
                        <td class="px-6 py-3 text-sm text-gray-900">{{ $emp->name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-900">{{ $emp->position ?? '-' }}</td>
                        <td class="px-6 py-3 text-sm text-right text-gray-900">{{ number_format((int)$emp->orders_count, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-sm">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $emp->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $emp->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500">Belum ada data karyawan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-md font-semibold text-gray-800 mb-4">Distribusi Status Pesanan</h3>
        <ul class="space-y-2">
            @php $total = max(1, ($statusDistribution->sum('orders_count') ?? 0)); @endphp
            @forelse($statusDistribution as $st)
            @php $pct = round((($st->orders_count ?? 0) / $total) * 100, 1); @endphp
            <li class="flex items-center justify-between">
                <span class="text-sm text-gray-700">{{ ucfirst($st->name) }}</span>
                <span class="text-sm font-medium text-gray-900">{{ $pct }}% ({{ $st->orders_count ?? 0 }})</span>
            </li>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
            </div>
            @empty
            <li class="text-sm text-gray-500">Tidak ada data status.</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-md font-semibold text-gray-800 mb-4">Layanan Teratas</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($servicePerformance as $svc)
                <tr>
                    <td class="px-6 py-3 text-sm text-gray-900">{{ $svc->item_name }}</td>
                    <td class="px-6 py-3 text-sm text-right text-gray-900">{{ rtrim(rtrim(number_format((float)$svc->total_quantity, 2, ',', '.'), '0'), ',') }}</td>
                    <td class="px-6 py-3 text-sm text-right text-gray-900">{{ number_format((int)$svc->order_count, 0, ',', '.') }}</td>
                    <td class="px-6 py-3 text-sm text-right text-gray-900">Rp {{ number_format((float)$svc->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500">Belum ada data layanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


