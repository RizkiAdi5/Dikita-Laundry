@extends('layouts.app')

@section('title', 'Monitoring - LaundryDikita')

@section('page-title', 'Monitoring Real-time')

@section('content')
<!-- Real-time Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pesanan Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">23</p>
                <p class="text-sm text-green-600">+5 dari kemarin</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Selesai Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">18</p>
                <p class="text-sm text-green-600">78% completion rate</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pending</p>
                <p class="text-2xl font-bold text-gray-900">5</p>
                <p class="text-sm text-yellow-600">Perlu perhatian</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-tachometer-alt text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rata-rata Waktu</p>
                <p class="text-2xl font-bold text-gray-900">2.3</p>
                <p class="text-sm text-red-600">jam per pesanan</p>
            </div>
        </div>
    </div>
</div>

<!-- Live Monitoring Dashboard -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Current Orders Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Status Pesanan Saat Ini</h3>
            <span class="text-sm text-gray-500">Update: <span id="lastUpdate">2 menit yang lalu</span></span>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3 animate-pulse"></div>
                    <div>
                        <p class="font-medium text-gray-900">#LAU-001</p>
                        <p class="text-sm text-gray-600">Cuci Reguler - 3kg</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Dalam Proses</p>
                    <p class="text-xs text-gray-500">1 jam 23 menit</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3 animate-pulse"></div>
                    <div>
                        <p class="font-medium text-gray-900">#LAU-002</p>
                        <p class="text-sm text-gray-600">Cuci Express - 2kg</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Menunggu</p>
                    <p class="text-xs text-gray-500">45 menit</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <div>
                        <p class="font-medium text-gray-900">#LAU-003</p>
                        <p class="text-sm text-gray-600">Cuci Setrika - 4kg</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Siap Diambil</p>
                    <p class="text-xs text-gray-500">Baru selesai</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-3 animate-pulse"></div>
                    <div>
                        <p class="font-medium text-gray-900">#LAU-004</p>
                        <p class="text-sm text-gray-600">Dry Clean - 1 piece</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">Setrika</p>
                    <p class="text-xs text-gray-500">30 menit</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Machine Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Status Mesin</h3>
            <span class="text-sm text-gray-500">Real-time</span>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">Mesin 1</h4>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                </div>
                <p class="text-sm text-gray-600">Cuci Reguler</p>
                <p class="text-xs text-gray-500 mt-1">Sisa: 15 menit</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 75%"></div>
                </div>
            </div>

            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">Mesin 2</h4>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Sibuk</span>
                </div>
                <p class="text-sm text-gray-600">Cuci Express</p>
                <p class="text-xs text-gray-500 mt-1">Sisa: 8 menit</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 90%"></div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">Mesin 3</h4>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Idle</span>
                </div>
                <p class="text-sm text-gray-600">Tidak ada pesanan</p>
                <p class="text-xs text-gray-500 mt-1">Siap digunakan</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-gray-400 h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>

            <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">Mesin 4</h4>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Maintenance</span>
                </div>
                <p class="text-sm text-gray-600">Sedang diperbaiki</p>
                <p class="text-xs text-gray-500 mt-1">Estimasi: 2 jam</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-yellow-600 h-2 rounded-full" style="width: 30%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Metrics -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Daily Performance Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Performa Hari Ini</h3>
        <canvas id="dailyPerformanceChart" height="200"></canvas>
    </div>

    <!-- Order Status Distribution -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Status</h3>
        <canvas id="orderStatusChart" height="200"></canvas>
    </div>

    <!-- Efficiency Metrics -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Metrik Efisiensi</h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Utilisasi Mesin</span>
                <span class="text-sm font-medium text-gray-900">85%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Rata-rata Waktu Proses</span>
                <span class="text-sm font-medium text-gray-900">2.3 jam</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-green-600 h-2 rounded-full" style="width: 70%"></div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Kepuasan Pelanggan</span>
                <span class="text-sm font-medium text-gray-900">4.8/5</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-yellow-600 h-2 rounded-full" style="width: 96%"></div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">On-time Delivery</span>
                <span class="text-sm font-medium text-gray-900">92%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-purple-600 h-2 rounded-full" style="width: 92%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Alerts and Notifications -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Peringatan & Notifikasi</h3>
        <button class="text-blue-600 hover:text-blue-700 text-sm">Lihat Semua</button>
    </div>
    
    <div class="space-y-3">
        <div class="flex items-center p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
            <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Stok Detergent Menipis</p>
                <p class="text-xs text-gray-600">Sisa stok: 5 kg (minimal: 10 kg)</p>
            </div>
            <span class="text-xs text-gray-500">5 menit yang lalu</span>
        </div>

        <div class="flex items-center p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
            <i class="fas fa-clock text-yellow-500 mr-3"></i>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Pesanan #LAU-005 Terlambat</p>
                <p class="text-xs text-gray-600">Estimasi: 2 jam, Real: 3.5 jam</p>
            </div>
            <span class="text-xs text-gray-500">15 menit yang lalu</span>
        </div>

        <div class="flex items-center p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Mesin 4 Selesai Maintenance</p>
                <p class="text-xs text-gray-600">Siap digunakan kembali</p>
            </div>
            <span class="text-xs text-gray-500">1 jam yang lalu</span>
        </div>

        <div class="flex items-center p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Target Harian Tercapai</p>
                <p class="text-xs text-gray-600">25/25 pesanan selesai tepat waktu</p>
            </div>
            <span class="text-xs text-gray-500">2 jam yang lalu</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Update timestamp
function updateTimestamp() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    document.getElementById('lastUpdate').textContent = timeString;
}

// Update every 30 seconds
setInterval(updateTimestamp, 30000);
updateTimestamp();

// Daily Performance Chart
const dailyCtx = document.getElementById('dailyPerformanceChart').getContext('2d');
const dailyPerformanceChart = new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
        datasets: [{
            label: 'Pesanan Masuk',
            data: [2, 5, 8, 12, 15, 18, 20, 23],
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Pesanan Selesai',
            data: [0, 2, 5, 8, 12, 15, 17, 18],
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Order Status Distribution Chart
const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
const orderStatusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Selesai', 'Dalam Proses', 'Menunggu', 'Siap Diambil'],
        datasets: [{
            data: [45, 25, 20, 10],
            backgroundColor: [
                'rgb(34, 197, 94)',
                'rgb(59, 130, 246)',
                'rgb(251, 191, 36)',
                'rgb(168, 85, 247)'
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

// Simulate real-time updates
function simulateUpdates() {
    // Update progress bars randomly
    const progressBars = document.querySelectorAll('.bg-blue-600, .bg-green-600, .bg-yellow-600, .bg-purple-600');
    progressBars.forEach(bar => {
        const currentWidth = parseInt(bar.style.width);
        const newWidth = Math.max(0, Math.min(100, currentWidth + (Math.random() - 0.5) * 10));
        bar.style.width = newWidth + '%';
    });
}

// Update every 10 seconds
setInterval(simulateUpdates, 10000);
</script>
@endsection 