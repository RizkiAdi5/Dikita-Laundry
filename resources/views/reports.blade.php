@extends('layouts.app')

@section('title', 'Laporan - LaundryDikita')

@section('page-title', 'Laporan')

@section('content')
<!-- Report Filters -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Filter Laporan</h3>
            <p class="text-sm text-gray-500">Pilih periode dan jenis laporan yang ingin dilihat</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly" selected>Bulanan</option>
                <option value="yearly">Tahunan</option>
            </select>
            <input type="date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-download mr-2"></i>
                Export PDF
            </button>
        </div>
    </div>
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
                <p class="text-2xl font-bold text-gray-900">Rp 45.2M</p>
                <p class="text-sm text-green-600">+18% dari bulan lalu</p>
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
                <p class="text-2xl font-bold text-gray-900">1,847</p>
                <p class="text-sm text-green-600">+12% dari bulan lalu</p>
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
                <p class="text-2xl font-bold text-gray-900">156</p>
                <p class="text-sm text-green-600">+8% dari bulan lalu</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-star text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rata-rata Rating</p>
                <p class="text-2xl font-bold text-gray-900">4.8</p>
                <p class="text-sm text-green-600">+0.2 dari bulan lalu</p>
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
            <select class="text-sm border border-gray-300 rounded-md px-3 py-1">
                <option>Bulan Ini</option>
                <option>3 Bulan Terakhir</option>
                <option>6 Bulan Terakhir</option>
            </select>
        </div>
        <canvas id="revenueChart" height="300"></canvas>
    </div>

    <!-- Service Performance -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Performa Layanan</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                    <span class="text-sm text-gray-700">Cuci Reguler</span>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">45%</p>
                    <p class="text-xs text-gray-500">832 pesanan</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm text-gray-700">Cuci Express</span>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">28%</p>
                    <p class="text-xs text-gray-500">517 pesanan</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full" style="width: 28%"></div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                    <span class="text-sm text-gray-700">Cuci Setrika</span>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">18%</p>
                    <p class="text-xs text-gray-500">332 pesanan</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-yellow-600 h-2 rounded-full" style="width: 18%"></div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                    <span class="text-sm text-gray-700">Dry Clean</span>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">9%</p>
                    <p class="text-xs text-gray-500">166 pesanan</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-purple-600 h-2 rounded-full" style="width: 9%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Top Customers and Inventory Reports -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Top Customers -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pelanggan Teratas</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-blue-600 font-semibold text-sm">AR</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Ahmad Rizki</p>
                        <p class="text-sm text-gray-500">Gold Member</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Rp 2.5M</p>
                    <p class="text-xs text-gray-500">45 pesanan</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-green-600 font-semibold text-sm">SN</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Siti Nurhaliza</p>
                        <p class="text-sm text-gray-500">Platinum Member</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Rp 2.1M</p>
                    <p class="text-xs text-gray-500">38 pesanan</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-purple-600 font-semibold text-sm">BS</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Budi Santoso</p>
                        <p class="text-sm text-gray-500">Silver Member</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Rp 1.8M</p>
                    <p class="text-xs text-gray-500">32 pesanan</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-yellow-600 font-semibold text-sm">DW</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Dewi Wati</p>
                        <p class="text-sm text-gray-500">Regular Member</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Rp 1.5M</p>
                    <p class="text-xs text-gray-500">28 pesanan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Inventori</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700">Detergent</span>
                <div class="flex items-center space-x-2">
                    <div class="w-20 bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">85%</span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700">Fabric Softener</span>
                <div class="flex items-center space-x-2">
                    <div class="w-20 bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">45%</span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700">Plastic Bag</span>
                <div class="flex items-center space-x-2">
                    <div class="w-20 bg-gray-200 rounded-full h-2">
                        <div class="bg-red-600 h-2 rounded-full" style="width: 15%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">15%</span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700">Hanger</span>
                <div class="flex items-center space-x-2">
                    <div class="w-20 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 92%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">92%</span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-700">Bleach</span>
                <div class="flex items-center space-x-2">
                    <div class="w-20 bg-gray-200 rounded-full h-2">
                        <div class="bg-red-600 h-2 rounded-full" style="width: 0%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-900">0%</span>
                </div>
            </div>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-semibold text-sm">AR</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Ahmad Rizki</div>
                                <div class="text-sm text-gray-500">Manager</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Manager</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">156</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2.1 jam</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-900">4.9</span>
                            <div class="flex ml-1">
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Excellent</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-green-600 font-semibold text-sm">SN</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Siti Nurhaliza</div>
                                <div class="text-sm text-gray-500">Cashier</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Cashier</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">142</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2.3 jam</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-900">4.7</span>
                            <div class="flex ml-1">
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="far fa-star text-yellow-400 text-xs"></i>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Good</span>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-purple-600 font-semibold text-sm">BS</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Budi Santoso</div>
                                <div class="text-sm text-gray-500">Operator</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Operator</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">128</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2.5 jam</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-900">4.5</span>
                            <div class="flex ml-1">
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                <i class="far fa-star text-yellow-400 text-xs"></i>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Average</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [{
            label: 'Pendapatan (Juta Rupiah)',
            data: [35, 42, 38, 45, 52, 48, 55, 58, 62, 59, 65, 45],
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
</script>
@endsection 