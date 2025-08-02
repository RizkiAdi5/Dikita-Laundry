@extends('layouts.app')

@section('title', 'Layanan - LaundryDikita')

@section('page-title', 'Layanan')

@section('content')
<!-- Header Actions -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h3 class="text-lg font-medium text-gray-900">Daftar Layanan</h3>
        <p class="text-sm text-gray-500">Kelola semua layanan laundry yang tersedia</p>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Tambah Layanan
    </button>
</div>

<!-- Services Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Cuci Reguler -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-tshirt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Cuci Reguler</h3>
                        <p class="text-sm text-gray-500">Layanan standar</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Harga:</span>
                    <span class="text-lg font-semibold text-gray-900">Rp 8.000/kg</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Estimasi:</span>
                    <span class="text-sm text-gray-900">2-3 hari</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cuci Express -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-bolt text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Cuci Express</h3>
                        <p class="text-sm text-gray-500">Layanan cepat</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Harga:</span>
                    <span class="text-lg font-semibold text-gray-900">Rp 15.000/kg</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Estimasi:</span>
                    <span class="text-sm text-gray-900">4-6 jam</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cuci Setrika -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-iron text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Cuci Setrika</h3>
                        <p class="text-sm text-gray-500">Cuci + setrika</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Harga:</span>
                    <span class="text-lg font-semibold text-gray-900">Rp 12.000/kg</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Estimasi:</span>
                    <span class="text-sm text-gray-900">1-2 hari</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Setrika Saja -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-iron text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Setrika Saja</h3>
                        <p class="text-sm text-gray-500">Setrika saja</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Harga:</span>
                    <span class="text-lg font-semibold text-gray-900">Rp 5.000/kg</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Estimasi:</span>
                    <span class="text-sm text-gray-900">2-4 jam</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dry Clean -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-spray-can text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Dry Clean</h3>
                        <p class="text-sm text-gray-500">Cuci kering</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Harga:</span>
                    <span class="text-lg font-semibold text-gray-900">Rp 25.000/piece</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Estimasi:</span>
                    <span class="text-sm text-gray-900">3-5 hari</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cuci Selimut -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-bed text-indigo-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Cuci Selimut</h3>
                        <p class="text-sm text-gray-500">Cuci selimut</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Harga:</span>
                    <span class="text-lg font-semibold text-gray-900">Rp 35.000/piece</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Estimasi:</span>
                    <span class="text-sm text-gray-900">2-3 hari</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cuci Karpet -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-couch text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Cuci Karpet</h3>
                        <p class="text-sm text-gray-500">Cuci karpet</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-900">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Harga:</span>
                    <span class="text-lg font-semibold text-gray-900">Rp 50.000/m²</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Estimasi:</span>
                    <span class="text-sm text-gray-900">3-4 hari</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 