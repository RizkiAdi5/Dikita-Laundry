@extends('layouts.app')

@section('title', 'Pengeluaran - LaundryDikita')

@section('page-title', 'Pengeluaran')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-money-bill-wave text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_this_month']) }}</p>
                <p class="text-sm {{ $stats['growth_percentage'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                    {{ $stats['growth_percentage'] > 0 ? '+' : '' }}{{ number_format($stats['growth_percentage'], 1) }}% dari bulan lalu
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Menunggu Persetujuan</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['pending_amount']) }}</p>
                <p class="text-sm text-yellow-600">Perlu review</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Terlambat Bayar</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['overdue_amount']) }}</p>
                <p class="text-sm text-orange-600">Perlu perhatian</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Bulan Lalu</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_last_month']) }}</p>
                <p class="text-sm text-green-600">Sebagai perbandingan</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Actions -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-col sm:flex-row gap-4">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengeluaran..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Semua Kategori</option>
            <option value="operational" {{ request('category') == 'operational' ? 'selected' : '' }}>Operasional</option>
            <option value="utilities" {{ request('category') == 'utilities' ? 'selected' : '' }}>Utilitas</option>
            <option value="salary" {{ request('category') == 'salary' ? 'selected' : '' }}>Gaji</option>
            <option value="inventory" {{ request('category') == 'inventory' ? 'selected' : '' }}>Inventaris</option>
            <option value="equipment" {{ request('category') == 'equipment' ? 'selected' : '' }}>Peralatan</option>
            <option value="maintenance" {{ request('category') == 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
            <option value="marketing" {{ request('category') == 'marketing' ? 'selected' : '' }}>Pemasaran</option>
            <option value="rent" {{ request('category') == 'rent' ? 'selected' : '' }}>Sewa</option>
            <option value="insurance" {{ request('category') == 'insurance' ? 'selected' : '' }}>Asuransi</option>
            <option value="tax" {{ request('category') == 'tax' ? 'selected' : '' }}>Pajak</option>
            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Lainnya</option>
        </select>
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-filter mr-2"></i>
            Filter
        </button>
    </form>
    <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Tambah Pengeluaran
    </button>
</div>

<!-- Expenses Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengeluaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($expenses as $expense)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-money-bill-wave text-red-600"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $expense->title }}</div>
                                <div class="text-sm text-gray-500">{{ $expense->expense_number }}</div>
                                @if($expense->supplier)
                                    <div class="text-xs text-gray-400">Supplier: {{ $expense->supplier }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $expense->status_badge }}">
                            {{ $expense->category_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">{{ $expense->formatted_amount }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $expense->status_badge }}">
                            {{ $expense->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $expense->expense_date->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button onclick="viewExpense({{ $expense->id }})" class="text-blue-600 hover:text-blue-900 p-1 rounded" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editExpense({{ $expense->id }})" class="text-green-600 hover:text-green-900 p-1 rounded" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDeleteExpense({{ $expense->id }}, '{{ addslashes($expense->title) }}')" class="text-red-600 hover:text-red-900 p-1 rounded" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex flex-col items-center py-8">
                            <i class="fas fa-money-bill-wave text-4xl text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium text-gray-600">Tidak ada pengeluaran ditemukan</p>
                            <p class="text-sm text-gray-500">Coba ubah filter pencarian Anda</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($expenses->hasPages())
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            @if($expenses->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $expenses->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Sebelumnya
                </a>
            @endif

            @if($expenses->hasMorePages())
                <a href="{{ $expenses->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Selanjutnya
                </a>
            @else
                <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                    Selanjutnya
                </span>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Create Expense Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[9997]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full transform transition-all duration-300 ease-in-out">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Pengeluaran Baru</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createForm" action="{{ route('expenses.store') }}" method="POST" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Pengeluaran *</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                        <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="operational">Operasional</option>
                            <option value="utilities">Utilitas</option>
                            <option value="salary">Gaji</option>
                            <option value="inventory">Inventaris</option>
                            <option value="equipment">Peralatan</option>
                            <option value="maintenance">Pemeliharaan</option>
                            <option value="marketing">Pemasaran</option>
                            <option value="rent">Sewa</option>
                            <option value="insurance">Asuransi</option>
                            <option value="tax">Pajak</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (Rp) *</label>
                        <input type="number" name="amount" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran *</label>
                        <select name="payment_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="cash">Tunai</option>
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="card">Kartu</option>
                            <option value="check">Cek</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="pending">Menunggu</option>
                            <option value="approved">Disetujui</option>
                            <option value="paid">Dibayar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pengeluaran *</label>
                        <input type="date" name="expense_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <input type="text" name="supplier" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan
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
let currentExpenseId = null;
let currentExpenseTitle = null;

// Toast notification functions
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    toastMessage.textContent = message;
    
    if (type === 'success') {
        toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-green-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-full';
        toastIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
    } else if (type === 'error') {
        toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-red-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-full';
        toastIcon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500"></i>';
    } else if (type === 'warning') {
        toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-yellow-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-full';
        toastIcon.innerHTML = '<i class="fas fa-exclamation-triangle text-yellow-500"></i>';
    } else {
        toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-blue-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-full';
        toastIcon.innerHTML = '<i class="fas fa-info-circle text-blue-500"></i>';
    }
    
    toast.classList.remove('hidden');
    
    // Slide in animation
    setTimeout(() => {
        if (type === 'success') {
            toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-green-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-0';
        } else if (type === 'error') {
            toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-red-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-0';
        } else if (type === 'warning') {
            toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-yellow-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-0';
        } else {
            toast.className = 'fixed top-4 right-4 z-[9999] bg-white rounded-lg shadow-xl border-l-4 border-blue-500 p-4 max-w-sm transform transition-all duration-300 ease-in-out translate-x-0';
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
}

// Confirmation modal functions
function showConfirmModal(title, message, action, expenseId = null, expenseTitle = null) {
    currentAction = action;
    currentExpenseId = expenseId;
    currentExpenseTitle = expenseTitle;
    
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentAction = null;
    currentExpenseId = null;
    currentExpenseTitle = null;
}

// Expense actions
function confirmDeleteExpense(id, title) {
    showConfirmModal(
        'Hapus Pengeluaran',
        `Apakah Anda yakin ingin menghapus pengeluaran "${title}"? Aksi ini tidak dapat dibatalkan.`,
        'delete',
        id,
        title
    );
}

function viewExpense(id) {
    showLoading();
    window.location.href = `{{ url('/expenses') }}/${id}`;
}

function editExpense(id) {
    showLoading();
    window.location.href = `{{ url('/expenses') }}/${id}/edit`;
}

// Handle confirmation actions
document.getElementById('confirmActionBtn').addEventListener('click', function() {
    if (currentAction === 'delete') {
        deleteExpense(currentExpenseId, currentExpenseTitle);
    }
    closeConfirmModal();
});

// Form submission with confirmation
document.getElementById('createForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    
    const formData = new FormData(this);
    
    fetch('{{ route("expenses.store") }}', {
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
            showToast('❌ ' + (data.message || 'Terjadi kesalahan'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌ Terjadi kesalahan saat menyimpan data', 'error');
    })
    .finally(() => {
        // Reset button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Delete expense function
function deleteExpense(id, title) {
    showLoading();
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/expenses/${id}`, {
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
            showToast('❌ ' + (data.message || 'Gagal menghapus pengeluaran'), 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('❌ Terjadi kesalahan saat menghapus data', 'error');
    });
}

// Show success message when page loads with success parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'true') {
        showToast('✅ Operasi berhasil dilakukan!', 'success');
    }
});

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
    const actionButtons = document.querySelectorAll('[onclick*="viewExpense"], [onclick*="editExpense"], [onclick*="confirmDeleteExpense"]');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Show welcome message if no expenses
    @if($expenses->count() == 0)
        showToast('👋 Selamat datang! Silakan tambahkan pengeluaran pertama Anda.', 'success');
    @endif
});
</script>
@endsection 