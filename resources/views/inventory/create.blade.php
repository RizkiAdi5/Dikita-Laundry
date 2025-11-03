@extends('layouts.app')

@section('title', 'Tambah Inventori - LaundryDikita')
@section('page-title', 'Tambah Inventori')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form method="POST" action="{{ route('inventory.store') }}" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                <input name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU *</label>
                <input name="sku" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
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
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                <input name="unit" required placeholder="contoh: kg, pcs, liter" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal *</label>
                <input type="number" step="0.01" min="0" name="quantity" required value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stok *</label>
                <input type="number" step="0.01" min="0" name="min_quantity" required value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Modal</label>
                <input type="number" step="0.01" min="0" name="cost_price" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label>
                <input type="number" step="0.01" min="0" name="selling_price" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                <input name="supplier" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                <input name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_active" value="1" checked class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
            <label class="ml-2 text-sm text-gray-700">Aktif</label>
        </div>
        <div class="flex justify-end space-x-3">
            <a href="{{ route('inventory.index') }}" class="px-4 py-2 bg-gray-200 rounded-lg">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
    @if ($errors->any())
        <div class="mt-4 text-sm text-red-600">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    </div>
@endsection

