@extends('layouts.app')

@section('title', 'Pelanggan - LaundryDikita')

@section('page-title', 'Pelanggan')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pelanggan</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                <p class="text-sm text-green-600">+12% dari bulan lalu</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-user-check text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pelanggan Aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active']) }}</p>
                <p class="text-sm text-green-600">+8% dari bulan lalu</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-crown text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Member VIP</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['vip']) }}</p>
                <p class="text-sm text-green-600">+5% dari bulan lalu</p>
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
                <p class="text-2xl font-bold text-gray-900">{{ $stats['avg_rating'] }}</p>
                <p class="text-sm text-green-600">+0.2 dari bulan lalu</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Actions -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col sm:flex-row gap-4">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <select name="membership_type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Semua Tipe</option>
            <option value="regular" {{ request('membership_type') == 'regular' ? 'selected' : '' }}>Regular</option>
            <option value="silver" {{ request('membership_type') == 'silver' ? 'selected' : '' }}>Silver</option>
            <option value="gold" {{ request('membership_type') == 'gold' ? 'selected' : '' }}>Gold</option>
            <option value="platinum" {{ request('membership_type') == 'platinum' ? 'selected' : '' }}>Platinum</option>
        </select>
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-filter mr-2"></i>
            Filter
        </button>
    </form>
    <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Tambah Pelanggan
    </button>
</div>

<!-- Customers Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe Member</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 {{ $customer->avatar_color }} rounded-full flex items-center justify-center mr-4">
                                <span class="font-semibold text-sm">{{ $customer->initials }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $customer->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}
                                    @if($customer->age)
                                        • {{ $customer->age }} tahun
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $customer->phone }}</div>
                        @if($customer->email)
                            <div class="text-sm text-gray-500">{{ $customer->email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
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
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $membershipColors[$customer->membership_type] }}">
                            {{ $membershipLabels[$customer->membership_type] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($customer->points) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($customer->is_active)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $customer->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="viewCustomer({{ $customer->id }})" class="text-blue-600 hover:text-blue-900 p-1 rounded" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editCustomer({{ $customer->id }})" class="text-green-600 hover:text-green-900 p-1 rounded" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDeleteCustomer({{ $customer->id }}, '{{ addslashes($customer->name) }}')" class="text-red-600 hover:text-red-900 p-1 rounded" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex flex-col items-center py-8">
                            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium text-gray-600">Tidak ada pelanggan ditemukan</p>
                            <p class="text-sm text-gray-500">Coba ubah filter pencarian Anda</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($customers->hasPages())
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            @if($customers->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $customers->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Sebelumnya
                </a>
            @endif

            @if($customers->hasMorePages())
                <a href="{{ $customers->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Selanjutnya
                </a>
            @else
                <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                    Selanjutnya
                </span>
            @endif
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $customers->firstItem() }}</span> sampai <span class="font-medium">{{ $customers->lastItem() }}</span> dari <span class="font-medium">{{ $customers->total() }}</span> hasil
                </p>
            </div>
            <div class="flex items-center space-x-1">
                @if ($customers->onFirstPage())
                    <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $customers->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach ($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                    @if ($page == $customers->currentPage())
                        <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-md">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if ($customers->hasMorePages())
                    <a href="{{ $customers->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                @else
                    <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Create Customer Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[9997]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all duration-300 ease-in-out">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Pelanggan Baru</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createForm" class="p-6">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="text-red-500 text-xs mt-1 hidden" id="name-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon *</label>
                        <input type="tel" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="text-red-500 text-xs mt-1 hidden" id="phone-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="text-red-500 text-xs mt-1 hidden" id="email-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Membership *</label>
                        <select name="membership_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="regular">Regular</option>
                            <option value="silver">Silver</option>
                            <option value="gold">Gold</option>
                            <option value="platinum">Platinum</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <span id="submitText">Simpan</span>
                        <span id="submitLoading" class="hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[9997]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all duration-300 ease-in-out">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900" id="confirmTitle">Konfirmasi</h3>
                    </div>
                </div>
                <div class="mb-6">
                    <p class="text-sm text-gray-600" id="confirmMessage">Apakah Anda yakin ingin melakukan aksi ini?</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeConfirmModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button id="confirmActionBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Toast -->
<div id="toast" class="fixed top-4 right-4 z-[9999] hidden">
    <div class="bg-white rounded-lg shadow-xl border-l-4 p-4 max-w-sm transform transition-all duration-300 ease-in-out">
        <div class="flex items-center">
            <div id="toastIcon" class="flex-shrink-0 mr-3"></div>
            <div class="flex-1">
                <p id="toastMessage" class="text-sm font-medium text-gray-900"></p>
            </div>
            <button onclick="hideToast()" class="ml-4 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[9998]">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3 shadow-xl">
            <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
            <span class="text-gray-700">Memproses...</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Global variables for confirmation
let currentAction = null;
let currentCustomerId = null;
let currentCustomerName = null;

// Toast notification functions
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    toastMessage.textContent = message;
    
    if (type === 'success') {
        toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-green-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-full';
        toastIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
    } else {
        toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-red-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-full';
        toastIcon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500"></i>';
    }
    
    toast.classList.remove('hidden');
    
    // Slide in animation
    setTimeout(() => {
        if (type === 'success') {
            toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-green-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-0';
        } else {
            toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-red-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-0';
        }
    }, 10);
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        hideToast();
    }, 5000);
}

