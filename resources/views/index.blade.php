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
                <p class="text-2xl font-bold text-gray-900">1,234</p>
                <p class="text-sm text-green-600">+12% dari bulan lalu</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pelanggan Aktif</p>
                <p class="text-2xl font-bold text-gray-900">856</p>
                <p class="text-sm text-green-600">+8% dari bulan lalu</p>
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
                <p class="text-2xl font-bold text-gray-900">Rp 2.5M</p>
                <p class="text-sm text-green-600">+15% dari kemarin</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pesanan Pending</p>
                <p class="text-2xl font-bold text-gray-900">23</p>
                <p class="text-sm text-red-600">-5% dari kemarin</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Recent Orders -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Sales Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Grafik Penjualan</h3>
            <select class="text-sm border border-gray-300 rounded-md px-3 py-1">
                <option>7 Hari Terakhir</option>
                <option>30 Hari Terakhir</option>
                <option>3 Bulan Terakhir</option>
            </select>
        </div>
        <canvas id="salesChart" height="300"></canvas>
    </div>

    <!-- Order Status Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Status Pesanan</h3>
        </div>
        <canvas id="orderStatusChart" height="300"></canvas>
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
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#LAU-001</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Ahmad Rizki</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Cuci Reguler</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp 25.000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Dalam Proses</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2 jam yang lalu</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#LAU-002</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Siti Nurhaliza</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Cuci Express</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp 35.000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1 jam yang lalu</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#LAU-003</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Budi Santoso</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Cuci Setrika</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp 45.000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Siap Diambil</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">30 menit yang lalu</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="/monitoring" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
            <i class="fas fa-chart-line text-blue-600 text-2xl mb-2"></i>
            <span class="text-sm font-medium text-gray-700">Monitoring</span>
        </a>
        <a href="/orders" class="flex flex-col items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors">
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
    </div>
</div>
@endsection

@section('scripts')
<script>
// Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
            label: 'Pendapatan (Juta Rupiah)',
            data: [2.1, 2.3, 2.8, 2.5, 3.1, 3.5, 2.9],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value + 'M';
                    }
                }
            }
        }
    }
});

// Order Status Chart
const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
const orderStatusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Selesai', 'Dalam Proses', 'Siap Diambil', 'Pending'],
        datasets: [{
            data: [45, 25, 20, 10],
            backgroundColor: [
                'rgb(34, 197, 94)',
                'rgb(59, 130, 246)',
                'rgb(251, 191, 36)',
                'rgb(239, 68, 68)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endsection