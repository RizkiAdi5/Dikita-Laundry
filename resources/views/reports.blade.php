@extends('layouts.app')

@section('title', 'Laporan - LaundryDikita')

@section('page-title', 'Laporan')

@section('content')
<!-- Report Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Filter Laporan</h3>
            <p class="text-sm text-gray-500">Pilih periode dan tanggal yang ingin dilihat</p>
            <p class="text-xs text-gray-400 mt-1">
                Periode: {{ \Carbon\Carbon::parse($startDate ?? now())->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate ?? now())->format('d M Y') }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
            <select name="period" id="period" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="daily" {{ ($period ?? 'monthly') == 'daily' ? 'selected' : '' }}>Harian</option>
                <option value="weekly" {{ ($period ?? 'monthly') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                <option value="monthly" {{ ($period ?? 'monthly') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="yearly" {{ ($period ?? 'monthly') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
            </select>
            <input type="date" name="date" id="date" value="{{ $date ?? now()->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Report Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-chart-bar text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format((float)($totalRevenue ?? 0), 0, ',', '.') }}</p>
                <p class="text-sm {{ ($revenueChange ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ ($revenueChange ?? 0) >= 0 ? '+' : '' }}{{ number_format((float)($revenueChange ?? 0), 1) }}% dari periode sebelumnya
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format((int)($totalOrders ?? 0), 0, ',', '.') }}</p>
                <p class="text-sm {{ ($ordersChange ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ ($ordersChange ?? 0) >= 0 ? '+' : '' }}{{ number_format((float)($ordersChange ?? 0), 1) }}% dari periode sebelumnya
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pelanggan Baru</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format((int)($newCustomers ?? 0), 0, ',', '.') }}</p>
                <p class="text-sm {{ ($customersChange ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ ($customersChange ?? 0) >= 0 ? '+' : '' }}{{ number_format((float)($customersChange ?? 0), 1) }}% dari periode sebelumnya
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-percentage text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rata-rata Pesanan</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ ($totalOrders ?? 0) > 0 ? number_format((float)($totalRevenue ?? 0) / ($totalOrders ?? 1), 0, ',', '.') : '0' }}
                </p>
                <p class="text-sm text-gray-500">Per pesanan</p>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Reports -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Revenue Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Grafik Pendapatan</h3>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Service Performance -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Performa Layanan</h3>
        <div class="space-y-4">
            @forelse(($servicePerformance ?? []) as $index => $service)
            @php
                $colors = ['blue', 'green', 'yellow', 'purple', 'indigo', 'pink', 'red', 'orange'];
                $color = $colors[$index % count($colors)];
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-{{ $color }}-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-700 font-medium">{{ $service->item_name }}</span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ number_format((float)$service->percentage, 1) }}%</p>
                        <p class="text-xs text-gray-500">{{ number_format((int)$service->order_count, 0, ',', '.') }} pesanan</p>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-{{ $color }}-600 h-2 rounded-full" style="width: {{ min(100, $service->percentage) }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Total: Rp {{ number_format((float)$service->total_revenue, 0, ',', '.') }} | Qty: {{ number_format((float)$service->total_quantity, 2, ',', '.') }}
                </p>
            </div>
            @empty
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-chart-bar text-4xl mb-2"></i>
                <p>Belum ada data layanan</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Top Customers and Inventory Reports -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Top Customers -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pelanggan Teratas</h3>
        <div class="space-y-4">
            @forelse(($topCustomers ?? []) as $customer)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <div class="w-10 h-10 {{ $customer->avatar_color ?? 'bg-blue-100 text-blue-600' }} rounded-full flex items-center justify-center mr-3 font-semibold text-sm">
                        {{ $customer->initials ?? substr($customer->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $customer->name }}</p>
                        <p class="text-sm text-gray-500">{{ $customer->membership_type ?? 'Regular' }} Member</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Rp {{ number_format((float)$customer->total_revenue, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">{{ number_format((int)$customer->order_count, 0, ',', '.') }} pesanan</p>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-users text-4xl mb-2"></i>
                <p>Belum ada data pelanggan</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Inventory Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Inventori</h3>
        <div class="space-y-4">
            @forelse(($inventoryItems ?? [])->take(8) as $item)
            @php
                $statusColor = 'green';
                if ($item->quantity <= 0) $statusColor = 'red';
                elseif ($item->quantity < $item->min_quantity) $statusColor = 'yellow';
            @endphp
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700 font-medium">{{ $item->name }}</span>
                <div class="flex items-center space-x-2">
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        <div class="bg-{{ $statusColor }}-600 h-2 rounded-full" style="width: {{ min(100, $item->stock_percentage ?? 0) }}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-900 w-12 text-right">{{ number_format((float)$item->stock_percentage ?? 0, 0) }}%</span>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-box-open text-4xl mb-2"></i>
                <p>Belum ada data inventori</p>
            </div>
            @endforelse
            @if(($inventoryItems ?? [])->count() > 8)
            <div class="text-center pt-2">
                <a href="{{ route('inventory.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Lihat semua inventori →
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Employee Performance -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Performa Karyawan</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karyawan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesanan Diproses</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse(($employeePerformance ?? []) as $employee)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-semibold text-xs">{{ substr($employee->name, 0, 2) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                                <div class="text-sm text-gray-500">{{ $employee->phone ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $employee->position ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format((int)$employee->processed_orders, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $employee->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-users text-4xl mb-2 block"></i>
                        <p>Belum ada data karyawan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
const revenueChartData = @json($revenueChartData ?? []);

if (document.getElementById('revenueChart')) {
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueLabels = revenueChartData.map(d => d.label);
    const revenueAmounts = revenueChartData.map(d => Math.round((d.amount || 0)));

    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenueAmounts,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#6b7280',
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#6b7280',
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });

    // Responsive chart resizing
    window.addEventListener('resize', function() {
        revenueChart.resize();
    });
}
</script>
@endsection
