@extends('layouts.app')

@section('title', 'Detail Pelanggan - LaundryDikita')

@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Customer Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 {{ $customer->avatar_color }} rounded-full flex items-center justify-center mr-4">
                    <span class="font-semibold text-xl">{{ $customer->initials }}</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h2>
                    <p class="text-gray-600">{{ $customer->phone }}</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('customers.edit', $customer->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Customer Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pribadi</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Lengkap:</span>
                        <span class="font-medium">{{ $customer->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nomor Telepon:</span>
                        <span class="font-medium">{{ $customer->phone }}</span>
                    </div>
                    @if($customer->email)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium">{{ $customer->email }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis Kelamin:</span>
                        <span class="font-medium">{{ $customer->gender == 'male' ? 'Laki-laki' : ($customer->gender == 'female' ? 'Perempuan' : '-') }}</span>
                    </div>
                    @if($customer->birth_date)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Lahir:</span>
                        <span class="font-medium">{{ $customer->birth_date->format('d M Y') }} @if($customer->age)({{ $customer->age }} tahun)@endif</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Membership Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Membership</h3>
                <div class="space-y-3">
                    @php
                        $membershipColors = [
                            'regular' => 'bg-gray-100 text-gray-800',
                            'silver' => 'bg-blue-100 text-blue-800',
                            'gold' => 'bg-yellow-100 text-yellow-800',
                            'platinum' => 'bg-purple-100 text-purple-800'
                        ];
                        $membershipLabels = [
                            'regular' => 'Regular',
                            'silver' => 'Silver',
                            'gold' => 'Gold',
                            'platinum' => 'Platinum'
                        ];
                    @endphp
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Tipe Membership:</span>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $membershipColors[$customer->membership_type] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $membershipLabels[$customer->membership_type] ?? 'Unknown' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Poin:</span>
                        <span class="font-medium">{{ number_format($customer->points ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        @if($customer->is_active)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                        @endif
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Terdaftar Sejak:</span>
                        <span class="font-medium">{{ $customer->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address -->
        @if($customer->address)
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Alamat</h3>
            <p class="text-gray-700">{{ $customer->address }}</p>
        </div>
        @endif

        <!-- Notes -->
        @if($customer->notes)
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Catatan</h3>
            <p class="text-gray-700">{{ $customer->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $customer->total_transactions ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($customer->total_revenue ?? 0) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Transaksi Terakhir</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @if($customer->last_transaction_date)
                            {{ $customer->last_transaction_date->format('d M') }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
        </div>
        <div class="p-6">
            @if($customer->orders->count() === 0)
                <div class="text-center text-gray-500">
                    <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                    <p class="text-lg font-medium text-gray-600">Belum ada transaksi</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($customer->orders as $order)
                            <tr>
                                <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-3 text-sm text-gray-700">{{ $order->items->first()->item_name ?? '-' }}</td>
                                <td class="px-6 py-3 text-sm font-semibold text-gray-900">{{ $order->formatted_total }}</td>
                                <td class="px-6 py-3 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $order->status->name }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 