function hideToast() {
    const toast = document.getElementById('toast');
    toast.className = toast.className.replace('translate-x-0', 'translate-x-full');
    
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 300);
}

// Loading overlay functions
function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

// Modal functions
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('createForm').reset();
    clearErrors();
}

function clearErrors() {
    const errorElements = document.querySelectorAll('[id$="-error"]');
    errorElements.forEach(element => {
        element.classList.add('hidden');
        element.textContent = '';
    });
}

// Confirmation modal functions
function showConfirmModal(title, message, action, customerId = null, customerName = null) {
    currentAction = action;
    currentCustomerId = customerId;
    currentCustomerName = customerName;
    
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentAction = null;
    currentCustomerId = null;
    currentCustomerName = null;
}

// Customer actions with confirmation
function confirmDeleteCustomer(id, name) {
    showConfirmModal(
        'Hapus Pelanggan',
        `Apakah Anda yakin ingin menghapus pelanggan "${name}"? Aksi ini tidak dapat dibatalkan.`,
        'delete',
        id,
        name
    );
}

function viewCustomer(id) {
    showLoading();
    window.location.href = `{{ url('/customers') }}/${id}`;
}

function editCustomer(id) {
    showLoading();
    window.location.href = `{{ url('/customers') }}/${id}/edit`;
}

// Handle confirmation actions
document.getElementById('confirmActionBtn').addEventListener('click', function() {
    if (currentAction === 'delete') {
        deleteCustomer(currentCustomerId, currentCustomerName);
    }
    closeConfirmModal();
});

// Form submission with confirmation
document.getElementById('createForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoading = document.getElementById('submitLoading');
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.classList.add('hidden');
    submitLoading.classList.remove('hidden');
    
    // Clear previous errors
    clearErrors();
    
    const formData = new FormData(this);
    
    fetch('{{ route("customers.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('✅ ' + data.message, 'success');
            closeCreateModal();
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            // Show validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const errorElement = document.getElementById(field + '-error');
                    if (errorElement) {
                        errorElement.textContent = data.errors[field][0];
                        errorElement.classList.remove('hidden');
                    }
                });
                showToast('❌ Terdapat kesalahan pada form', 'error');
            } else {
                showToast('❌ ' + (data.message || 'Terjadi kesalahan'), 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌ Terjadi kesalahan saat menyimpan data', 'error');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        submitLoading.classList.add('hidden');
    });
});

// Delete customer function
function deleteCustomer(id, name) {
    showLoading();
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/customers/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast('✅ ' + data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast('❌ ' + (data.message || 'Gagal menghapus pelanggan'), 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('❌ Terjadi kesalahan saat menghapus data', 'error');
    });
}

// Close modals when clicking outside
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateModal();
    }
});

document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCreateModal();
        closeConfirmModal();
    }
});

// Initialize tooltips and hover effects
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects for action buttons
    const actionButtons = document.querySelectorAll('[onclick*="viewCustomer"], [onclick*="editCustomer"], [onclick*="confirmDeleteCustomer"]');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Show welcome message if no customers
    @if($customers->count() == 0)
        showToast('👋 Selamat datang! Silakan tambahkan pelanggan pertama Anda.', 'success');
    @endif
});
</script>
@endsection 