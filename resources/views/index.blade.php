@extends('layouts.app')

@section('title', 'Dashboard - LaundryDikita')

@section('page-title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format((int)($totalOrders ?? 0), 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500">Semua waktu</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pelanggan</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format((int)($activeCustomers ?? 0), 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500">Terdaftar</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-money-bill-wave text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pendapatan Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format((float)($revenueToday ?? 0), 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500">Pembayaran tercatat</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pesanan Belum Selesai</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format((int)($pendingOrders ?? 0), 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500">Tidak termasuk selesai</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Recent Orders -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
    <!-- Sales Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Grafik Pendapatan</h3>
            <select class="text-sm border border-gray-300 rounded-md px-3 py-1">
                <option>7 Hari Terakhir</option>
                <option>30 Hari Terakhir</option>
                <option>3 Bulan Terakhir</option>
            </select>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Order Status Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Status Pesanan</h3>
            <div class="text-sm text-gray-500">Perbandingan jumlah pesanan per status</div>
        </div>
        <div class="relative" style="height: 300px;">
            <canvas id="orderStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Pesanan Terbaru</h3>
        <a href="/orders" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse(($recentOrders ?? []) as $ro)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $ro->order_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ro->customer->name ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ro->items_count ? $ro->items_count.' item' : '' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format((float)$ro->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $ro->status->name ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ro->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500">Belum ada pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="/orders/create" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
            <i class="fas fa-plus-circle text-green-600 text-2xl mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Pesanan Baru</span>
        </a>
        <a href="/customers" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
            <i class="fas fa-user-plus text-yellow-600 text-2xl mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Tambah Pelanggan</span>
        </a>
        <a href="/inventory" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
            <i class="fas fa-box-open text-purple-600 text-2xl mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Cek Stok</span>
        </a>
        <a href="/reports" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
            <i class="fas fa-chart-line text-blue-600 text-2xl mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Laporan</span>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
const salesSeries = @json($salesSeries ?? []);
const statusCounts = @json(($statusCounts ?? []));
// Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesLabels = salesSeries.map(s => s.date);
const salesData = salesSeries.map(s => Math.round((s.amount || 0)));
const salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: salesLabels,
        datasets: [{
            label: 'Pendapatan Harian (Rp)',
            data: salesData,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            borderWidth: 3,
            pointBackgroundColor: 'rgb(59, 130, 246)',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
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
                displayColors: false
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    font: {
                        size: 12
                    },
                    color: '#6b7280'
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                },
                ticks: {
                    font: {
                        size: 12
                    },
                    color: '#6b7280',
                    callback: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    }
});

// Order Status Chart
const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
const statusLabels = (statusCounts || []).map(s => s.name);
const statusData = (statusCounts || []).map(s => s.orders_count || 0);
const orderStatusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusData,
            backgroundColor: [
                'rgb(34, 197, 94)',
                'rgb(59, 130, 246)',
                'rgb(251, 191, 36)',
                'rgb(239, 68, 68)'
            ],
            borderWidth: 0,
            hoverBorderWidth: 2,
            hoverBorderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    font: {
                        size: 12
                    },
                    color: '#374151'
                }
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
                        const label = context.label || '';
                        const value = context.parsed;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        },
        cutout: '60%'
    }
});

// Responsive chart resizing
window.addEventListener('resize', function() {
    salesChart.resize();
    orderStatusChart.resize();
});
</script>
@endsection