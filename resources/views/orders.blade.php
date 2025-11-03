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
                <p class="text-sm text-green-600">+5 dari kemarin</p>
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
                <p class="text-sm text-yellow-600">Rata-rata 2.3 jam</p>
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
                <p class="text-sm text-green-600">65% completion rate</p>
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
                <p class="text-sm text-red-600">Perlu perhatian</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Actions -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="relative">
            <input type="text" placeholder="Cari pesanan..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Dikonfirmasi</option>
            <option value="in_progress">Dalam Proses</option>
            <option value="ready">Siap Diambil</option>
            <option value="completed">Selesai</option>
        </select>
        <input type="date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>
    <a href="{{ route('orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->items->first()->item_name ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->formatted_total }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $order->status->name }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 50%"></div>
                            </div>
                            <span class="text-xs text-gray-500">-</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('orders.edit', $order) }}" class="text-green-600 hover:text-green-900" title="Edit Pesanan">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan {{ $order->order_number }}?');">
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
                    <td colspan="8" class="px-6 py-10 text-center text-gray-500">Belum ada pesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Sebelumnya
            </a>
            <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Selanjutnya
            </a>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">10</span> dari <span class="font-medium">97</span> hasil
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">3</a>
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
function openCreateModal(){ document.getElementById('createOrderModal').classList.remove('hidden'); document.body.style.overflow='hidden'; }
function closeCreateModal(){ document.getElementById('createOrderModal').classList.add('hidden'); document.body.style.overflow='auto'; document.getElementById('orderCreateForm')?.reset(); }

document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('orderCreateForm');
  if(form){
    // Dynamic items handling
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

    addBtn.addEventListener('click', addRow);
    discountInput.addEventListener('input', recalc);
    taxInput.addEventListener('input', recalc);

    // Initialize with one row
    addRow();

    form.addEventListener('submit', function(e){
      e.preventDefault();
      const btn = document.getElementById('orderSubmitBtn');
      const original = btn.innerHTML;
      btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
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

      // collect dynamic items
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
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'X-Requested-With': 'XMLHttpRequest', 'Accept':'application/json' }
      }).then(r=>r.json()).then(data=>{
        if(data.success){ closeCreateModal(); location.reload(); }
        else{ alert(data.message || 'Gagal membuat pesanan'); }
      }).catch(()=>alert('Gagal membuat pesanan')).finally(()=>{ btn.disabled=false; btn.innerHTML=original; });
    });
  }
});
// Update timestamp
function updateTimestamp() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    document.getElementById('lastUpdate').textContent = timeString;
}

// Update every 30 seconds
setInterval(updateTimestamp, 30000);
updateTimestamp();

// Simulate real-time progress updates
function simulateProgressUpdates() {
    const progressBars = document.querySelectorAll('.bg-blue-600, .bg-green-600');
    progressBars.forEach(bar => {
        const currentWidth = parseInt(bar.style.width);
        if (currentWidth < 100) {
            const newWidth = Math.min(100, currentWidth + Math.random() * 5);
            bar.style.width = newWidth + '%';
            
            // Update percentage text
            const percentageText = bar.parentElement.nextElementSibling;
            if (percentageText) {
                percentageText.textContent = Math.round(newWidth) + '%';
            }
        }
    });
}

// Update progress every 10 seconds
setInterval(simulateProgressUpdates, 10000);

// Note: action button handlers for .text-blue-600 removed; eye icon now links directly to PDF
</script>
@endsection 