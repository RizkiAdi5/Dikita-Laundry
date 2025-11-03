@extends('layouts.app')

@section('title', 'Edit Pesanan - '.$order->order_number)
@section('page-title', 'Edit Pesanan')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <form id="orderEditForm" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan *</label>
                <select name="customer_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    @foreach(($customers ?? []) as $c)
                        <option value="{{ $c->id }}" @selected($order->customer_id==$c->id)>{{ $c->name }} - {{ $c->phone }}</option>
                    @endforeach
                </select>
            </div>
            <div></div>

            <div class="md:col-span-2">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Item Layanan *</label>
                    <button type="button" id="addItemRowBtn" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-plus mr-1"></i> Tambah Item
                    </button>
                </div>
                <div id="itemsContainer" class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="item-row grid grid-cols-12 gap-2 items-end">
                        <div class="col-span-5">
                            <label class="block text-xs text-gray-600 mb-1">Layanan</label>
                            <select class="service-select w-full px-3 py-2 border border-gray-300 rounded-lg">
                                @foreach(($services ?? []) as $s)
                                    <option value="{{ $s->id }}" data-price="{{ $s->price }}" @selected($item->service_id==$s->id)>{{ $s->name }} (Rp {{ number_format($s->price,0,',','.') }}/{{ $s->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Qty</label>
                            <input type="number" step="0.1" min="0.1" value="{{ (float)$item->quantity }}" class="qty-input w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Harga</label>
                            <input type="number" step="0.01" min="0" value="{{ (float)$item->unit_price }}" class="price-input w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="text" value="0" class="line-total w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                        </div>
                        <div class="col-span-1 flex">
                            <button type="button" class="remove-row w-full px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div id="itemRowTemplate" class="hidden">
                    <div class="item-row grid grid-cols-12 gap-2 items-end">
                        <div class="col-span-5">
                            <label class="block text-xs text-gray-600 mb-1">Layanan</label>
                            <select class="service-select w-full px-3 py-2 border border-gray-300 rounded-lg">
                                @foreach(($services ?? []) as $s)
                                    <option value="{{ $s->id }}" data-price="{{ $s->price }}">{{ $s->name }} (Rp {{ number_format($s->price,0,',','.') }}/{{ $s->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Qty</label>
                            <input type="number" step="0.1" min="0.1" value="1" class="qty-input w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Harga</label>
                            <input type="number" step="0.01" min="0" value="0" class="price-input w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="text" value="0" class="line-total w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50" readonly>
                        </div>
                        <div class="col-span-1 flex">
                            <button type="button" class="remove-row w-full px-3 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Ambil</label>
                <input type="date" name="pickup_date" value="{{ optional($order->pickup_date)->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="delivery_date" value="{{ optional($order->delivery_date)->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Diskon</label>
                <input type="number" name="discount" step="0.01" min="0" value="{{ (float)$order->discount }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pajak</label>
                <input type="number" name="tax" step="0.01" min="0" value="{{ (float)$order->tax }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="unpaid" @selected($order->payment_status==='unpaid')>Belum dibayar</option>
                    <option value="partial" @selected($order->payment_status==='partial')>Sebagian</option>
                    <option value="paid" @selected($order->payment_status==='paid')>Lunas</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="cash">Tunai</option>
                    <option value="transfer">Transfer</option>
                    <option value="ewallet">E-Wallet</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dibayar</label>
                <input type="number" name="paid_amount" step="0.01" min="0" value="{{ (float)$order->paid_amount }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg">{{ $order->notes }}</textarea>
            </div>
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="col-span-1 md:col-span-2"></div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal</label>
                    <input type="text" id="subtotalDisplay" class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50" readonly value="0">
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                    <input type="text" id="totalDisplay" class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50" readonly value="0">
                </div>
            </div>
        </div>
        <div class="flex justify-end space-x-3">
            <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Batal</a>
            <button type="submit" id="orderUpdateBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Update</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('orderEditForm');
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
      if (price && (!priceInput.value || Number(priceInput.value) === 0)) priceInput.value = price;
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

  // Wire existing rows
  itemsContainer.querySelectorAll('.item-row').forEach(wireRow);
  recalc();

  addBtn.addEventListener('click', addRow);
  discountInput.addEventListener('input', recalc);
  taxInput.addEventListener('input', recalc);

  form.addEventListener('submit', function(e){
    e.preventDefault();
    const btn = document.getElementById('orderUpdateBtn');
    const original = btn.innerHTML;
    btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    const fd = new FormData(form);
    const body = new FormData();
    body.append('_method', 'PUT');
    body.append('customer_id', fd.get('customer_id'));
    if (fd.get('pickup_date')) body.append('pickup_date', fd.get('pickup_date'));
    if (fd.get('delivery_date')) body.append('delivery_date', fd.get('delivery_date'));
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
    });

    fetch("{{ route('orders.update', $order) }}", {
      method: 'POST',
      body,
      credentials: 'same-origin',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'X-Requested-With': 'XMLHttpRequest', 'Accept':'application/json' }
    }).then(r=>r.json()).then(data=>{
      if(data.success){ window.location = "{{ route('orders.show', $order) }}"; }
      else{ alert(data.message || 'Gagal mengupdate pesanan'); }
    }).catch(()=>alert('Gagal mengupdate pesanan')).finally(()=>{ btn.disabled=false; btn.innerHTML=original; });
  });
});
</script>
@endsection


