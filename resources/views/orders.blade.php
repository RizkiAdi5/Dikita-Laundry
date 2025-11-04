@extends('layouts.app')

@section('title', 'Pesanan - LaundryDikita')

@section('page-title', 'Pesanan')

@section('content')
<!-- Order Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pesanan Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['today'] ?? 0) }}</p>                
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-hourglass-half text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Dalam Proses</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['in_progress'] ?? 0) }}</p>
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
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['completed_today'] ?? 0) }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Terlambat</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['delayed'] ?? 0) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Actions -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <form method="GET" action="{{ route('orders.index') }}" class="flex flex-col sm:flex-row gap-4">
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesanan..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status->name }}" {{ request('status') == $status->name ? 'selected' : '' }}>
                    {{ $status->name }}
                </option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" onchange="this.form.submit()">
    </form>
    <a href="{{ route('orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center">
        <i class="fas fa-plus mr-2"></i>
        Pesanan Baru
    </a>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-semibold text-sm">{{ $order->customer->initials }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $order->customer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->customer->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $order->items->first()->item_name ?? '-' }}
                        @if($order->items->count() > 1)
                            <span class="text-gray-500 text-xs">+{{ $order->items->count() - 1 }} lainnya</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->formatted_total }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button 
                            onclick="openStatusModal({{ $order->id }}, '{{ $order->order_number }}', {{ $order->order_status_id }})"
                            class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full hover:opacity-80 transition-opacity cursor-pointer"
                            style="background-color: {{ $order->status->color }}20; color: {{ $order->status->color }};"
                            title="Klik untuk ubah status">
                            <i class="fas fa-circle text-xs mr-1.5" style="color: {{ $order->status->color }};"></i>
                            {{ $order->status->name }}
                        </button>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @php
                                $progress = 0;
                                switch($order->status->name) {
                                    case 'Menunggu': $progress = 10; break;
                                    case 'Dikonfirmasi': $progress = 30; break;
                                    case 'Dalam Proses': $progress = 60; break;
                                    case 'Siap': $progress = 85; break;
                                    case 'Selesai': $progress = 100; break;
                                    case 'Batal': $progress = 0; break;
                                    default: $progress = 0;
                                }
                                $barColor = $order->status->name == 'Selesai' ? 'bg-green-600' : ($order->status->name == 'Batal' ? 'bg-red-600' : 'bg-blue-600');
                            @endphp
                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $progress }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('orders.show', $order) }}" 
                            class="text-blue-600 hover:text-blue-900" 
                            title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('orders.receipt', $order) }}" 
                            target="_blank" 
                            class="text-orange-600 hover:text-orange-900" 
                            title="Print Resi">
                                <i class="fas fa-receipt"></i>
                            </a>
                            <a href="{{ route('orders.bill', $order) }}" 
                            target="_blank" 
                            class="text-purple-600 hover:text-purple-900" 
                            title="Cetak Bill">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <a href="{{ route('orders.edit', $order) }}" 
                            class="text-green-600 hover:text-green-900" 
                            title="Edit Pesanan">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('orders.destroy', $order) }}" 
                                method="POST" 
                                onsubmit="return confirm('Hapus pesanan {{ $order->order_number }}?');" 
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900" 
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p class="text-sm">Belum ada pesanan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="bg-white px-4 py-3 border-t border-gray-200">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-sync-alt mr-2 text-blue-600"></i>
                Update Status Pesanan
            </h3>
            <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="statusUpdateForm" onsubmit="updateOrderStatus(event)">
            <input type="hidden" id="statusOrderId" name="order_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pesanan: <span id="statusOrderNumber" class="font-bold text-blue-600"></span>
                </label>
            </div>

            <div class="mb-4">
                <label for="statusSelect" class="block text-sm font-medium text-gray-700 mb-2">
                    Status Baru <span class="text-red-500">*</span>
                </label>
                <select id="statusSelect" name="order_status_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Pilih Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" data-color="{{ $status->color }}">
                            {{ $status->name }}
                            @if($status->description)
                                - {{ $status->description }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="statusNotes" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan (Opsional)
                </label>
                <textarea 
                    id="statusNotes" 
                    name="notes" 
                    rows="3" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button 
                    type="button" 
                    onclick="closeStatusModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </button>
                <button 
                    type="submit" 
                    id="statusSubmitBtn"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-check mr-2"></i>
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
// ============================================================================
// STATUS MODAL FUNCTIONS
// ============================================================================
let currentOrderId = null;

function openStatusModal(orderId, orderNumber, currentStatusId) {
    currentOrderId = orderId;
    document.getElementById('statusOrderId').value = orderId;
    document.getElementById('statusOrderNumber').textContent = orderNumber;
    document.getElementById('statusSelect').value = currentStatusId;
    document.getElementById('statusNotes').value = '';
    document.getElementById('statusModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentOrderId = null;
}

function updateOrderStatus(event) {
    event.preventDefault();
    
    const btn = document.getElementById('statusSubmitBtn');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memperbarui...';
    
    const formData = new FormData(event.target);
    const orderId = formData.get('order_id');
    
    fetch(`/orders/${orderId}/status`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-HTTP-Method-Override': 'PATCH'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            closeStatusModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('error', data.message || 'Gagal mengubah status');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Terjadi kesalahan saat mengubah status');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    });
}

