@extends('layouts.app')

@section('title', 'Tambah Pengeluaran - LaundryDikita')

@section('page-title', 'Tambah Pengeluaran Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Form Pengeluaran Baru</h3>
            <p class="text-sm text-gray-600 mt-1">Isi form di bawah ini untuk menambahkan pengeluaran baru</p>
        </div>
        
        <form action="{{ route('expenses.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Judul Pengeluaran *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                           placeholder="Contoh: Pembelian Detergen">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori *</label>
                    <select name="category" id="category" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        <option value="operational" {{ old('category') == 'operational' ? 'selected' : '' }}>Operasional</option>
                        <option value="utilities" {{ old('category') == 'utilities' ? 'selected' : '' }}>Utilitas</option>
                        <option value="salary" {{ old('category') == 'salary' ? 'selected' : '' }}>Gaji</option>
                        <option value="inventory" {{ old('category') == 'inventory' ? 'selected' : '' }}>Inventaris</option>
                        <option value="equipment" {{ old('category') == 'equipment' ? 'selected' : '' }}>Peralatan</option>
                        <option value="maintenance" {{ old('category') == 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
                        <option value="marketing" {{ old('category') == 'marketing' ? 'selected' : '' }}>Pemasaran</option>
                        <option value="rent" {{ old('category') == 'rent' ? 'selected' : '' }}>Sewa</option>
                        <option value="insurance" {{ old('category') == 'insurance' ? 'selected' : '' }}>Asuransi</option>
                        <option value="tax" {{ old('category') == 'tax' ? 'selected' : '' }}>Pajak</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp) *</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount') border-red-500 @enderror"
                           placeholder="0">
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran *</label>
                    <select name="payment_method" id="payment_method" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                        <option value="">Pilih Metode</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Kartu</option>
                        <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Cek</option>
                        <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="">Pilih Status</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengeluaran *</label>
                    <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('expense_date') border-red-500 @enderror">
                    @error('expense_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                    <input type="text" name="supplier" id="supplier" value="{{ old('supplier') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('supplier') border-red-500 @enderror"
                           placeholder="Nama supplier">
                    @error('supplier')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Deskripsi detail pengeluaran">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('expenses.index') }}" 
                   class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill paid_date if status is paid
    const statusSelect = document.getElementById('status');
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'paid') {
            // Auto-check is_approved when status is paid
            const isApprovedCheckbox = document.querySelector('input[name="is_approved"]');
            if (isApprovedCheckbox) {
                isApprovedCheckbox.checked = true;
            }
        }
    });
    
    // Format amount input
    const amountInput = document.getElementById('amount');
    amountInput.addEventListener('input', function() {
        // Remove non-numeric characters except decimal point
        this.value = this.value.replace(/[^0-9.]/g, '');
    });
});
</script>
@endsection 