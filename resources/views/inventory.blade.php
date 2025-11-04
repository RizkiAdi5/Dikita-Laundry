@extends('layouts.app')

@section('title', 'Inventori - LaundryDikita')

@section('page-title', 'Inventori')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-boxes text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Item</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Stok Aman</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['in_stock'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Stok Menipis</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['low_stock'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-times-circle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Stok Habis</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['out_of_stock'] ?? 0) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Actions -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <form method="GET" action="{{ route('inventory.index') }}" class="flex flex-col sm:flex-row gap-4">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari item..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Semua Kategori</option>
            <option value="detergen" {{ request('category')=='detergen' ? 'selected' : '' }}>Detergen</option>
            <option value="pelembut" {{ request('category')=='pelembut' ? 'selected' : '' }}>Pelembut</option>
            <option value="pemutih" {{ request('category')=='pemutih' ? 'selected' : '' }}>Pemutih</option>
            <option value="plastik" {{ request('category')=='plastik' ? 'selected' : '' }}>Plastik</option>
            <option value="gantungan" {{ request('category')=='gantungan' ? 'selected' : '' }}>Gantungan</option>
            <option value="peralatan" {{ request('category')=='peralatan' ? 'selected' : '' }}>Peralatan</option>
            <option value="parfum" {{ request('category')=='parfum' ? 'selected' : '' }}>Parfum</option>
            <option value="pembersih_noda" {{ request('category')=='pembersih_noda' ? 'selected' : '' }}>Pembersih Noda</option>
            <option value="lainnya" {{ request('category')=='lainnya' ? 'selected' : '' }}>Lainnya</option>
        </select>
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Semua Status</option>
            <option value="in_stock" {{ request('status')=='in_stock' ? 'selected' : '' }}>Stok Aman</option>
            <option value="low_stock" {{ request('status')=='low_stock' ? 'selected' : '' }}>Stok Menipis</option>
            <option value="out_of_stock" {{ request('status')=='out_of_stock' ? 'selected' : '' }}>Stok Habis</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-filter mr-2"></i>
            Filter
        </button>
    </form>
    <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Tambah Item
    </button>
    </div>

<!-- Inventory Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($inventories as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                <div class="text-sm text-gray-500">{{ $item->unit }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->sku }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ str_replace('_',' ', ucfirst($item->category)) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ number_format($item->quantity, 2) }}</div>
                        <div class="text-xs text-gray-500">Min: {{ number_format($item->min_quantity, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->formatted_selling_price }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php $status = $item->stock_status; @endphp
                        @if($status === 'in_stock')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Stok Aman</span>
                        @elseif($status === 'low_stock')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Stok Menipis</span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Stok Habis</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('inventory.show', $item) }}" class="text-blue-600 hover:text-blue-900" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('inventory.edit', $item) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('inventory.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada data inventori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($inventories) && $inventories->hasPages())
    <!-- Pagination -->
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $inventories->firstItem() }}</span> sampai <span class="font-medium">{{ $inventories->lastItem() }}</span> dari <span class="font-medium">{{ $inventories->total() }}</span> hasil
                </p>
            </div>
            <div class="flex items-center space-x-1">
                @if ($inventories->onFirstPage())
                    <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-300 cursor-not-allowed rounded-md">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $inventories->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                @endif

                @foreach ($inventories->getUrlRange(1, $inventories->lastPage()) as $page => $url)
                    @if ($page == $inventories->currentPage())
                        <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-md">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if ($inventories->hasMorePages())
                    <a href="{{ $inventories->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-md">
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
@endsection 

@section('scripts')
<script>
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
    errorElements.forEach(el => { el.textContent = ''; el.classList.add('hidden'); });
}

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
    setTimeout(() => {
        toast.className = toast.className.replace('translate-x-full', 'translate-x-0');
    }, 10);
    setTimeout(() => { hideToast(); }, 5000);
}

function hideToast() {
    const toast = document.getElementById('toast');
    toast.className = toast.className.replace('translate-x-0', 'translate-x-full');
    setTimeout(() => { toast.classList.add('hidden'); }, 300);
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            clearErrors();

            const formData = new FormData(form);
            fetch('{{ route('inventory.store') }}', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('✅ ' + data.message, 'success');
                    closeCreateModal();
                    setTimeout(() => { location.reload(); }, 1200);
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const el = document.getElementById(field + '-error');
                            if (el) { el.textContent = data.errors[field][0]; el.classList.remove('hidden'); }
                        });
                        showToast('❌ Terdapat kesalahan pada form', 'error');
                    } else {
                        showToast('❌ ' + (data.message || 'Terjadi kesalahan'), 'error');
                    }
                }
            })
            .catch(() => { showToast('❌ Terjadi kesalahan saat menyimpan data', 'error'); })
            .finally(() => {
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            });
        });
    }

    const modal = document.getElementById('createModal');
    if (modal) {
        modal.addEventListener('click', function(e){ if(e.target===this){ closeCreateModal(); }});
    }
    document.addEventListener('keydown', function(e){ if(e.key==='Escape'){ closeCreateModal(); }});
});
</script>

<!-- Create Inventory Modal -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[9997]">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full transform transition-all duration-300 ease-in-out">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Tambah Item Inventori</h3>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createForm" method="POST" action="{{ route('inventory.store') }}" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                        <input name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <div class="text-red-500 text-xs mt-1 hidden" id="name-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                        <input name="sku" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <div class="text-red-500 text-xs mt-1 hidden" id="sku-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                        <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="detergent">Detergent</option>
                            <option value="fabric_softener">Fabric Softener</option>
                            <option value="bleach">Bleach</option>
                            <option value="plastic_bag">Plastic Bag</option>
                            <option value="hanger">Hanger</option>
                            <option value="equipment">Equipment</option>
                            <option value="other">Lainnya</option>
                        </select>
                        <div class="text-red-500 text-xs mt-1 hidden" id="category-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                        <input name="unit" required placeholder="kg, pcs, liter" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <div class="text-red-500 text-xs mt-1 hidden" id="unit-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal *</label>
                        <input type="number" step="0.01" min="0" name="quantity" required value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <div class="text-red-500 text-xs mt-1 hidden" id="quantity-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stok *</label>
                        <input type="number" step="0.01" min="0" name="min_quantity" required value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <div class="text-red-500 text-xs mt-1 hidden" id="min_quantity-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Modal</label>
                        <input type="number" step="0.01" min="0" name="cost_price" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <div class="text-red-500 text-xs mt-1 hidden" id="cost_price-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label>
                        <input type="number" step="0.01" min="0" name="selling_price" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <div class="text-red-500 text-xs mt-1 hidden" id="selling_price-error"></div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <input name="supplier" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <div class="text-red-500 text-xs mt-1 hidden" id="supplier-error"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <div class="text-red-500 text-xs mt-1 hidden" id="location-error"></div>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                    <div class="text-red-500 text-xs mt-1 hidden" id="description-error"></div>
                </div>
                <div class="flex items-center mt-2">
                    <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <label class="ml-2 text-sm text-gray-700">Aktif</label>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Batal</button>
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

<!-- Toast -->
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