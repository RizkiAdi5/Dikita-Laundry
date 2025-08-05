<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LaundryDikita - Sistem Monitoring Laundry')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        .content-transition {
            transition: margin-left 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg sidebar-transition transform -translate-x-full lg:translate-x-0">
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
            <div class="flex items-center">
                <i class="fas fa-tshirt text-blue-600 text-2xl mr-3"></i>
                <h1 class="text-xl font-bold text-gray-800">LaundryDikita</h1>
            </div>
            <button id="closeSidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <nav class="mt-6 px-4">
            <div class="space-y-2">
                <a href="/" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->is('/') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
                
                
                
                <a href="/orders" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->is('orders*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-shopping-cart w-5 h-5 mr-3"></i>
                    <span>Pesanan</span>
                </a>
                
                <a href="/customers" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->is('customers*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    <span>Pelanggan</span>
                </a>
                
                <a href="/services" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->is('services*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-concierge-bell w-5 h-5 mr-3"></i>
                    <span>Layanan</span>
                </a>
                
                <a href="/inventory" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->is('inventory*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-boxes w-5 h-5 mr-3"></i>
                    <span>Inventori</span>
                </a>
                
                <a href="/employees" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->is('employees*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-user-tie w-5 h-5 mr-3"></i>
                    <span>Karyawan</span>
                </a>
                
                <a href="/expenses" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->is('expenses*') ? 'bg-blue-50 text-blue-600' : '' }}">
                    <i class="fas fa-money-bill-wave w-5 h-5 mr-3"></i>
                    <span>Pengeluaran</span>
                </a>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="px-4">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Laporan</h3>
                </div>
                <div class="mt-2 space-y-2">
                    <a href="/reports" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors {{ request()->is('reports*') ? 'bg-blue-50 text-blue-600' : '' }}">
                        <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                        <span>Laporan Penjualan</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <i class="fas fa-chart-pie w-5 h-5 mr-3"></i>
                        <span>Laporan Stok</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <i class="fas fa-chart-area w-5 h-5 mr-3"></i>
                        <span>Laporan Performa</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    <!-- Main Content -->
    <div id="mainContent" class="lg:ml-64 content-transition">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between h-16 px-6">
                <div class="flex items-center">
                    <button id="openSidebar" class="lg:hidden text-gray-500 hover:text-gray-700 mr-4">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">A</span>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-medium text-gray-700">Admin</p>
                            <p class="text-xs text-gray-500">admin@laundrydikita.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div id="flash-success" class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="flash-error" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('warning'))
                <div id="flash-warning" class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-yellow-500 hover:text-yellow-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('info'))
                <div id="flash-info" class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div id="flash-validation" class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-semibold">Terjadi kesalahan validasi:</span>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        const mainContent = document.getElementById('mainContent');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        openSidebarBtn.addEventListener('click', openSidebar);
        closeSidebarBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Close sidebar on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });

        // Flash message auto-hide functionality
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessages = document.querySelectorAll('[id^="flash-"]');
            
            flashMessages.forEach(function(message) {
                // Auto-hide after 5 seconds
                setTimeout(function() {
                    if (message && message.parentNode) {
                        message.style.transition = 'opacity 0.5s ease-out';
                        message.style.opacity = '0';
                        setTimeout(function() {
                            if (message && message.parentNode) {
                                message.remove();
                            }
                        }, 500);
                    }
                }, 5000);

                // Add click to close functionality
                const closeBtn = message.querySelector('button');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        message.style.transition = 'opacity 0.5s ease-out';
                        message.style.opacity = '0';
                        setTimeout(function() {
                            if (message && message.parentNode) {
                                message.remove();
                            }
                        }, 500);
                    });
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html> 