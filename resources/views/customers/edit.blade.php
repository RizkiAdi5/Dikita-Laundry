@extends('layouts.app')

@section('title', 'Edit Pelanggan - LaundryDikita')

@section('page-title', 'Edit Pelanggan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form id="editForm" class="space-y-6">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PUT">
            
            <!-- Nama -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ $customer->name }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="text-red-500 text-xs mt-1 hidden" id="name-error"></div>
            </div>

            <!-- Telepon -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                <input type="tel" name="phone" value="{{ $customer->phone }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="text-red-500 text-xs mt-1 hidden" id="phone-error"></div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ $customer->email }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="text-red-500 text-xs mt-1 hidden" id="email-error"></div>
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $customer->address }}</textarea>
            </div>

            <!-- Gender dan Tanggal Lahir -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male" {{ $customer->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ $customer->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ $customer->birth_date ? $customer->birth_date->format('Y-m-d') : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Membership Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Membership *</label>
                <select name="membership_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="regular" {{ $customer->membership_type == 'regular' ? 'selected' : '' }}>Regular</option>
                    <option value="silver" {{ $customer->membership_type == 'silver' ? 'selected' : '' }}>Silver</option>
                    <option value="gold" {{ $customer->membership_type == 'gold' ? 'selected' : '' }}>Gold</option>
                    <option value="platinum" {{ $customer->membership_type == 'platinum' ? 'selected' : '' }}>Platinum</option>
                </select>
            </div>

            <!-- Points -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Poin</label>
                <input type="number" name="points" value="{{ $customer->points }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Status -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $customer->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Pelanggan Aktif</span>
                </label>
            </div>

            <!-- Catatan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Catatan tambahan tentang pelanggan...">{{ $customer->notes }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('customers.index') }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" id="submitBtn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <span id="submitText">Update Pelanggan</span>
                    <span id="submitLoading" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[9997]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all duration-300 ease-in-out">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900" id="confirmTitle">Konfirmasi</h3>
                    </div>
                </div>
                <div class="mb-6">
                    <p class="text-sm text-gray-600" id="confirmMessage">Apakah Anda yakin ingin memperbarui data pelanggan ini?</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeConfirmModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button id="confirmActionBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Update
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
let formData = null;

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

function clearErrors() {
    const errorElements = document.querySelectorAll('[id$="-error"]');
    errorElements.forEach(element => {
        element.classList.add('hidden');
        element.textContent = '';
    });
}

// Confirmation modal functions
function showConfirmModal(title, message, action, data = null) {
    currentAction = action;
    formData = data;
    
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentAction = null;
    formData = null;
}

// Handle confirmation actions
document.getElementById('confirmActionBtn').addEventListener('click', function() {
    if (currentAction === 'update') {
        submitForm(formData);
    }
    closeConfirmModal();
});

// Form submission with confirmation
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formDataObj = new FormData(form);
    
    // Show confirmation modal
    showConfirmModal(
        'Update Pelanggan',
        'Apakah Anda yakin ingin memperbarui data pelanggan ini?',
        'update',
        formDataObj
    );
});

// Submit form function
function submitForm(formData) {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoading = document.getElementById('submitLoading');
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.classList.add('hidden');
    submitLoading.classList.remove('hidden');
    
    // Clear previous errors
    clearErrors();
    
    fetch('{{ route("customers.update", $customer->id) }}', {
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
            setTimeout(() => {
                window.location.href = '{{ route("customers.index") }}';
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

// Form validation on input
document.addEventListener('DOMContentLoaded', function() {
    const requiredFields = document.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
        
        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
    });
});
</script>
@endsection 