function showNotification(type, message) {
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-3 animate-fade-in`;
    notification.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('statusModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('statusModal').classList.contains('hidden')) {
        closeStatusModal();
    }
});

// ============================================================================
// CREATE ORDER MODAL FUNCTIONS (Existing)
// ============================================================================
function openCreateModal(){ 
    const modal = document.getElementById('createOrderModal');
    if(modal) {
        modal.classList.remove('hidden'); 
        document.body.style.overflow='hidden'; 
    }
}

function closeCreateModal(){ 
    const modal = document.getElementById('createOrderModal');
    if(modal) {
        modal.classList.add('hidden'); 
        document.body.style.overflow='auto'; 
        document.getElementById('orderCreateForm')?.reset(); 
    }
}

document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('orderCreateForm');
  if(form){
    const itemsContainer = document.getElementById('itemsContainer');
    const template = document.getElementById('itemRowTemplate');
    const addBtn = document.getElementById('addItemRowBtn');
    const discountInput = form.querySelector('input[name="discount"]');
    const taxInput = form.querySelector('input[name="tax"]');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const totalDisplay = document.getElementById('totalDisplay');

    function formatNumber(n){
      const v = isNaN(n) ? 0 : n;
      return 'Rp ' + Math.round(v).toLocaleString('id-ID');
    }

    function recalc(){
      let subtotal = 0;
      itemsContainer.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input').value || '0');
        const price = parseFloat(row.querySelector('.price-input').value || '0');
        const line = Math.max(0, qty * price);
        row.querySelector('.line-total').value = formatNumber(line);
        subtotal += line;
      });
      const discount = parseFloat(discountInput.value || '0');
      const tax = parseFloat(taxInput.value || '0');
      const total = Math.max(0, subtotal - discount + tax);
      subtotalDisplay.value = formatNumber(subtotal);
      totalDisplay.value = formatNumber(total);
    }

    function wireRow(row){
      const serviceSelect = row.querySelector('.service-select');
      const priceInput = row.querySelector('.price-input');
      const qtyInput = row.querySelector('.qty-input');
      const removeBtn = row.querySelector('.remove-row');

      function syncPrice(){
        const opt = serviceSelect?.selectedOptions?.[0];
        const price = opt ? opt.getAttribute('data-price') : '';
        if (price) priceInput.value = price;
        recalc();
      }

      serviceSelect.addEventListener('change', syncPrice);
      qtyInput.addEventListener('input', recalc);
      priceInput.addEventListener('input', recalc);
      removeBtn.addEventListener('click', function(){
        row.remove();
        if (itemsContainer.querySelectorAll('.item-row').length === 0) {
          addRow();
        } else {
          recalc();
        }
      });

      syncPrice();
    }

    function addRow(){
      const clone = template.firstElementChild.cloneNode(true);
      itemsContainer.appendChild(clone);
      wireRow(clone);
    }

    if(addBtn) addBtn.addEventListener('click', addRow);
    if(discountInput) discountInput.addEventListener('input', recalc);
    if(taxInput) taxInput.addEventListener('input', recalc);

    // Initialize with one row
    if(itemsContainer && template) addRow();

    form.addEventListener('submit', function(e){
      e.preventDefault();
      const btn = document.getElementById('orderSubmitBtn');
      if(!btn) return;
      
      const original = btn.innerHTML;
      btn.disabled=true; 
      btn.innerHTML='<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
      
      const fd = new FormData(form);
      const body = new FormData();
      body.append('customer_id', fd.get('customer_id'));
      body.append('pickup_date', fd.get('pickup_date'));
      body.append('delivery_date', fd.get('delivery_date'));
      if (fd.get('discount')) body.append('discount', fd.get('discount'));
      if (fd.get('tax')) body.append('tax', fd.get('tax'));
      if (fd.get('notes')) body.append('notes', fd.get('notes'));
      if (fd.get('payment_status')) body.append('payment_status', fd.get('payment_status'));
      if (fd.get('payment_method')) body.append('payment_method', fd.get('payment_method'));
      if (fd.get('paid_amount')) body.append('paid_amount', fd.get('paid_amount'));

      const rows = itemsContainer.querySelectorAll('.item-row');
      rows.forEach((row, idx) => {
        const serviceId = row.querySelector('.service-select').value;
        const qty = row.querySelector('.qty-input').value;
        const price = row.querySelector('.price-input').value;
        body.append(`items[${idx}][service_id]`, serviceId);
        body.append(`items[${idx}][quantity]`, qty);
        body.append(`items[${idx}][unit_price]`, price);
        const itemNotes = fd.get('item_notes');
        if (itemNotes) body.append(`items[${idx}][notes]`, itemNotes);
      });

      fetch('{{ route('orders.store') }}', {
        method: 'POST',
        body,
        credentials: 'same-origin',
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 
            'X-Requested-With': 'XMLHttpRequest', 
            'Accept':'application/json' 
        }
      }).then(r=>r.json()).then(data=>{
        if(data.success){ 
            showNotification('success', 'Pesanan berhasil dibuat');
            closeCreateModal(); 
            setTimeout(() => location.reload(), 1000);
        } else { 
            showNotification('error', data.message || 'Gagal membuat pesanan');
        }
      }).catch(()=>{
        showNotification('error', 'Gagal membuat pesanan');
      }).finally(()=>{ 
        btn.disabled=false; 
        btn.innerHTML=original; 
      });
    });
  }
});

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================
function updateTimestamp() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    const el = document.getElementById('lastUpdate');
    if(el) el.textContent = timeString;
}

setInterval(updateTimestamp, 30000);
updateTimestamp();
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease;
}

/* Smooth transitions for progress bars */
.transition-all {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
@endsection