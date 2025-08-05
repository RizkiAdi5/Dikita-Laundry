@extends('layouts.app')

@section('title', 'Detail Pengeluaran - LaundryDikita')

@section('page-title', 'Detail Pengeluaran')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $expense->title }}</h3>
                    <p class="text-sm text-gray-600">{{ $expense->expense_number }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('expenses.edit', $expense) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('expenses.index') }}" 
                       class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900">Informasi Dasar</h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->title }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Pengeluaran</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->expense_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $expense->status_badge }} mt-1">
                                {{ $expense->category_label }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <p class="text-lg font-semibold text-gray-900 mt-1">{{ $expense->formatted_amount }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->description ?: 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900">Informasi Pembayaran</h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $expense->status_badge }} mt-1">
                                {{ $expense->status_label }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->payment_method_label }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Frekuensi</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->frequency_label }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Karyawan</label>
                            <p class="text-sm text-gray-900 mt-1">
                                {{ $expense->employee ? $expense->employee->name : 'Tidak ditentukan' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dates Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900">Informasi Tanggal</h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Pengeluaran</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->expense_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Jatuh Tempo</label>
                            <p class="text-sm text-gray-900 mt-1">
                                {{ $expense->due_date ? $expense->due_date->format('d M Y') : 'Tidak ada' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Dibayar</label>
                            <p class="text-sm text-gray-900 mt-1">
                                {{ $expense->paid_date ? $expense->paid_date->format('d M Y') : 'Belum dibayar' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900">Informasi Tambahan</h4>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Supplier</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->supplier ?: 'Tidak ada' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Receipt</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->receipt_number ?: 'Tidak ada' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Catatan</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->notes ?: 'Tidak ada catatan' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Status</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status:</span>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $expense->status_badge }}">
                                {{ $expense->status_label }}
                            </span>
                        </div>
                        @if($expense->is_overdue)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Terlambat:</span>
                            <span class="text-sm text-red-600 font-medium">{{ $expense->days_overdue }} hari</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Berulang:</span>
                            <span class="text-sm text-gray-900">{{ $expense->is_recurring ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Disetujui:</span>
                            <span class="text-sm text-gray-900">{{ $expense->is_approved ? 'Ya' : 'Tidak' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Aksi Cepat</h4>
                    <div class="space-y-2">
                        @if($expense->status === 'pending')
                        <button onclick="approveExpense({{ $expense->id }})" 
                                class="w-full px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                            <i class="fas fa-check mr-2"></i>Setujui
                        </button>
                        <button onclick="rejectExpense({{ $expense->id }})" 
                                class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                            <i class="fas fa-times mr-2"></i>Tolak
                        </button>
                        @endif
                        @if($expense->status === 'approved' && $expense->status !== 'paid')
                        <button onclick="markAsPaid({{ $expense->id }})" 
                                class="w-full px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <i class="fas fa-money-bill mr-2"></i>Tandai Dibayar
                        </button>
                        @endif
                        <button onclick="confirmDeleteExpense({{ $expense->id }}, '{{ addslashes($expense->title) }}')" 
                                class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>

            <!-- Created/Updated Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Informasi Sistem</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dibuat</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Terakhir Diupdate</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
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

function approveExpense(id) {
    showConfirmModal(
        'Setujui Pengeluaran',
        'Apakah Anda yakin ingin menyetujui pengeluaran ini?',
        'approve',
        id
    );
}

function rejectExpense(id) {
    showConfirmModal(
        'Tolak Pengeluaran',
        'Apakah Anda yakin ingin menolak pengeluaran ini?',
        'reject',
        id
    );
}

function markAsPaid(id) {
    showConfirmModal(
        'Tandai Dibayar',
        'Apakah Anda yakin ingin menandai pengeluaran ini sebagai dibayar?',
        'mark-as-paid',
        id
    );
}

// Handle confirmation actions
document.getElementById('confirmActionBtn').addEventListener('click', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    if (currentAction === 'delete') {
        deleteExpense(currentExpenseId, currentExpenseTitle);
    } else if (currentAction === 'approve') {
        updateExpenseStatus(currentExpenseId, 'approve', csrfToken);
    } else if (currentAction === 'reject') {
        updateExpenseStatus(currentExpenseId, 'reject', csrfToken);
    } else if (currentAction === 'mark-as-paid') {
        updateExpenseStatus(currentExpenseId, 'mark-as-paid', csrfToken);
    }
    closeConfirmModal();
});

// Update expense status
function updateExpenseStatus(id, action, csrfToken) {
    let url = '';
    if (action === 'approve') {
        url = `/expenses/${id}/approve`;
    } else if (action === 'reject') {
        url = `/expenses/${id}/reject`;
    } else if (action === 'mark-as-paid') {
        url = `/expenses/${id}/mark-as-paid`;
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('✅ ' + data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showToast('❌ ' + (data.message || 'Terjadi kesalahan'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌ Terjadi kesalahan saat memperbarui status', 'error');
    });
}

// Delete expense function
function deleteExpense(id, title) {
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
        if (data.success) {
            showToast('✅ ' + data.message, 'success');
            setTimeout(() => {
                window.location.href = '{{ route("expenses.index") }}';
            }, 1500);
        } else {
            showToast('❌ ' + (data.message || 'Gagal menghapus pengeluaran'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌ Terjadi kesalahan saat menghapus data', 'error');
    });
}

// Close modal when clicking outside
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeConfirmModal();
    }
});
</script>
@endsection 